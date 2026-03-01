<?php

use Netbums\Onpay\DataObjects\DetailedSubscriptionData;
use Netbums\Onpay\DataObjects\SimpleSubscriptionData;
use Netbums\Onpay\DataObjects\SimpleTransactionData;
use Netbums\Onpay\DataObjects\SubscriptionAuthorizeData;
use Netbums\Onpay\DataObjects\SubscriptionHistoryData;
use Netbums\Onpay\DataObjects\SubscriptionListData;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('creates SimpleSubscriptionData from array', function () {
    $data = SimpleSubscriptionData::fromArray(Fixtures::simpleSubscription());

    expect($data)
        ->uuid->toBe('03c8c78e-a7ac-11e7-9cff-b61928e29a9f')
        ->subscriptionNumber->toBe(123)
        ->currencyCode->toBe(208)
        ->orderId->toBe('1234567891')
        ->status->toBe('active')
        ->threeDSecure->toBeFalse()
        ->acquirer->toBe('nets')
        ->cardType->toBe('visa')
        ->testmode->toBeFalse();
});

it('creates DetailedSubscriptionData from response', function () {
    $data = DetailedSubscriptionData::fromArray(Fixtures::detailedSubscription());

    expect($data)
        ->uuid->toBe('0bb17c90-a80e-11e7-8fc8-b61928e29a9f')
        ->subscriptionNumber->toBe(123)
        ->cardBin->toBe('457199')
        ->expiryMonth->toBe(4)
        ->expiryYear->toBe(2028);

    expect($data->history)->toHaveCount(1);
    expect($data->history[0])->toBeInstanceOf(SubscriptionHistoryData::class);
    expect($data->history[0])->action->toBe('authorize');

    expect($data->transactions)->toHaveCount(1);
    expect($data->transactions[0])->toBeInstanceOf(SimpleTransactionData::class);
});

it('creates SubscriptionListData from response', function () {
    $list = SubscriptionListData::fromArray(Fixtures::subscriptionListResponse());

    expect($list->subscriptions)->toHaveCount(1);
    expect($list->subscriptions[0])->toBeInstanceOf(SimpleSubscriptionData::class);
    expect($list->pagination)->total->toBe(200);
});

it('converts SubscriptionAuthorizeData to array', function () {
    $data = new SubscriptionAuthorizeData(
        amount: 12000,
        orderId: '20171205143025',
        surchargeEnabled: true,
        surchargeVatRate: 2500,
    );

    expect($data->toArray())->toBe([
        'data' => [
            'amount' => 12000,
            'order_id' => '20171205143025',
            'surcharge_enabled' => true,
            'surcharge_vat_rate' => 2500,
        ],
    ]);
});
