<?php

use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\Onpay as OnpayClient;
use Netbums\Onpay\Resources\AcquirerResource;
use Netbums\Onpay\Resources\GatewayResource;
use Netbums\Onpay\Resources\PaymentResource;
use Netbums\Onpay\Resources\ProviderResource;
use Netbums\Onpay\Resources\SubscriptionResource;
use Netbums\Onpay\Resources\TransactionResource;
use Netbums\Onpay\Resources\WalletResource;
use Netbums\Onpay\Support\HmacCalculator;

it('resolves the Onpay singleton', function () {
    $onpay = app(OnpayClient::class);

    expect($onpay)->toBeInstanceOf(OnpayClient::class);
});

it('resolves the same instance from the container', function () {
    $onpay1 = app(OnpayClient::class);
    $onpay2 = app(OnpayClient::class);

    expect($onpay1)->toBe($onpay2);
});

it('provides resource accessors via facade', function () {
    expect(Onpay::payments())->toBeInstanceOf(PaymentResource::class);
    expect(Onpay::transactions())->toBeInstanceOf(TransactionResource::class);
    expect(Onpay::subscriptions())->toBeInstanceOf(SubscriptionResource::class);
    expect(Onpay::gateway())->toBeInstanceOf(GatewayResource::class);
    expect(Onpay::acquirers())->toBeInstanceOf(AcquirerResource::class);
    expect(Onpay::providers())->toBeInstanceOf(ProviderResource::class);
    expect(Onpay::wallets())->toBeInstanceOf(WalletResource::class);
});

it('provides hmac calculator via facade', function () {
    expect(Onpay::hmac())->toBeInstanceOf(HmacCalculator::class);
});

it('provides gateway id via facade', function () {
    expect(Onpay::gatewayId())->toBe('20007895654');
});

it('provides window url via facade', function () {
    expect(Onpay::windowUrl())->toBe('https://onpay.io/window/v3/');
});
