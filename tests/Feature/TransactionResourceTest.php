<?php

use Illuminate\Support\Facades\Http;
use Netbums\Onpay\DataObjects\CaptureData;
use Netbums\Onpay\DataObjects\DetailedTransactionData;
use Netbums\Onpay\DataObjects\RefundData;
use Netbums\Onpay\DataObjects\TransactionEventData;
use Netbums\Onpay\DataObjects\TransactionEventListData;
use Netbums\Onpay\DataObjects\TransactionListData;
use Netbums\Onpay\Enums\SortDirection;
use Netbums\Onpay\Enums\TransactionOrderBy;
use Netbums\Onpay\Enums\TransactionStatus;
use Netbums\Onpay\Exceptions\OnpayApiException;
use Netbums\Onpay\Exceptions\OnpayAuthenticationException;
use Netbums\Onpay\Exceptions\OnpayValidationException;
use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('lists transactions', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*' => Http::response(Fixtures::transactionListResponse()),
    ]);

    $result = Onpay::transactions()->all(
        page: 1,
        pageSize: 10,
        orderBy: TransactionOrderBy::Created,
        direction: SortDirection::Descending,
    );

    expect($result)->toBeInstanceOf(TransactionListData::class);
    expect($result->transactions)->toHaveCount(1);
    expect($result->pagination->total)->toBe(200);
});

it('lists transactions with status filter', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*' => Http::response(Fixtures::transactionListResponse()),
    ]);

    $result = Onpay::transactions()->all(status: TransactionStatus::Active);

    expect($result)->toBeInstanceOf(TransactionListData::class);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'status=active');
    });
});

it('lists transactions with date filters', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*' => Http::response(Fixtures::transactionListResponse()),
    ]);

    Onpay::transactions()->all(
        dateAfter: '2024-10-27',
        dateBefore: '2024-11-17',
    );

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'date_after=2024-10-27')
            && str_contains($request->url(), 'date_before=2024-11-17');
    });
});

it('fetches transaction events', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/events/*' => Http::response(Fixtures::transactionEventListResponse()),
    ]);

    $result = Onpay::transactions()->events();

    expect($result)->toBeInstanceOf(TransactionEventListData::class);
    expect($result->events)->toHaveCount(1);
    expect($result->events[0])->toBeInstanceOf(TransactionEventData::class);
    expect($result->events[0]->action)->toBe('authorize');
    expect($result->nextCursor)->not->toBeNull();
});

it('finds a specific transaction by uuid', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/0bb17c90-a80e-11e7-8fc8-b61928e29a9f' => Http::response(Fixtures::detailedTransaction()),
    ]);

    $transaction = Onpay::transactions()->find('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');

    expect($transaction)->toBeInstanceOf(DetailedTransactionData::class);
    expect($transaction->uuid)->toBe('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');
    expect($transaction->amount)->toBe(12300);
    expect($transaction->cardBin)->toBe('457199');
    expect($transaction->history)->toHaveCount(1);
});

it('captures a transaction with specific amount', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*/capture' => Http::response(Fixtures::capturedTransaction()),
    ]);

    $result = Onpay::transactions()->capture(
        '0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
        new CaptureData(amount: 12300),
    );

    expect($result)->toBeInstanceOf(DetailedTransactionData::class);
    expect($result->charged)->toBe(12300);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/capture')
            && $request['data']['amount'] === 12300;
    });
});

it('captures a transaction without specific amount', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*/capture' => Http::response(Fixtures::capturedTransaction()),
    ]);

    $result = Onpay::transactions()->capture('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');

    expect($result->charged)->toBe(12300);
});

it('refunds a transaction', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*/refund' => Http::response(Fixtures::refundedTransaction()),
    ]);

    $result = Onpay::transactions()->refund(
        '0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
        new RefundData(amount: 10000),
    );

    expect($result)->toBeInstanceOf(DetailedTransactionData::class);
    expect($result->refunded)->toBe(10000);
});

it('cancels a transaction', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*/cancel' => Http::response(Fixtures::cancelledTransaction()),
    ]);

    $result = Onpay::transactions()->cancel('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');

    expect($result)->toBeInstanceOf(DetailedTransactionData::class);
    expect($result->status)->toBe('cancelled');
});

it('throws OnpayAuthenticationException on 401', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*' => Http::response(['message' => 'Unauthenticated'], 401),
    ]);

    Onpay::transactions()->find('test-uuid');
})->throws(OnpayAuthenticationException::class);

it('throws OnpayValidationException on 422', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*/capture' => Http::response(Fixtures::errorResponse(), 422),
    ]);

    Onpay::transactions()->capture('test-uuid', new CaptureData(amount: 999));
})->throws(OnpayValidationException::class);

it('throws OnpayApiException on 404', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*' => Http::response(['errors' => [['message' => 'Not found']]], 404),
    ]);

    Onpay::transactions()->find('nonexistent-uuid');
})->throws(OnpayApiException::class);

it('throws OnpayApiException on 500', function () {
    Http::fake([
        'api.onpay.io/v1/transaction/*' => Http::response(['errors' => [['message' => 'Internal error']]], 500),
    ]);

    Onpay::transactions()->find('test-uuid');
})->throws(OnpayApiException::class);
