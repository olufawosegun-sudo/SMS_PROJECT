<?php

namespace App\Services\PaymentGateways\Drivers;

use App\Models\Payment;
use App\Services\PaymentGateways\Contracts\PaymentGatewayInterface;

class BankTransferGateway implements PaymentGatewayInterface
{
    /**
     * Initialize manual Bank Transfer / Teller Upload transaction.
     */
    public function initializePayment(Payment $payment, array $options = []): array
    {
        return [
            'status' => 'pending_upload',
            'action' => 'upload_teller',
            'reference' => $payment->reference,
            'message' => 'Please transfer the payment amount to the designated school bank account and upload your bank teller/receipt.',
            'raw' => [
                'bank_name' => $options['bank_name'] ?? 'School Bank Account',
                'account_number' => $options['account_number'] ?? 'N/A',
                'account_name' => $options['account_name'] ?? 'School Official Account',
            ],
        ];
    }

    /**
     * Verify Bank Transfer status.
     */
    public function verifyPayment(string $reference): array
    {
        return [
            'status' => 'pending',
            'paid' => false,
            'reference' => $reference,
            'message' => 'Bank transfer payments require manual review and verification by the school Bursar or Owner.',
        ];
    }

    /**
     * Webhook not applicable for manual bank transfer.
     */
    public function handleWebhook(array $payload): bool
    {
        return false;
    }

    public function getName(): string
    {
        return 'Bank Transfer / Teller Upload';
    }

    public function getDriver(): string
    {
        return 'bank_transfer';
    }
}
