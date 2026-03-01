<?php

namespace Netbums\Onpay\DataObjects;

readonly class DeliveryAddressData
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $attention = null,
        public ?string $company = null,
        public ?string $address1 = null,
        public ?string $address2 = null,
        public ?string $postalCode = null,
        public ?string $city = null,
        public ?int $country = null,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            attention: $data['attention'] ?? null,
            company: $data['company'] ?? null,
            address1: $data['address1'] ?? null,
            address2: $data['address2'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            city: $data['city'] ?? null,
            country: $data['country'] ?? null,
        );
    }
}
