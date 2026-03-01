<?php

use Netbums\Onpay\DataObjects\AccountInfoData;
use Netbums\Onpay\DataObjects\AddressData;
use Netbums\Onpay\DataObjects\CartData;
use Netbums\Onpay\DataObjects\CartHandlingData;
use Netbums\Onpay\DataObjects\CartItemData;
use Netbums\Onpay\DataObjects\CartShippingData;
use Netbums\Onpay\DataObjects\CreatePaymentData;
use Netbums\Onpay\DataObjects\PaymentInfoData;
use Netbums\Onpay\DataObjects\PhoneData;
use Netbums\Onpay\Enums\CartItemType;
use Netbums\Onpay\Enums\DeliveryTimeFrame;
use Netbums\Onpay\Enums\Language;
use Netbums\Onpay\Enums\PaymentMethod;
use Netbums\Onpay\Enums\PaymentType;
use Netbums\Onpay\Enums\ShippingMethod;

it('converts a minimal payment to array', function () {
    $payment = new CreatePaymentData(
        currency: 'DKK',
        amount: 12000,
        reference: 'AF-847824',
        website: 'https://example.com/',
    );

    $array = $payment->toArray();

    expect($array)->toBe([
        'currency' => 'DKK',
        'amount' => 12000,
        'reference' => 'AF-847824',
        'website' => 'https://example.com/',
    ]);
});

it('converts a full payment to array', function () {
    $payment = new CreatePaymentData(
        currency: 'DKK',
        amount: 11200,
        reference: 'AF-847824',
        website: 'https://example.com/',
        acceptUrl: 'https://example.com/accept',
        type: PaymentType::Transaction,
        method: PaymentMethod::Card,
        surchargeEnabled: true,
        surchargeVatRate: 2500,
        language: Language::English,
        declineUrl: 'https://example.com/decline',
        callbackUrl: 'https://example.com/callback',
        design: 'window1',
        expiration: 4200,
        testmode: true,
    );

    $array = $payment->toArray();

    expect($array)
        ->toHaveKey('currency', 'DKK')
        ->toHaveKey('amount', 11200)
        ->toHaveKey('reference', 'AF-847824')
        ->toHaveKey('type', 'transaction')
        ->toHaveKey('method', 'card')
        ->toHaveKey('surcharge_enabled', true)
        ->toHaveKey('surcharge_vat_rate', 2500)
        ->toHaveKey('language', 'en')
        ->toHaveKey('declineurl', 'https://example.com/decline')
        ->toHaveKey('callbackurl', 'https://example.com/callback')
        ->toHaveKey('design', 'window1')
        ->toHaveKey('expiration', 4200)
        ->toHaveKey('testmode', 1);
});

it('includes payment info in array', function () {
    $payment = new CreatePaymentData(
        currency: 'DKK',
        amount: 12000,
        reference: 'REF-123',
        website: 'https://example.com/',
        info: new PaymentInfoData(
            name: 'Emil Pedersen',
            email: 'emil@example.org',
            account: new AccountInfoData(
                id: 'ABC-455454',
                purchases: 3,
            ),
            billing: new AddressData(
                city: 'Skanderborg',
                country: '208',
                line1: 'Højvangen 4',
                postalCode: '8660',
            ),
            shipping: new AddressData(
                city: 'Skanderborg',
                country: '208',
                line1: 'Højvangen 4',
                postalCode: '8660',
            ),
            phone: new PhoneData(
                mobileCc: '45',
                mobileNumber: '37123456',
            ),
            deliveryTimeFrame: DeliveryTimeFrame::Overnight,
            shippingMethod: ShippingMethod::ShipToBillingAddress,
        ),
    );

    $array = $payment->toArray();

    expect($array['info'])
        ->toHaveKey('name', 'Emil Pedersen')
        ->toHaveKey('email', 'emil@example.org')
        ->toHaveKey('account')
        ->toHaveKey('billing')
        ->toHaveKey('shipping')
        ->toHaveKey('phone')
        ->toHaveKey('delivery_time_frame', '03')
        ->toHaveKey('shipping_method', '01');

    expect($array['info']['account'])->toHaveKey('id', 'ABC-455454');
    expect($array['info']['phone'])->toHaveKey('mobile_cc', '45');
});

it('includes cart data in array', function () {
    $payment = new CreatePaymentData(
        currency: 'DKK',
        amount: 12000,
        reference: 'REF-456',
        website: 'https://example.com/',
        cart: new CartData(
            items: [
                new CartItemData(
                    name: 't-shirt',
                    price: 1200,
                    tax: 300,
                    quantity: 2,
                    sku: 'AB47871',
                    type: CartItemType::Physical,
                ),
            ],
            shipping: new CartShippingData(price: 4000, tax: 200, name: 'Standard shipping'),
            handling: new CartHandlingData(price: 0, tax: 0, name: 'Handling'),
            discount: 400,
        ),
    );

    $array = $payment->toArray();

    expect($array['cart'])
        ->toHaveKey('shipping')
        ->toHaveKey('handling')
        ->toHaveKey('discount', 400)
        ->toHaveKey('items');

    expect($array['cart']['items'])->toHaveCount(1);
    expect($array['cart']['items'][0])
        ->toHaveKey('name', 't-shirt')
        ->toHaveKey('type', 'physical');
});
