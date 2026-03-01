<?php

namespace Netbums\Onpay\DataObjects;

readonly class SimpleWalletData
{
    public function __construct(
        public string $name,
        public bool $active,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'],
            active: $data['active'],
        );
    }
}
