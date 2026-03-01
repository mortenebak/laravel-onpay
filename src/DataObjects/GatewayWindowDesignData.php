<?php

namespace Netbums\Onpay\DataObjects;

readonly class GatewayWindowDesignData
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'],
        );
    }
}
