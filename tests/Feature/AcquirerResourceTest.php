<?php

use Illuminate\Support\Facades\Http;
use Netbums\Onpay\DataObjects\DetailedAcquirerData;
use Netbums\Onpay\DataObjects\SimpleAcquirerData;
use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('lists all acquirers', function () {
    Http::fake([
        'api.onpay.io/v1/acquirer' => Http::response(Fixtures::acquirerList()),
    ]);

    $acquirers = Onpay::acquirers()->all();

    expect($acquirers)->toHaveCount(2);
    expect($acquirers[0])->toBeInstanceOf(SimpleAcquirerData::class);
    expect($acquirers[0]->name)->toBe('nets');
    expect($acquirers[0]->active)->toBeTrue();
    expect($acquirers[1]->name)->toBe('clearhaus');
    expect($acquirers[1]->active)->toBeFalse();
});

it('finds a specific acquirer', function () {
    Http::fake([
        'api.onpay.io/v1/acquirer/clearhaus' => Http::response(Fixtures::detailedAcquirer()),
    ]);

    $acquirer = Onpay::acquirers()->find('clearhaus');

    expect($acquirer)->toBeInstanceOf(DetailedAcquirerData::class);
    expect($acquirer->name)->toBe('clearhaus');
    expect($acquirer->active)->toBeTrue();
    expect($acquirer->apiKey)->toBe('3764eca2-a225-44b6-b5cc-c069efaf38ac');
    expect($acquirer->mcc)->toBe('1234');
    expect($acquirer->scaMode)->toBe('all');
});

it('updates an acquirer', function () {
    $response = Fixtures::detailedAcquirer();
    $response['data']['tof'] = '12345678';

    Http::fake([
        'api.onpay.io/v1/acquirer/nets' => Http::response($response),
    ]);

    $result = Onpay::acquirers()->update('nets', [
        'active' => true,
        'tof' => '12345678',
    ]);

    expect($result)->toBeInstanceOf(DetailedAcquirerData::class);

    Http::assertSent(function ($request) {
        return $request->method() === 'PATCH'
            && $request['data']['tof'] === '12345678';
    });
});
