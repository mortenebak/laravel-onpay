<?php

namespace Netbums\Onpay\DataObjects;

readonly class AddressData
{
    public function __construct(
        public ?string $city = null,
        public ?string $country = null,
        public ?string $line1 = null,
        public ?string $line2 = null,
        public ?string $line3 = null,
        public ?string $postalCode = null,
        public ?string $state = null,
    ) {}

    public function toArray(string $prefix = 'address'): array
    {
        return array_filter([
            "{$prefix}_city" => $this->city,
            "{$prefix}_country" => $this->country,
            "{$prefix}_line1" => $this->line1,
            "{$prefix}_line2" => $this->line2,
            "{$prefix}_line3" => $this->line3,
            "{$prefix}_postal_code" => $this->postalCode,
            "{$prefix}_state" => $this->state,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data, string $prefix = 'address'): static
    {
        return new static(
            city: $data["{$prefix}_city"] ?? null,
            country: $data["{$prefix}_country"] ?? null,
            line1: $data["{$prefix}_line1"] ?? null,
            line2: $data["{$prefix}_line2"] ?? null,
            line3: $data["{$prefix}_line3"] ?? null,
            postalCode: $data["{$prefix}_postal_code"] ?? null,
            state: $data["{$prefix}_state"] ?? null,
        );
    }
}
