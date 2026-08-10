<?php

namespace App\Services\PaymentGateways\Drivers;

use App\Models\Payment;
use App\Services\PaymentGateways\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackGateway implements PaymentGatewayInterface
{
    protected string $secretKey;

    protected string $baseUrl = 'https://api.paystack.co';

    public function __construct(?string $secretKey = null)
    {
        $this->secretKey = $secretKey ?? config('services.paystack.secret_key', '');
    }

    /**
     * Initialize payment transaction with Paystack.
     */
    public function initializePayment(Payment $payment, array $options = []): array
    {
        $email = $options['email'] ?? $payment->student?->user?->email ?? 'student@school.com';
        $callbackUrl = $options['callback_url'] ?? route('payments.verify', ['reference' => $payment->reference]);

        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/transaction/initialize", [
                    'amount' => (int) round($payment->amount * 100), // convert to kobo
                    'email' => $email,
                    'reference' => $payment->reference,
                    'callback_url' => $callbackUrl,
                    'metadata' => [
                        'payment_id' => $payment->id,
                        'school_id' => $payment->school_id,
                        'student_id' => $payment->student_id,
                        'custom_fields' => [
                            [
                                'display_name' => 'Payment Reference',
                                'variable_name' => 'payment_reference',
                                'value' => $payment->reference,
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json('data');

                return [
                    'status' => 'success',
                    'authorization_url' => $data['authorization_url'] ?? null,
                    'access_code' => $data['access_code'] ?? null,
                    'reference' => $data['reference'] ?? $payment->reference,
                    'raw' => $data,
                ];
            }

            Log::error('Paystack Initialization Error', ['response' => $response->body()]);

            return [
                'status' => 'error',
                'message' => $response->json('message', 'Failed to initialize Paystack transaction'),
            ];

        } catch (\Exception $e) {
            Log::error('Paystack Gateway Exception', ['error' => $e->getMessage()]);

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment transaction with Paystack.
     */
    public function verifyPayment(string $reference): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transaction/verify/{$reference}");

            if ($response->successful()) {
                $data = $response->json('data');
                $isPaid = ($data['status'] ?? '') === 'success';

                return [
                    'status' => $isPaid ? 'confirmed' : 'failed',
                    'paid' => $isPaid,
                    'amount' => ($data['amount'] ?? 0) / 100, // convert kobo back to main currency
                    'reference' => $reference,
                    'gateway_reference' => $data['id'] ?? null,
                    'channel' => $data['channel'] ?? 'paystack',
                    'paid_at' => $data['paid_at'] ?? now()->toIso8601String(),
                    'raw' => $data,
                ];
            }

            return [
                'status' => 'failed',
                'paid' => false,
                'message' => $response->json('message', 'Verification failed'),
            ];

        } catch (\Exception $e) {
            Log::error('Paystack Verification Exception', ['error' => $e->getMessage()]);

            return [
                'status' => 'error',
                'paid' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process Paystack webhook.
     */
    public function handleWebhook(array $payload): bool
    {
        $event = $payload['event'] ?? '';

        if ($event === 'charge.success') {
            $reference = $payload['data']['reference'] ?? null;
            if ($reference) {
                // Return true to indicate successful handling
                return true;
            }
        }

        return false;
    }

    public function getName(): string
    {
        return 'Paystack';
    }

    public function getDriver(): string
    {
        return 'paystack';
    }
}
