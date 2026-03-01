<?php

namespace Netbums\Onpay\DataObjects;

readonly class CartHandlingData
{
    public function __construct(
        public ?int $price = null,
        public ?int $tax = null,
        public ?string $name = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'price' => $this->price,
            'tax' => $this->tax,
            'name' => $this->name,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): static
    {
        return new static(
            price: $data['price'] ?? null,
            tax: $data['tax'] ?? null,
            name: $data['name'] ?? null,
        );
    }
}
