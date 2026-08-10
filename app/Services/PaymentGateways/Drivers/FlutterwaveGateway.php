<?php

namespace App\Services\PaymentGateways\Drivers;

use App\Models\Payment;
use App\Services\PaymentGateways\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveGateway implements PaymentGatewayInterface
{
    protected string $secretKey;

    protected string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct(?string $secretKey = null)
    {
        $this->secretKey = $secretKey ?? config('services.flutterwave.secret_key', '');
    }

    /**
     * Initialize payment transaction with Flutterwave.
     */
    public function initializePayment(Payment $payment, array $options = []): array
    {
        $email = $options['email'] ?? $payment->student?->user?->email ?? 'student@school.com';
        $name = $options['name'] ?? $payment->student?->user?->first_name.' '.$payment->student?->user?->last_name;
        $callbackUrl = $options['redirect_url'] ?? route('payments.verify', ['reference' => $payment->reference]);

        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/payments", [
                    'tx_ref' => $payment->reference,
                    'amount' => $payment->amount,
                    'currency' => $options['currency'] ?? 'NGN',
                    'redirect_url' => $callbackUrl,
                    'customer' => [
                        'email' => $email,
                        'name' => trim($name) ?: 'Student',
                    ],
                    'customizations' => [
                        'title' => 'School Fee Payment',
                        'description' => $payment->description ?? "Payment for Invoice #{$payment->invoice_id}",
                    ],
                    'meta' => [
                        'payment_id' => $payment->id,
                        'school_id' => $payment->school_id,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json('data');

                return [
                    'status' => 'success',
                    'authorization_url' => $data['link'] ?? null,
                    'reference' => $payment->reference,
                    'raw' => $data,
                ];
            }

            Log::error('Flutterwave Initialization Error', ['response' => $response->body()]);

            return [
                'status' => 'error',
                'message' => $response->json('message', 'Failed to initialize Flutterwave payment'),
            ];

        } catch (\Exception $e) {
            Log::error('Flutterwave Gateway Exception', ['error' => $e->getMessage()]);

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment transaction with Flutterwave.
     */
    public function verifyPayment(string $reference): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transactions/verify-by-txref", [
                    'tx_ref' => $reference,
                ]);

            if ($response->successful()) {
                $data = $response->json('data');
                $isSuccessful = ($data['status'] ?? '') === 'successful';

                return [
                    'status' => $isSuccessful ? 'confirmed' : 'failed',
                    'paid' => $isSuccessful,
                    'amount' => $data['amount'] ?? 0,
                    'reference' => $reference,
                    'gateway_reference' => $data['id'] ?? null,
                    'channel' => 'flutterwave',
                    'paid_at' => $data['created_at'] ?? now()->toIso8601String(),
                    'raw' => $data,
                ];
            }

            return [
                'status' => 'failed',
                'paid' => false,
                'message' => $response->json('message', 'Verification failed'),
            ];

        } catch (\Exception $e) {
            Log::error('Flutterwave Verification Exception', ['error' => $e->getMessage()]);

            return [
                'status' => 'error',
                'paid' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process Flutterwave webhook.
     */
    public function handleWebhook(array $payload): bool
    {
        $status = $payload['data']['status'] ?? '';
        if ($status === 'successful') {
            return true;
        }

        return false;
    }

    public function getName(): string
    {
        return 'Flutterwave';
    }

    public function getDriver(): string
    {
        return 'flutterwave';
    }
}
