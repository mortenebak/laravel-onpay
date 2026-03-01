<?php

namespace Netbums\Onpay\Facades;

use Illuminate\Support\Facades\Facade;
use Netbums\Onpay\Resources\AcquirerResource;
use Netbums\Onpay\Resources\GatewayResource;
use Netbums\Onpay\Resources\PaymentResource;
use Netbums\Onpay\Resources\ProviderResource;
use Netbums\Onpay\Resources\SubscriptionResource;
use Netbums\Onpay\Resources\TransactionResource;
use Netbums\Onpay\Resources\WalletResource;
use Netbums\Onpay\Support\HmacCalculator;

/**
 * @method static PaymentResource payments()
 * @method static TransactionResource transactions()
 * @method static SubscriptionResource subscriptions()
 * @method static GatewayResource gateway()
 * @method static AcquirerResource acquirers()
 * @method static ProviderResource providers()
 * @method static WalletResource wallets()
 * @method static HmacCalculator hmac()
 * @method static string gatewayId()
 * @method static string windowUrl()
 *
 * @see \Netbums\Onpay\Onpay
 */
class Onpay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Netbums\Onpay\Onpay::class;
    }
}
