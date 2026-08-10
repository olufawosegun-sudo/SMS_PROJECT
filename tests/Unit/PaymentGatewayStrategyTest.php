<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\Services\PaymentGateways\Drivers\BankTransferGateway;
use App\Services\PaymentGateways\Drivers\CashPaymentGateway;
use App\Services\PaymentGateways\Drivers\FlutterwaveGateway;
use App\Services\PaymentGateways\Drivers\PaystackGateway;
use PHPUnit\Framework\TestCase;

class PaymentGatewayStrategyTest extends TestCase
{
    public function test_bank_transfer_strategy_initialization()
    {
        $gateway = new BankTransferGateway;
        $payment = new Payment([
            'reference' => 'PAY20260810TEST01',
            'amount' => 50000,
        ]);

        $result = $gateway->initializePayment($payment);

        $this->assertEquals('pending_upload', $result['status']);
        $this->assertEquals('PAY20260810TEST01', $result['reference']);
    }

    public function test_cash_strategy_initialization_and_verification()
    {
        $gateway = new CashPaymentGateway;
        $payment = new Payment([
            'reference' => 'PAY20260810TEST02',
            'amount' => 20000,
        ]);

        $result = $gateway->initializePayment($payment);
        $this->assertEquals('confirmed', $result['status']);

        $verification = $gateway->verifyPayment('PAY20260810TEST02');
        $this->assertTrue($verification['paid']);
        $this->assertEquals('confirmed', $verification['status']);
    }

    public function test_paystack_webhook_handling()
    {
        $gateway = new PaystackGateway('sk_test_12345');

        $successPayload = [
            'event' => 'charge.success',
            'data' => [
                'reference' => 'PAY20260810TEST03',
                'amount' => 1500000,
            ],
        ];

        $this->assertTrue($gateway->handleWebhook($successPayload));
        $this->assertFalse($gateway->handleWebhook(['event' => 'charge.failed']));
    }

    public function test_flutterwave_webhook_handling()
    {
        $gateway = new FlutterwaveGateway('FLWSECK_TEST-12345');

        $successPayload = [
            'data' => [
                'status' => 'successful',
                'tx_ref' => 'PAY20260810TEST04',
            ],
        ];

        $this->assertTrue($gateway->handleWebhook($successPayload));
        $this->assertFalse($gateway->handleWebhook(['data' => ['status' => 'failed']]));
    }
}
