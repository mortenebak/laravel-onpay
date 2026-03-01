<?php

use Netbums\Onpay\DataObjects\DetailedAcquirerData;
use Netbums\Onpay\DataObjects\SimpleAcquirerData;
use Netbums\Onpay\DataObjects\SimpleProviderData;
use Netbums\Onpay\DataObjects\SimpleWalletData;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('creates SimpleAcquirerData from array', function () {
    $data = SimpleAcquirerData::fromArray(['name' => 'nets', 'active' => true, 'links' => ['self' => '/v1/acquirer/nets']]);

    expect($data)
        ->name->toBe('nets')
        ->active->toBeTrue();
});

it('creates DetailedAcquirerData from response', function () {
    $data = DetailedAcquirerData::fromArray(Fixtures::detailedAcquirer());

    expect($data)
        ->name->toBe('clearhaus')
        ->active->toBeTrue()
        ->apiKey->toBe('3764eca2-a225-44b6-b5cc-c069efaf38ac')
        ->mcc->toBe('1234')
        ->merchantId->toBe('000000001234567')
        ->scaMode->toBe('all')
        ->mastercardBin->toBe('123456')
        ->visaBin->toBe('123456');

    expect($data->exemptions)->toHaveKey('sca_low_value', true);
});

it('creates SimpleProviderData from array', function () {
    $data = SimpleProviderData::fromArray(['name' => 'Klarna', 'active' => true]);

    expect($data)->name->toBe('Klarna')->active->toBeTrue();
});

it('creates SimpleWalletData from array', function () {
    $data = SimpleWalletData::fromArray(['name' => 'mobilepay', 'active' => true]);

    expect($data)->name->toBe('mobilepay')->active->toBeTrue();
});
