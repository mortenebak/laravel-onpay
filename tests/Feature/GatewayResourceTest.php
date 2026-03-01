<?php

use Illuminate\Support\Facades\Http;
use Netbums\Onpay\DataObjects\GatewayInformationData;
use Netbums\Onpay\DataObjects\GatewayWindowDesignData;
use Netbums\Onpay\DataObjects\GatewayWindowIntegrationData;
use Netbums\Onpay\DataObjects\GatewayWindowLanguageData;
use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('fetches gateway information', function () {
    Http::fake([
        'api.onpay.io/v1/gateway/information' => Http::response(Fixtures::gatewayInformation()),
    ]);

    $info = Onpay::gateway()->information();

    expect($info)->toBeInstanceOf(GatewayInformationData::class);
    expect($info->gatewayId)->toBe('12345678910111213141516');
    expect($info->activeMethods)->toContain('card', 'mobilepay');
});

it('fetches window integration settings', function () {
    Http::fake([
        'api.onpay.io/v1/gateway/window/v3/integration/*' => Http::response(Fixtures::gatewayWindowIntegration()),
    ]);

    $integration = Onpay::gateway()->windowIntegration();

    expect($integration)->toBeInstanceOf(GatewayWindowIntegrationData::class);
    expect($integration->secret)->toStartWith('db78b4dac653');
});

it('fetches window designs', function () {
    Http::fake([
        'api.onpay.io/v1/gateway/window/v3/design/*' => Http::response(Fixtures::gatewayWindowDesigns()),
    ]);

    $designs = Onpay::gateway()->windowDesigns();

    expect($designs)->toHaveCount(2);
    expect($designs[0])->toBeInstanceOf(GatewayWindowDesignData::class);
    expect($designs[0]->name)->toBe('Danish window');
    expect($designs[1]->name)->toBe('International window');
});

it('fetches window languages', function () {
    Http::fake([
        'api.onpay.io/v1/gateway/window/v3/language/*' => Http::response(Fixtures::gatewayWindowLanguages()),
    ]);

    $languages = Onpay::gateway()->windowLanguages();

    expect($languages)->toHaveCount(3);
    expect($languages[0])->toBeInstanceOf(GatewayWindowLanguageData::class);
    expect($languages[0]->locale)->toBe('da');
});
