<?php

use Illuminate\Support\Facades\Http;
use Netbums\Onpay\DataObjects\SimpleProviderData;
use Netbums\Onpay\DataObjects\SimpleWalletData;
use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('lists all providers', function () {
    Http::fake([
        'api.onpay.io/v1/provider' => Http::response(Fixtures::providerList()),
    ]);

    $providers = Onpay::providers()->all();

    expect($providers)->toHaveCount(3);
    expect($providers[0])->toBeInstanceOf(SimpleProviderData::class);
    expect($providers[0]->name)->toBe('Anyday');
    expect($providers[0]->active)->toBeTrue();
    expect($providers[2]->name)->toBe('Viabill');
    expect($providers[2]->active)->toBeFalse();
});

it('lists all wallets', function () {
    Http::fake([
        'api.onpay.io/v1/wallet' => Http::response(Fixtures::walletList()),
    ]);

    $wallets = Onpay::wallets()->all();

    expect($wallets)->toHaveCount(3);
    expect($wallets[0])->toBeInstanceOf(SimpleWalletData::class);
    expect($wallets[0]->name)->toBe('applepay');
    expect($wallets[0]->active)->toBeTrue();
    expect($wallets[2]->name)->toBe('mobilepay');
});
