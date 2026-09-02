<?php

namespace App\Services\Payment;

use App\Order;
use App\Payment;
use App\Package;
use App\Company;
use App\User;
use App\PaymentWebhook;
use App\PaymentRefund;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Gateways\RazorpayGateway;
use App\Services\Payment\Gateways\PhonePeGateway;
use App\Traits\CompanyPackageTrait;
use App\Traits\JobSeekerPackageTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    use CompanyPackageTrait;
    use JobSeekerPackageTrait;

    protected array $gateways = [];
    protected string $defaultGateway = 'razorpay';

    public function __construct()
    {
        $this->gateways['razorpay'] = new RazorpayGateway();
        $this->gateways['phonepe']  = new PhonePeGateway();
    }

    /**
     * Get specific gateway or default
     */
    public function gateway(?string $name = null): PaymentGatewayInterface
    {
        $name = strtolower($name ?: $this->defaultGateway);

        if (!isset($this->gateways[$name])) {
            throw new Exception("Payment gateway [{$name}] is not registered.");
        }

        return $this->gateways[$name];
    }

    /**
     * Create an internal order and corresponding gateway order
     *
     * @param mixed $payable (Company, User, etc.)
     * @param int $packageId
     * @param string $gatewayName
     * @param array $options
     * @return array
     */
    public function createPackageOrder($payable, int $packageId, string $gatewayName = 'razorpay', array $options = []): array
    {
        $package = Package::findOrFail($packageId);

        // Server-side calculated pricing
        $basePrice = floatval($package->package_price);
        $discountAmount = 0.00;
        $taxAmount = 0.00; // Tax/GST can be configured modularly
        $totalAmount = max(0, $basePrice - $discountAmount + $taxAmount);

        $packageType = $package->package_for ?? 'employer';
        if ($payable instanceof Company) {
            $packageType = 'employer';
        } elseif ($payable instanceof User) {
            $packageType = 'job_seeker';
        }

        $currency = 'INR';
        $siteSetting = \App\SiteSetting::first();
        if ($siteSetting && !empty($siteSetting->default_currency_code)) {
            $currency = $siteSetting->default_currency_code;
        }

        // Create internal order in pending state
        $order = new Order();
        $order->order_number    = Order::generateOrderNumber();
        $order->payable_type     = get_class($payable);
        $order->payable_id       = $payable->id;
        $order->package_id       = $package->id;
        $order->package_type     = $packageType;
        $order->package_title    = $package->package_title;
        $order->package_price    = $basePrice;
        $order->discount_amount  = $discountAmount;
        $order->tax_amount       = $taxAmount;
        $order->total_amount     = $totalAmount;
        $order->currency         = $currency;
        $order->status           = 'pending';
        $order->gateway          = $gatewayName;
        $order->notes            = $options['notes'] ?? null;
        $order->save();

        // If package is 100% Free (₹0), fulfill immediately
        if ($totalAmount <= 0) {
            $this->fulfillOrder($order, [
                'payment_id'     => 'FREE_' . uniqid(),
                'payment_method' => 'free',
                'amount'         => 0.00,
                'currency'       => $currency,
            ], 'free_order');

            return [
                'success' => true,
                'is_free' => true,
                'order'   => $order,
            ];
        }

        // Create gateway order
        $gateway = $this->gateway($gatewayName);
        $gatewayResult = $gateway->createOrder($order);

        if (!$gatewayResult['success']) {
            $order->status = 'failed';
            $order->notes = 'Gateway Order Error: ' . ($gatewayResult['error'] ?? 'Unknown error');
            $order->save();

            return [
                'success' => false,
                'error'   => $gatewayResult['error'] ?? 'Unable to initialize payment gateway order.',
                'order'   => $order,
            ];
        }

        // Store gateway order ID
        $order->gateway_order_id = $gatewayResult['gateway_order_id'] ?? null;
        $order->status = 'pending';
        $order->save();

        return [
            'success'          => true,
            'is_free'          => false,
            'order'            => $order,
            'gateway'          => $gatewayName,
            'gateway_order_id' => $order->gateway_order_id,
            'amount_paise'     => intval(round($order->total_amount * 100)),
            'currency'         => $order->currency,
            'key_id'           => ($gateway instanceof RazorpayGateway) ? $gateway->getKeyId() : '',
            'buyer_name'       => $order->buyer_name,
            'buyer_email'      => $order->buyer_email,
            'buyer_phone'      => $order->buyer_phone,
        ];
    }

    /**
     * Atomically fulfill and activate an order upon verified payment (Idempotent)
     *
     * @param Order $order
     * @param array $paymentData
     * @param string $source ('callback', 'webhook', 'admin')
     * @return bool
     */
    public function fulfillOrder(Order $order, array $paymentData, string $source = 'callback'): bool
    {
        return DB::transaction(function () use ($order, $paymentData, $source) {
            // Lock order for update to prevent concurrent duplicate activations
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();

            if (!$lockedOrder) {
                Log::error('PaymentService: Order not found during fulfillment', ['order_id' => $order->id]);
                return false;
            }

            // IDEMPOTENCY CHECK: If order is already paid, do not re-activate benefits
            if ($lockedOrder->status === 'paid') {
                Log::info('PaymentService: Order already marked as PAID (Idempotent bypass)', [
                    'order_id'     => $lockedOrder->id,
                    'order_number' => $lockedOrder->order_number,
                    'source'       => $source,
                ]);
                return true;
            }

            // Update Order status to PAID
            $lockedOrder->status = 'paid';
            $lockedOrder->save();

            // Record or Update Payment row
            $gatewayPaymentId = $paymentData['payment_id'] ?? ($paymentData['gateway_payment_id'] ?? null);

            $payment = Payment::where('order_id', $lockedOrder->id)
                ->where('gateway_payment_id', $gatewayPaymentId)
                ->first();

            if (!$payment) {
                $payment = new Payment();
                $payment->order_id           = $lockedOrder->id;
                $payment->payable_type       = $lockedOrder->payable_type;
                $payment->payable_id         = $lockedOrder->payable_id;
                $payment->gateway            = $lockedOrder->gateway;
                $payment->gateway_payment_id = $gatewayPaymentId;
                $payment->gateway_order_id   = $lockedOrder->gateway_order_id;
                $payment->amount             = $lockedOrder->total_amount;
                $payment->currency           = $lockedOrder->currency;
                $payment->payment_method     = $paymentData['payment_method'] ?? 'online';
                $payment->payment_status     = 'paid';
                $payment->transaction_reference = $gatewayPaymentId;
                $payment->raw_response       = json_encode($paymentData['raw'] ?? $paymentData);
                $payment->paid_at            = Carbon::now();
                $payment->save();
            } else {
                $payment->payment_status = 'paid';
                $payment->paid_at        = Carbon::now();
                $payment->save();
            }

            // Activate Package Quota on the model
            $this->activatePackageBenefit($lockedOrder);

            // Record Coupon Redemption if coupon was applied
            if (!empty($lockedOrder->coupon_id)) {
                try {
                    $couponService = app(\App\Services\Coupon\CouponService::class);
                    $couponService->redeemCoupon($lockedOrder, $gatewayPaymentId);
                } catch (\Exception $e) {
                    Log::error('PaymentService: Failed to redeem coupon for order', [
                        'order_id' => $lockedOrder->id,
                        'coupon_id' => $lockedOrder->coupon_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('PaymentService: Order successfully fulfilled and activated', [
                'order_number' => $lockedOrder->order_number,
                'payable_type' => $lockedOrder->payable_type,
                'payable_id'   => $lockedOrder->payable_id,
                'package_id'   => $lockedOrder->package_id,
                'source'       => $source,
            ]);

            return true;
        });
    }

    /**
     * Mark an order as failed
     */
    public function markOrderFailed(Order $order, string $reason, ?array $raw = null): void
    {
        if ($order->status !== 'paid') {
            $order->status = 'failed';
            $order->notes = $reason;
            $order->save();

            // Record failed payment attempt
            $payment = new Payment();
            $payment->order_id       = $order->id;
            $payment->payable_type   = $order->payable_type;
            $payment->payable_id     = $order->payable_id;
            $payment->gateway        = $order->gateway;
            $payment->amount         = $order->total_amount;
            $payment->currency       = $order->currency;
            $payment->payment_status = 'failed';
            $payment->failure_reason = $reason;
            $payment->raw_response   = json_encode($raw);
            $payment->save();
        }
    }

    /**
     * Activate the purchased package quota according to business rules
     */
    protected function activatePackageBenefit(Order $order): void
    {
        $package = Package::find($order->package_id);
        if (!$package) {
            Log::error('PaymentService: Package not found during benefit activation', ['package_id' => $order->package_id]);
            return;
        }

        $payable = $order->payable;
        if (!$payable) {
            Log::error('PaymentService: Payable entity not found during benefit activation', [
                'type' => $order->payable_type,
                'id'   => $order->payable_id,
            ]);
            return;
        }

        if ($payable instanceof Company) {
            // If company already has an active valid package, upgrade/extend it; otherwise fresh activate
            if ($payable->package_end_date && $payable->package_end_date->gt(Carbon::now())) {
                $this->updateCompanyPackage($payable, $package);
            } else {
                $this->addCompanyPackage($payable, $package);
            }
        } elseif ($payable instanceof User) {
            if ($payable->package_end_date && $payable->package_end_date->gt(Carbon::now())) {
                $this->updateUserPackage($payable, $package);
            } else {
                $this->addUserPackage($payable, $package);
            }
        }
    }

    /**
     * Process a Refund for an Order / Payment
     */
    public function processRefund(Payment $payment, float $amount, ?string $reason = null): array
    {
        if ($payment->payment_status !== 'paid') {
            return [
                'success' => false,
                'error'   => 'Only successfully paid transactions can be refunded.',
            ];
        }

        $gateway = $this->gateway($payment->gateway);
        $result = $gateway->refundPayment($payment->gateway_payment_id, $amount, $reason);

        if ($result['success']) {
            DB::transaction(function () use ($payment, $amount, $reason, $result) {
                // Record Refund
                PaymentRefund::create([
                    'payment_id'        => $payment->id,
                    'order_id'          => $payment->order_id,
                    'gateway'           => $payment->gateway,
                    'gateway_refund_id' => $result['refund_id'] ?? null,
                    'amount'            => $amount,
                    'currency'          => $payment->currency,
                    'status'            => $result['status'] ?? 'processed',
                    'reason'            => $reason,
                    'raw_response'      => json_encode($result['raw'] ?? []),
                ]);

                // Update Payment & Order Status
                $payment->payment_status = 'refunded';
                $payment->save();

                $order = $payment->order;
                if ($order) {
                    $order->status = 'refunded';
                    $order->save();
                }
            });

            return [
                'success'   => true,
                'refund_id' => $result['refund_id'] ?? null,
            ];
        }

        return [
            'success' => false,
            'error'   => $result['error'] ?? 'Gateway rejected the refund request.',
        ];
    }
}
