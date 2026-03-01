<?php

use Illuminate\Support\Facades\Http;
use Netbums\Onpay\DataObjects\CreatePaymentData;
use Netbums\Onpay\DataObjects\PaymentResponseData;
use Netbums\Onpay\Enums\Language;
use Netbums\Onpay\Enums\PaymentMethod;
use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('creates a payment', function () {
    Http::fake([
        'api.onpay.io/v1/payment/create' => Http::response(Fixtures::paymentCreateResponse()),
    ]);

    $response = Onpay::payments()->create(new CreatePaymentData(
        currency: 'DKK',
        amount: 12000,
        reference: 'AF-847824',
        website: 'https://example.com/',
        acceptUrl: 'https://example.com/accept',
        method: PaymentMethod::Card,
        language: Language::English,
    ));

    expect($response)->toBeInstanceOf(PaymentResponseData::class);
    expect($response)
        ->paymentUuid->toBe('dfe8bf50-aaaa-11e7-898d-be9d7bb73511')
        ->amount->toBe(12000)
        ->currencyCode->toBe('208')
        ->language->toBe('en')
        ->method->toBe('card')
        ->paymentWindowUrl->toBe('https://onpay.io/window/v3/dfe8bf50-aaaa-11e7-898d-be9d7bb73511');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.onpay.io/v1/payment/create'
            && $request['currency'] === 'DKK'
            && $request['amount'] === 12000
            && $request['reference'] === 'AF-847824';
    });
});
