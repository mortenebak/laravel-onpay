<?php

use Netbums\Onpay\Support\HmacCalculator;

it('calculates correct HMAC from OnPay docs example', function () {
    $secret = 'e88ebc73104651e3c8ee9af666c19b0626c9ecacd7f8f857e3633e355776baad92e67b7faf9b87744f8c6ce4303978ed65b4165f29534118c882c0fd95f52d0c';

    $calculator = new HmacCalculator($secret);

    $params = [
        'onpay_gatewayid' => '20007895654',
        'onpay_currency' => 'DKK',
        'onpay_amount' => '12000',
        'onpay_reference' => 'AF-847824',
        'onpay_accepturl' => 'https://example.com/accept',
        'unrelated_param' => 'bla bla bla',
    ];

    $hmac = $calculator->calculate($params);

    expect($hmac)->toBe('16586ad0b3446b58df92446296cf821500ac57d8');
});

it('excludes onpay_hmac_sha1 from calculation', function () {
    $secret = 'test-secret';
    $calculator = new HmacCalculator($secret);

    $params = [
        'onpay_amount' => '100',
        'onpay_hmac_sha1' => 'should-be-excluded',
    ];

    $paramsWithout = [
        'onpay_amount' => '100',
    ];

    expect($calculator->calculate($params))->toBe($calculator->calculate($paramsWithout));
});

it('only includes onpay_ prefixed parameters', function () {
    $secret = 'test-secret';
    $calculator = new HmacCalculator($secret);

    $params = [
        'onpay_amount' => '100',
        'other_param' => 'ignored',
        'custom' => 'also-ignored',
    ];

    $onlyOnpay = [
        'onpay_amount' => '100',
    ];

    expect($calculator->calculate($params))->toBe($calculator->calculate($onlyOnpay));
});

it('verifies a valid HMAC', function () {
    $secret = 'e88ebc73104651e3c8ee9af666c19b0626c9ecacd7f8f857e3633e355776baad92e67b7faf9b87744f8c6ce4303978ed65b4165f29534118c882c0fd95f52d0c';
    $calculator = new HmacCalculator($secret);

    $params = [
        'onpay_gatewayid' => '20007895654',
        'onpay_currency' => 'DKK',
        'onpay_amount' => '12000',
        'onpay_reference' => 'AF-847824',
        'onpay_accepturl' => 'https://example.com/accept',
    ];

    expect($calculator->verify($params, '16586ad0b3446b58df92446296cf821500ac57d8'))->toBeTrue();
});

it('rejects an invalid HMAC', function () {
    $secret = 'test-secret';
    $calculator = new HmacCalculator($secret);

    $params = ['onpay_amount' => '100'];

    expect($calculator->verify($params, 'invalid-hmac'))->toBeFalse();
});

it('sorts parameters alphabetically', function () {
    $secret = 'test-secret';
    $calculator = new HmacCalculator($secret);

    $paramsOrdered = [
        'onpay_amount' => '100',
        'onpay_currency' => 'DKK',
        'onpay_reference' => 'REF-1',
    ];

    $paramsUnordered = [
        'onpay_reference' => 'REF-1',
        'onpay_amount' => '100',
        'onpay_currency' => 'DKK',
    ];

    expect($calculator->calculate($paramsOrdered))->toBe($calculator->calculate($paramsUnordered));
});
