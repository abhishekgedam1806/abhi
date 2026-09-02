<?php

namespace App\Http\Controllers;

use App\Order;
use App\Payment;
use App\Package;
use App\Company;
use App\User;
use App\PaymentWebhook;
use App\Services\Payment\PaymentService;
use App\Services\Coupon\CouponService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;
    protected CouponService $couponService;

    public function __construct(PaymentService $paymentService, CouponService $couponService)
    {
        $this->paymentService = $paymentService;
        $this->couponService = $couponService;
    }

    /**
     * Get current authenticated buyer (Company or User)
     */
    protected function getAuthenticatedBuyer()
    {
        if (Auth::guard('company')->check()) {
            return Auth::guard('company')->user();
        } elseif (Auth::check()) {
            return Auth::user();
        }
        return null;
    }

    /**
     * Step 1: Package Checkout / Order Initiation
     */
    public function checkout(Request $request, $package_id)
    {
        $buyer = $this->getAuthenticatedBuyer();
        if (!$buyer) {
            flash(__('Please login to purchase a package.'))->error();
            return redirect()->route('login');
        }

        $package = Package::findOrFail($package_id);
        $gatewayName = $request->input('gateway', 'razorpay');

        // Create internal order and Razorpay order
        $result = $this->paymentService->createPackageOrder($buyer, $package->id, $gatewayName);

        if (!$result['success']) {
            flash($result['error'] ?? __('Failed to initialize payment.'))->error();
            return redirect()->back();
        }

        // If package is free (₹0), redirect directly to success
        if (!empty($result['is_free'])) {
            flash(__('Free package activated successfully!'))->success();
            return redirect()->route('payment.success', ['order_number' => $result['order']->order_number]);
        }

        $order = $result['order'];
        $razorpayKey = $result['key_id'];

        return view('payment.checkout', compact('order', 'package', 'result', 'razorpayKey', 'buyer'));
    }

    /**
     * Step 2: Client Verification Callback (Server-side HMAC verification)
     */
    public function verifyPayment(Request $request)
    {
        $razorpayOrderId   = $request->input('razorpay_order_id');
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpaySignature = $request->input('razorpay_signature');
        $orderNumber       = $request->input('order_number');

        if (!$orderNumber) {
            $order = Order::where('gateway_order_id', $razorpayOrderId)->first();
        } else {
            $order = Order::where('order_number', $orderNumber)->first();
        }

        if (!$order) {
            Log::error('PaymentController: Order not found during verification', [
                'order_number' => $orderNumber,
                'gateway_order_id' => $razorpayOrderId,
            ]);
            return response()->json([
                'success' => false,
                'message' => __('Order record not found.'),
            ], 404);
        }

        // Verify with Razorpay Gateway
        $gateway = $this->paymentService->gateway($order->gateway);
        $verification = $gateway->verifyPayment([
            'razorpay_order_id'   => $razorpayOrderId,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_signature' => $razorpaySignature,
        ]);

        if (!$verification['success']) {
            $this->paymentService->markOrderFailed($order, $verification['error'] ?? 'Signature verification mismatch', $verification);

            return response()->json([
                'success'     => false,
                'message'     => $verification['error'] ?? __('Payment verification failed.'),
                'redirect_url'=> route('payment.failed', ['order_number' => $order->order_number]),
            ], 400);
        }

        // Fulfill and activate package idempotently
        $this->paymentService->fulfillOrder($order, $verification, 'checkout_callback');

        return response()->json([
            'success'      => true,
            'message'      => __('Payment verified successfully.'),
            'redirect_url' => route('payment.success', ['order_number' => $order->order_number]),
        ]);
    }

    /**
     * Step 3: Webhook Endpoint (Server-to-Server Confirmation)
     */
    public function handleWebhook(Request $request, $gateway = 'razorpay')
    {
        $rawPayload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature') ?: '';

        Log::info("Payment Webhook received for [{$gateway}]", [
            'has_signature' => !empty($signature),
            'payload_length' => strlen($rawPayload),
        ]);

        try {
            $gatewayInstance = $this->paymentService->gateway($gateway);
            $result = $gatewayInstance->handleWebhook($rawPayload, $signature);

            if (!$result['success']) {
                Log::warning("Webhook signature verification failed for [{$gateway}]: " . ($result['error'] ?? ''));
                return response()->json(['status' => 'invalid_signature'], 400);
            }

            $eventId = $result['event_id'];
            $eventType = $result['event_type'];

            // IDEMPOTENCY CHECK: Prevent duplicate processing of the same webhook event
            $existingWebhook = PaymentWebhook::where('event_id', $eventId)->first();
            if ($existingWebhook && $existingWebhook->processed) {
                Log::info("Webhook event [{$eventId}] already processed (Idempotent bypass)");
                return response()->json(['status' => 'already_processed'], 200);
            }

            if (!$existingWebhook) {
                $existingWebhook = PaymentWebhook::create([
                    'gateway'            => $gateway,
                    'event_id'           => $eventId,
                    'event_type'         => $eventType,
                    'payload'            => $rawPayload,
                    'signature_verified' => true,
                    'processed'          => false,
                ]);
            }

            // Process supported payment events (e.g. payment.captured, order.paid)
            if (in_array($eventType, ['payment.captured', 'order.paid', 'payment_link.paid'])) {
                $gatewayOrderId = $result['order_id'];
                $order = Order::where('gateway_order_id', $gatewayOrderId)->first();

                if ($order) {
                    $this->paymentService->fulfillOrder($order, $result, 'webhook');
                } else {
                    Log::warning("Webhook order not found for gateway_order_id: {$gatewayOrderId}");
                }
            } elseif ($eventType === 'payment.failed') {
                $gatewayOrderId = $result['order_id'];
                $order = Order::where('gateway_order_id', $gatewayOrderId)->first();
                if ($order) {
                    $this->paymentService->markOrderFailed($order, 'Payment failed at gateway', $result);
                }
            }

            // Mark webhook as processed
            $existingWebhook->processed = true;
            $existingWebhook->processed_at = Carbon::now();
            $existingWebhook->save();

            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            Log::error("Webhook processing error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Payment Success Page
     */
    public function paymentSuccess(Request $request, $order_number)
    {
        $order = Order::with(['payments', 'package'])->where('order_number', $order_number)->firstOrFail();
        $buyer = $order->payable;
        $payment = $order->latestPayment;

        return view('payment.success', compact('order', 'buyer', 'payment'));
    }

    /**
     * Payment Failure Page
     */
    public function paymentFailed(Request $request, $order_number)
    {
        $order = Order::with('package')->where('order_number', $order_number)->firstOrFail();
        $buyer = $order->payable;
        $payment = $order->latestPayment;

        return view('payment.failed', compact('order', 'buyer', 'payment'));
    }

    /**
     * Download/Print Invoice
     */
    public function downloadInvoice($order_number)
    {
        $order = Order::with(['payments', 'package'])->where('order_number', $order_number)->firstOrFail();
        $siteSetting = \App\SiteSetting::first();
        $buyer = $order->payable;
        $payment = $order->latestPayment;

        return view('payment.invoice', compact('order', 'siteSetting', 'buyer', 'payment'));
    }

    /**
     * Employer Transaction History in Dashboard
     */
    public function myPayments(Request $request)
    {
        $company = Auth::guard('company')->user();
        if (!$company) {
            flash(__('Please login as employer.'))->error();
            return redirect()->route('login');
        }

        $orders = Order::where('payable_type', get_class($company))
            ->where('payable_id', $company->id)
            ->with(['payments', 'package'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('company.my_payments', compact('orders', 'company'));
    }

    /**
     * Step 1.5: Validate & Apply Promo Coupon Code at Checkout
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'coupon_code'  => 'required|string|max:50',
        ]);

        $order = Order::with('package')->where('order_number', $request->order_number)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => __('Order not found.')], 404);
        }

        if ($order->status === 'paid') {
            return response()->json(['success' => false, 'message' => __('Order is already completed.')], 400);
        }

        $buyer = $this->getAuthenticatedBuyer();
        $validation = $this->couponService->validateCoupon($request->coupon_code, $order->package, $buyer, $order->package_price);

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ], 422);
        }

        // Apply discount to Order model
        $order->coupon_id       = $validation['coupon_id'];
        $order->coupon_code     = $validation['coupon_code'];
        $order->discount_amount = $validation['discount_amount'];
        $order->total_amount    = max(0, $validation['final_amount'] + floatval($order->tax_amount));

        // Re-generate Gateway Order for updated amount if online gateway
        $amountPaise = intval(round($order->total_amount * 100));
        if ($amountPaise > 0 && !empty($order->gateway)) {
            try {
                $gateway = $this->paymentService->gateway($order->gateway);
                $gatewayOrder = $gateway->createOrder($amountPaise, $order->currency, [
                    'receipt' => $order->order_number,
                    'notes'   => ['package_id' => $order->package_id, 'coupon' => $order->coupon_code]
                ]);
                if ($gatewayOrder['success']) {
                    $order->gateway_order_id = $gatewayOrder['gateway_order_id'];
                }
            } catch (\Exception $e) {
                Log::warning("Gateway order recalculation skipped: " . $e->getMessage());
            }
        }

        $order->save();

        return response()->json([
            'success'                   => true,
            'message'                   => $validation['message'],
            'coupon_code'               => $validation['coupon_code'],
            'discount_type'             => $validation['discount_type'],
            'formatted_discount'        => $validation['formatted_discount'],
            'discount_amount'           => $validation['discount_amount'],
            'discount_amount_formatted' => '₹' . number_format($validation['discount_amount'], 2),
            'package_price_formatted'   => '₹' . number_format($order->package_price, 2),
            'total_amount'              => $order->total_amount,
            'total_amount_formatted'    => '₹' . number_format($order->total_amount, 2),
            'amount_paise'              => $amountPaise,
            'gateway_order_id'          => $order->gateway_order_id,
        ]);
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
        ]);

        $order = Order::with('package')->where('order_number', $request->order_number)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => __('Order not found.')], 404);
        }

        if ($order->status === 'paid') {
            return response()->json(['success' => false, 'message' => __('Order is already completed.')], 400);
        }

        $order->coupon_id       = null;
        $order->coupon_code     = null;
        $order->discount_amount = 0.00;
        $order->total_amount    = max(0, floatval($order->package_price) + floatval($order->tax_amount));

        $amountPaise = intval(round($order->total_amount * 100));
        if ($amountPaise > 0 && !empty($order->gateway)) {
            try {
                $gateway = $this->paymentService->gateway($order->gateway);
                $gatewayOrder = $gateway->createOrder($amountPaise, $order->currency, [
                    'receipt' => $order->order_number,
                ]);
                if ($gatewayOrder['success']) {
                    $order->gateway_order_id = $gatewayOrder['gateway_order_id'];
                }
            } catch (\Exception $e) {
                Log::warning("Gateway order reset skipped: " . $e->getMessage());
            }
        }

        $order->save();

        return response()->json([
            'success'                   => true,
            'message'                   => __('Coupon removed.'),
            'discount_amount'           => 0.00,
            'discount_amount_formatted' => '₹0.00',
            'package_price_formatted'   => '₹' . number_format($order->package_price, 2),
            'total_amount'              => $order->total_amount,
            'total_amount_formatted'    => '₹' . number_format($order->total_amount, 2),
            'amount_paise'              => $amountPaise,
            'gateway_order_id'          => $order->gateway_order_id,
        ]);
    }
}
