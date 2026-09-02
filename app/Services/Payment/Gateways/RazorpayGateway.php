<?php

namespace App\Services\Payment\Gateways;

use App\Order;
use App\SiteSetting;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class RazorpayGateway implements PaymentGatewayInterface
{
    protected string $keyId;
    protected string $keySecret;
    protected string $webhookSecret;
    protected string $mode;
    protected Client $client;
    protected string $baseUrl = 'https://api.razorpay.com/v1/';

    public function __construct()
    {
        $setting = SiteSetting::first();

        // Preference: Environment variables > SiteSettings DB > Defaults
        $this->keyId = env('RAZORPAY_KEY_ID', $setting->razorpay_key ?? '');
        $this->keySecret = env('RAZORPAY_KEY_SECRET', $setting->razorpay_secret ?? '');
        $this->webhookSecret = env('RAZORPAY_WEBHOOK_SECRET', $setting->razorpay_webhook_secret ?? '');
        $this->mode = env('RAZORPAY_MODE', $setting->razorpay_mode ?? 'test');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 30,
            'auth'     => [$this->keyId, $this->keySecret],
            'headers'  => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
        ]);
    }

    public function getName(): string
    {
        return 'razorpay';
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Create Razorpay Order on server
     */
    public function createOrder(Order $order, array $options = []): array
    {
        // Amount in Paise (e.g., INR 999 -> 99900 paise)
        $amountInPaise = intval(round($order->total_amount * 100));

        $payload = [
            'amount'          => $amountInPaise,
            'currency'        => strtoupper($order->currency ?: 'INR'),
            'receipt'         => (string) $order->order_number,
            'payment_capture' => 1, // Auto-capture payment
            'notes'           => [
                'order_id'     => (string) $order->id,
                'order_number' => (string) $order->order_number,
                'package_id'   => (string) $order->package_id,
                'package_type' => (string) $order->package_type,
                'buyer_name'   => (string) $order->buyer_name,
                'buyer_email'  => (string) $order->buyer_email,
            ],
        ];

        try {
            Log::info('Razorpay: Creating Order', ['order_number' => $order->order_number, 'amount' => $amountInPaise]);

            $response = $this->client->post('orders', [
                'json' => $payload,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (!empty($body['id'])) {
                return [
                    'success'          => true,
                    'gateway_order_id' => $body['id'],
                    'amount'           => $body['amount'],
                    'currency'         => $body['currency'],
                    'raw'              => $body,
                ];
            }

            return [
                'success' => false,
                'error'   => 'Invalid response from Razorpay order API.',
                'raw'     => $body,
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay: Order Creation Failed', [
                'message' => $e->getMessage(),
                'order'   => $order->order_number,
            ]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment signature from frontend checkout callback
     */
    public function verifyPayment(array $payload): array
    {
        $razorpayOrderId = $payload['razorpay_order_id'] ?? '';
        $razorpayPaymentId = $payload['razorpay_payment_id'] ?? '';
        $razorpaySignature = $payload['razorpay_signature'] ?? '';

        if (empty($razorpayOrderId) || empty($razorpayPaymentId) || empty($razorpaySignature)) {
            return [
                'success' => false,
                'error'   => 'Missing required payment verification parameters.',
            ];
        }

        // Generate expected signature: HMAC_SHA256(order_id + "|" + payment_id, secret)
        $expectedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $this->keySecret);

        if (!hash_equals($expectedSignature, $razorpaySignature)) {
            Log::warning('Razorpay: Signature Verification Failed', [
                'order_id'   => $razorpayOrderId,
                'payment_id' => $razorpayPaymentId,
            ]);

            return [
                'success'    => false,
                'error'      => 'Payment signature verification failed. Possible tampering detected.',
                'order_id'   => $razorpayOrderId,
                'payment_id' => $razorpayPaymentId,
            ];
        }

        // Fetch detailed payment data from Razorpay API for method, amount, status
        $paymentDetails = $this->getPaymentStatus($razorpayPaymentId);
        $method = $paymentDetails['method'] ?? 'online';

        return [
            'success'          => true,
            'gateway'          => $this->getName(),
            'gateway_order_id' => $razorpayOrderId,
            'payment_id'       => $razorpayPaymentId,
            'payment_method'   => $method,
            'amount'           => isset($paymentDetails['amount']) ? ($paymentDetails['amount'] / 100) : null,
            'currency'         => $paymentDetails['currency'] ?? 'INR',
            'raw'              => $paymentDetails,
        ];
    }

    /**
     * Fetch payment status directly from Razorpay
     */
    public function getPaymentStatus(string $gatewayPaymentId): array
    {
        try {
            $response = $this->client->get("payments/{$gatewayPaymentId}");
            return json_decode($response->getBody()->getContents(), true) ?: [];
        } catch (\Exception $e) {
            Log::error('Razorpay: Failed to fetch payment status', [
                'payment_id' => $gatewayPaymentId,
                'error'      => $e->getMessage(),
            ]);
            return ['status' => 'unknown', 'error' => $e->getMessage()];
        }
    }

    /**
     * Process Razorpay Webhook Event
     */
    public function handleWebhook(string $rawPayload, string $signature): array
    {
        if (empty($this->webhookSecret)) {
            Log::warning('Razorpay: Webhook secret not configured in application settings.');
            return [
                'success' => false,
                'error'   => 'Webhook secret not configured on server.',
            ];
        }

        // Verify webhook signature: HMAC_SHA256(raw_payload, webhook_secret)
        $expectedSignature = hash_hmac('sha256', $rawPayload, $this->webhookSecret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Razorpay Webhook: Invalid Signature');
            return [
                'success' => false,
                'error'   => 'Invalid webhook signature.',
            ];
        }

        $data = json_decode($rawPayload, true);
        if (!$data || empty($data['event'])) {
            return [
                'success' => false,
                'error'   => 'Invalid webhook payload structure.',
            ];
        }

        $event = $data['event'];
        $eventId = $data['event_id'] ?? ('evt_' . md5($rawPayload));
        $paymentEntity = $data['payload']['payment']['entity'] ?? [];
        $orderEntity = $data['payload']['order']['entity'] ?? [];

        $orderId = $paymentEntity['order_id'] ?? ($orderEntity['id'] ?? null);
        $paymentId = $paymentEntity['id'] ?? null;
        $status = $paymentEntity['status'] ?? null;

        return [
            'success'    => true,
            'event_id'   => $eventId,
            'event_type' => $event,
            'order_id'   => $orderId,
            'payment_id' => $paymentId,
            'status'     => $status,
            'method'     => $paymentEntity['method'] ?? null,
            'amount'     => isset($paymentEntity['amount']) ? ($paymentEntity['amount'] / 100) : null,
            'raw'        => $data,
        ];
    }

    /**
     * Refund a payment
     */
    public function refundPayment(string $gatewayPaymentId, float $amount, ?string $reason = null): array
    {
        $amountInPaise = intval(round($amount * 100));

        $payload = [
            'amount' => $amountInPaise,
            'notes'  => [
                'reason' => $reason ?: 'Customer requested refund',
            ],
        ];

        try {
            $response = $this->client->post("payments/{$gatewayPaymentId}/refund", [
                'json' => $payload,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            return [
                'success'   => true,
                'refund_id' => $body['id'] ?? null,
                'status'    => $body['status'] ?? 'processed',
                'raw'       => $body,
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay Refund Failed', [
                'payment_id' => $gatewayPaymentId,
                'error'      => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }
}
