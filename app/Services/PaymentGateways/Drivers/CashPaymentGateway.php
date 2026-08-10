<?php

namespace App\Services\PaymentGateways\Drivers;

use App\Models\Payment;
use App\Services\PaymentGateways\Contracts\PaymentGatewayInterface;

class CashPaymentGateway implements PaymentGatewayInterface
{
    /**
     * Initialize Cash Payment at Bursary.
     */
    public function initializePayment(Payment $payment, array $options = []): array
    {
        return [
            'status' => 'confirmed',
            'action' => 'bursary_cash_received',
            'reference' => $payment->reference,
            'message' => 'Cash payment received and processed directly at the school bursary.',
            'raw' => [
                'received_by' => $options['received_by'] ?? null,
                'payment_date' => now()->toIso8601String(),
            ],
        ];
    }

    /**
     * Verify Cash payment.
     */
    public function verifyPayment(string $reference): array
    {
        return [
            'status' => 'confirmed',
            'paid' => true,
            'reference' => $reference,
            'channel' => 'cash',
            'message' => 'Cash payment verified at bursary.',
        ];
    }

    /**
     * Webhook not applicable for cash payments.
     */
    public function handleWebhook(array $payload): bool
    {
        return false;
    }

    public function getName(): string
    {
        return 'Cash Collection';
    }

    public function getDriver(): string
    {
        return 'cash';
    }
}
