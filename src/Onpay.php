<?php

namespace Netbums\Onpay;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Netbums\Onpay\Exceptions\InvalidConfigException;
use Netbums\Onpay\Resources\AcquirerResource;
use Netbums\Onpay\Resources\GatewayResource;
use Netbums\Onpay\Resources\PaymentResource;
use Netbums\Onpay\Resources\ProviderResource;
use Netbums\Onpay\Resources\SubscriptionResource;
use Netbums\Onpay\Resources\TransactionResource;
use Netbums\Onpay\Resources\WalletResource;
use Netbums\Onpay\Support\HmacCalculator;

class Onpay
{
    protected PendingRequest $client;

    protected string $baseUrl;

    protected string $gatewayId;

    protected string $secret;

    public function __construct(
        ?string $apiToken = null,
        ?string $baseUrl = null,
        ?string $gatewayId = null,
        ?string $secret = null,
    ) {
        $apiToken ??= config('onpay.api_token');
        $this->baseUrl = $baseUrl ?? config('onpay.base_url', 'https://api.onpay.io');
        $this->gatewayId = $gatewayId ?? config('onpay.gateway_id', '');
        $this->secret = $secret ?? config('onpay.secret', '');

        if (! $apiToken) {
            throw new InvalidConfigException(
                'Missing OnPay API token. Set ONPAY_API_TOKEN in your .env file.'
            );
        }

        $this->client = Http::baseUrl($this->baseUrl)
            ->withToken($apiToken)
            ->acceptJson()
            ->contentType('application/json');
    }

    public function payments(): PaymentResource
    {
        return new PaymentResource(client: $this->client);
    }

    public function transactions(): TransactionResource
    {
        return new TransactionResource(client: $this->client);
    }

    public function subscriptions(): SubscriptionResource
    {
        return new SubscriptionResource(client: $this->client);
    }

    public function gateway(): GatewayResource
    {
        return new GatewayResource(client: $this->client);
    }

    public function acquirers(): AcquirerResource
    {
        return new AcquirerResource(client: $this->client);
    }

    public function providers(): ProviderResource
    {
        return new ProviderResource(client: $this->client);
    }

    public function wallets(): WalletResource
    {
        return new WalletResource(client: $this->client);
    }

    public function hmac(): HmacCalculator
    {
        return new HmacCalculator($this->secret);
    }

    public function gatewayId(): string
    {
        return $this->gatewayId;
    }

    public function windowUrl(): string
    {
        return config('onpay.window_url', 'https://onpay.io/window/v3/');
    }
}
