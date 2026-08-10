<?php

namespace App\Services\PaymentGateways\Contracts;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Initialize a payment transaction with the gateway.
     */
    public function initializePayment(Payment $payment, array $options = []): array;

    /**
     * Verify a transaction reference with the gateway.
     */
    public function verifyPayment(string $reference): array;

    /**
     * Process an incoming webhook payload.
     */
    public function handleWebhook(array $payload): bool;

    /**
     * Get the human-readable gateway driver name.
     */
    public function getName(): string;

    /**
     * Get the gateway driver identifier string.
     */
    public function getDriver(): string;
}
