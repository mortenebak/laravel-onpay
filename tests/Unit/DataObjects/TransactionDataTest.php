<?php

use Netbums\Onpay\DataObjects\DetailedTransactionData;
use Netbums\Onpay\DataObjects\SimpleTransactionData;
use Netbums\Onpay\DataObjects\TransactionHistoryData;
use Netbums\Onpay\DataObjects\TransactionListData;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('creates SimpleTransactionData from array', function () {
    $data = SimpleTransactionData::fromArray(Fixtures::simpleTransaction());

    expect($data)
        ->uuid->toBe('0bb17c90-a80e-11e7-8fc8-b61928e29a9f')
        ->transactionNumber->toBe(1234)
        ->amount->toBe(12300)
        ->charged->toBe(0)
        ->refunded->toBe(0)
        ->currencyCode->toBe(208)
        ->orderId->toBe('1234567890')
        ->status->toBe('active')
        ->threeDSecure->toBeFalse()
        ->acquirer->toBe('nets')
        ->cardType->toBe('visa')
        ->wallet->toBe('mobilepay')
        ->testmode->toBeFalse();
});

it('creates DetailedTransactionData from response', function () {
    $data = DetailedTransactionData::fromArray(Fixtures::detailedTransaction());

    expect($data)
        ->uuid->toBe('0bb17c90-a80e-11e7-8fc8-b61928e29a9f')
        ->transactionNumber->toBe(1234)
        ->amount->toBe(12300)
        ->cardBin->toBe('457199')
        ->cardMask->toBe('457199XXXXXX1234')
        ->cardCountry->toBe(208)
        ->expiryMonth->toBe(4)
        ->expiryYear->toBe(2028)
        ->ip->toBe('127.0.0.1')
        ->subscriptionNumber->toBe(321)
        ->subscriptionUuid->toBe('03e8162a-a7ac-11e7-9d00-b61928e29a9f')
        ->hasCardholderData->toBeTrue()
        ->cardholderData->not->toBeNull();

    expect($data->cardholderData)
        ->firstName->toBe('First name')
        ->lastName->toBe('Last name')
        ->email->toBe('email@onpay.io');

    expect($data->cardholderData->deliveryAddress)
        ->firstName->toBe('Del First')
        ->city->toBe('Del City');

    expect($data->history)->toHaveCount(1);
    expect($data->history[0])->toBeInstanceOf(TransactionHistoryData::class);
    expect($data->history[0])
        ->action->toBe('authorize')
        ->amount->toBe(12300)
        ->successful->toBeTrue();
});

it('creates TransactionListData from response', function () {
    $list = TransactionListData::fromArray(Fixtures::transactionListResponse());

    expect($list->transactions)->toHaveCount(1);
    expect($list->transactions[0])->toBeInstanceOf(SimpleTransactionData::class);
    expect($list->pagination)
        ->total->toBe(200)
        ->count->toBe(10)
        ->perPage->toBe(10)
        ->currentPage->toBe(1)
        ->totalPages->toBe(20);
});
