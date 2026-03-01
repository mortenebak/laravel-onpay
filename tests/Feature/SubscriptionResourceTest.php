<?php

use Illuminate\Support\Facades\Http;
use Netbums\Onpay\DataObjects\DetailedSubscriptionData;
use Netbums\Onpay\DataObjects\DetailedTransactionData;
use Netbums\Onpay\DataObjects\SubscriptionAuthorizeData;
use Netbums\Onpay\DataObjects\SubscriptionListData;
use Netbums\Onpay\Enums\SortDirection;
use Netbums\Onpay\Enums\SubscriptionOrderBy;
use Netbums\Onpay\Enums\SubscriptionStatus;
use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('lists subscriptions', function () {
    Http::fake([
        'api.onpay.io/v1/subscription*' => Http::response(Fixtures::subscriptionListResponse()),
    ]);

    $result = Onpay::subscriptions()->all(
        page: 1,
        pageSize: 25,
        orderBy: SubscriptionOrderBy::Created,
        direction: SortDirection::Descending,
    );

    expect($result)->toBeInstanceOf(SubscriptionListData::class);
    expect($result->subscriptions)->toHaveCount(1);
    expect($result->pagination->total)->toBe(200);
});

it('lists subscriptions with status filter', function () {
    Http::fake([
        'api.onpay.io/v1/subscription*' => Http::response(Fixtures::subscriptionListResponse()),
    ]);

    Onpay::subscriptions()->all(status: SubscriptionStatus::Active);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'status=active');
    });
});

it('finds a specific subscription', function () {
    Http::fake([
        'api.onpay.io/v1/subscription/0bb17c90-a80e-11e7-8fc8-b61928e29a9f' => Http::response(Fixtures::detailedSubscription()),
    ]);

    $subscription = Onpay::subscriptions()->find('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');

    expect($subscription)->toBeInstanceOf(DetailedSubscriptionData::class);
    expect($subscription->uuid)->toBe('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');
    expect($subscription->subscriptionNumber)->toBe(123);
    expect($subscription->history)->toHaveCount(1);
    expect($subscription->transactions)->toHaveCount(1);
});

it('creates a transaction from subscription', function () {
    Http::fake([
        'api.onpay.io/v1/subscription/*/authorize' => Http::response(Fixtures::detailedTransaction()),
    ]);

    $result = Onpay::subscriptions()->authorize(
        '0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
        new SubscriptionAuthorizeData(
            amount: 12000,
            orderId: '20171205143025',
        ),
    );

    expect($result)->toBeInstanceOf(DetailedTransactionData::class);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/authorize')
            && $request['data']['amount'] === 12000
            && $request['data']['order_id'] === '20171205143025';
    });
});

it('cancels a subscription', function () {
    $response = Fixtures::detailedSubscription();
    $response['data']['status'] = 'cancelled';

    Http::fake([
        'api.onpay.io/v1/subscription/*/cancel' => Http::response($response),
    ]);

    $result = Onpay::subscriptions()->cancel('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');

    expect($result)->toBeInstanceOf(DetailedSubscriptionData::class);
    expect($result->status)->toBe('cancelled');
});
