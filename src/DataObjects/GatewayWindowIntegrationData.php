<?php

namespace Netbums\Onpay\DataObjects;

readonly class GatewayWindowIntegrationData
{
    public function __construct(
        public string $secret,
    ) {}

    public static function fromArray(array $response): static
    {
        $data = $response['data'] ?? $response;

        return new static(
            secret: $data['secret'],
        );
    }
}
