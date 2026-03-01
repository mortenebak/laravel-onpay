<?php

namespace Netbums\Onpay\DataObjects;

readonly class SimpleAcquirerData
{
    public function __construct(
        public string $name,
        public bool $active,
        public ?array $links = null,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'],
            active: $data['active'],
            links: $data['links'] ?? null,
        );
    }
}
