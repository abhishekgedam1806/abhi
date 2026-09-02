<?php

namespace App\Services\Payment\Contracts;

use App\Order;

interface PaymentGatewayInterface
{
    /**
     * Get unique identifier for the gateway (e.g. 'razorpay', 'phonepe')
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Create gateway order / transaction initiation
     *
     * @param Order $order
     * @param array $options
     * @return array
     */
    public function createOrder(Order $order, array $options = []): array;

    /**
     * Verify payment signature/response from client
     *
     * @param array $payload
     * @return array ['success' => bool, 'payment_id' => string|null, 'order_id' => string|null, 'method' => string|null, 'raw' => mixed, 'error' => string|null]
     */
    public function verifyPayment(array $payload): array;

    /**
     * Verify and process incoming webhook payload
     *
     * @param string $rawPayload
     * @param string $signature
     * @return array ['success' => bool, 'event_id' => string, 'event_type' => string, 'order_id' => string|null, 'payment_id' => string|null, 'status' => string|null, 'raw' => mixed, 'error' => string|null]
     */
    public function handleWebhook(string $rawPayload, string $signature): array;

    /**
     * Fetch payment status directly from Gateway API
     *
     * @param string $gatewayPaymentId
     * @return array
     */
    public function getPaymentStatus(string $gatewayPaymentId): array;

    /**
     * Process a refund via Gateway API
     *
     * @param string $gatewayPaymentId
     * @param float $amount
     * @param string|null $reason
     * @return array ['success' => bool, 'refund_id' => string|null, 'status' => string, 'raw' => mixed, 'error' => string|null]
     */
    public function refundPayment(string $gatewayPaymentId, float $amount, ?string $reason = null): array;
}
