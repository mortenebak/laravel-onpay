<?php

use Netbums\Onpay\DataObjects\GatewayInformationData;
use Netbums\Onpay\DataObjects\GatewayWindowDesignData;
use Netbums\Onpay\DataObjects\GatewayWindowIntegrationData;
use Netbums\Onpay\DataObjects\GatewayWindowLanguageData;
use Netbums\Onpay\Tests\Fixtures\Fixtures;

it('creates GatewayInformationData from response', function () {
    $data = GatewayInformationData::fromArray(Fixtures::gatewayInformation());

    expect($data)
        ->gatewayId->toBe('12345678910111213141516')
        ->activeMethods->toBe(['card', 'mobilepay', 'viabill']);
});

it('creates GatewayWindowIntegrationData from response', function () {
    $data = GatewayWindowIntegrationData::fromArray(Fixtures::gatewayWindowIntegration());

    expect($data->secret)->toStartWith('db78b4dac653b65a');
});

it('creates GatewayWindowDesignData from array', function () {
    $data = GatewayWindowDesignData::fromArray(['name' => 'Danish window']);

    expect($data->name)->toBe('Danish window');
});

it('creates GatewayWindowLanguageData from array', function () {
    $data = GatewayWindowLanguageData::fromArray(['locale' => 'da']);

    expect($data->locale)->toBe('da');
});
