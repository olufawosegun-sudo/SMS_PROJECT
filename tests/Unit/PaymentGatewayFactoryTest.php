<?php

namespace Tests\Unit;

use App\Services\PaymentGateways\Contracts\PaymentGatewayInterface;
use App\Services\PaymentGateways\Drivers\BankTransferGateway;
use App\Services\PaymentGateways\Drivers\CashPaymentGateway;
use App\Services\PaymentGateways\Drivers\FlutterwaveGateway;
use App\Services\PaymentGateways\Drivers\PaystackGateway;
use App\Services\PaymentGateways\PaymentGatewayFactory;
use InvalidArgumentException;
use Tests\TestCase;

class PaymentGatewayFactoryTest extends TestCase
{
    public function test_factory_creates_paystack_gateway()
    {
        $gateway = PaymentGatewayFactory::make('paystack');

        $this->assertInstanceOf(PaymentGatewayInterface::class, $gateway);
        $this->assertInstanceOf(PaystackGateway::class, $gateway);
        $this->assertEquals('Paystack', $gateway->getName());
        $this->assertEquals('paystack', $gateway->getDriver());
    }

    public function test_factory_creates_flutterwave_gateway()
    {
        $gateway = PaymentGatewayFactory::make('flutterwave');

        $this->assertInstanceOf(PaymentGatewayInterface::class, $gateway);
        $this->assertInstanceOf(FlutterwaveGateway::class, $gateway);
        $this->assertEquals('Flutterwave', $gateway->getName());
        $this->assertEquals('flutterwave', $gateway->getDriver());
    }

    public function test_factory_creates_bank_transfer_gateway()
    {
        $gateway = PaymentGatewayFactory::make('bank_transfer');

        $this->assertInstanceOf(PaymentGatewayInterface::class, $gateway);
        $this->assertInstanceOf(BankTransferGateway::class, $gateway);
        $this->assertEquals('Bank Transfer / Teller Upload', $gateway->getName());
        $this->assertEquals('bank_transfer', $gateway->getDriver());
    }

    public function test_factory_creates_cash_gateway()
    {
        $gateway = PaymentGatewayFactory::make('cash');

        $this->assertInstanceOf(PaymentGatewayInterface::class, $gateway);
        $this->assertInstanceOf(CashPaymentGateway::class, $gateway);
        $this->assertEquals('Cash Collection', $gateway->getName());
        $this->assertEquals('cash', $gateway->getDriver());
    }

    public function test_factory_throws_exception_for_unsupported_driver()
    {
        $this->expectException(InvalidArgumentException::class);
        PaymentGatewayFactory::make('invalid_gateway_name');
    }

    public function test_factory_checks_supported_drivers()
    {
        $this->assertTrue(PaymentGatewayFactory::isSupported('paystack'));
        $this->assertTrue(PaymentGatewayFactory::isSupported('flutterwave'));
        $this->assertTrue(PaymentGatewayFactory::isSupported('bank_transfer'));
        $this->assertTrue(PaymentGatewayFactory::isSupported('cash'));
        $this->assertFalse(PaymentGatewayFactory::isSupported('bitcoin'));
    }
}
