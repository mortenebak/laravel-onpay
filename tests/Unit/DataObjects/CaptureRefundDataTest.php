<?php

use Netbums\Onpay\DataObjects\CaptureData;
use Netbums\Onpay\DataObjects\RefundData;

it('converts CaptureData with amount to array', function () {
    $data = new CaptureData(amount: 12300);

    expect($data->toArray())->toBe([
        'data' => ['amount' => 12300],
    ]);
});

it('converts CaptureData with postActionChargeAmount to array', function () {
    $data = new CaptureData(postActionChargeAmount: 12300);

    expect($data->toArray())->toBe([
        'data' => ['postActionChargeAmount' => 12300],
    ]);
});

it('converts empty CaptureData to array', function () {
    $data = new CaptureData;

    expect($data->toArray())->toBe(['data' => []]);
});

it('converts RefundData with amount to array', function () {
    $data = new RefundData(amount: 10000);

    expect($data->toArray())->toBe([
        'data' => ['amount' => 10000],
    ]);
});

it('converts RefundData with postActionRefundAmount to array', function () {
    $data = new RefundData(postActionRefundAmount: 10000);

    expect($data->toArray())->toBe([
        'data' => ['postActionRefundAmount' => 10000],
    ]);
});
