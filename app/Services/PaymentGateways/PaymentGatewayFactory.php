<?php

namespace App\Services\PaymentGateways;

use App\Services\PaymentGateways\Contracts\PaymentGatewayInterface;
use App\Services\PaymentGateways\Drivers\BankTransferGateway;
use App\Services\PaymentGateways\Drivers\CashPaymentGateway;
use App\Services\PaymentGateways\Drivers\FlutterwaveGateway;
use App\Services\PaymentGateways\Drivers\PaystackGateway;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    /**
     * Driver mapping array.
     */
    protected static array $drivers = [
        'paystack' => PaystackGateway::class,
        'flutterwave' => FlutterwaveGateway::class,
        'bank_transfer' => BankTransferGateway::class,
        'teller' => BankTransferGateway::class,
        'cash' => CashPaymentGateway::class,
    ];

    /**
     * Create and return the gateway strategy instance.
     *
     * @throws InvalidArgumentException
     */
    public static function make(string $driver, array $config = []): PaymentGatewayInterface
    {
        $normalizedDriver = strtolower(trim($driver));

        if (! static::isSupported($normalizedDriver)) {
            throw new InvalidArgumentException("Unsupported payment gateway driver: [{$driver}]");
        }

        $className = static::$drivers[$normalizedDriver];

        return app()->makeWith($className, $config);
    }

    /**
     * Check if a driver is supported.
     */
    public static function isSupported(string $driver): bool
    {
        return array_key_exists(strtolower(trim($driver)), static::$drivers);
    }

    /**
     * Get list of all available driver keys.
     */
    public static function getAvailableDrivers(): array
    {
        return array_keys(static::$drivers);
    }
}
