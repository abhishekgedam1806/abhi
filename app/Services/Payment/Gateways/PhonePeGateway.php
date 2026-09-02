<?php

namespace App\Services\Payment\Gateways;

use App\Order;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;

class PhonePeGateway implements PaymentGatewayInterface
{
    protected string $merchantId;
    protected string $saltKey;
    protected int $saltIndex;
    protected string $env;

    public function __construct()
    {
        $this->merchantId = env('PHONEPE_MERCHANT_ID', '');
        $this->saltKey = env('PHONEPE_SALT_KEY', '');
        $this->saltIndex = intval(env('PHONEPE_SALT_INDEX', 1));
        $this->env = env('PHONEPE_ENV', 'UAT'); // UAT or PROD
    }

    public function getName(): string
    {
        return 'phonepe';
    }

    public function createOrder(Order $order, array $options = []): array
    {
        Log::info('PhonePe Gateway Adapter ready for activation', ['order_number' => $order->order_number]);

        return [
            'success' => false,
            'error'   => 'PhonePe payment gateway adapter is installed and ready for merchant credentials configuration.',
        ];
    }

    public function verifyPayment(array $payload): array
    {
        return [
            'success' => false,
            'error'   => 'PhonePe verification not yet enabled.',
        ];
    }

    public function handleWebhook(string $rawPayload, string $signature): array
    {
        return [
            'success' => false,
            'error'   => 'PhonePe webhook not yet enabled.',
        ];
    }

    public function getPaymentStatus(string $gatewayPaymentId): array
    {
        return [
            'status' => 'pending',
        ];
    }

    public function refundPayment(string $gatewayPaymentId, float $amount, ?string $reason = null): array
    {
        return [
            'success' => false,
            'error'   => 'PhonePe refund not yet enabled.',
        ];
    }
}
