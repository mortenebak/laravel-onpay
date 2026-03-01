<?php

namespace Netbums\Onpay\DataObjects;

readonly class CartShippingData
{
    public function __construct(
        public ?int $price = null,
        public ?int $tax = null,
        public ?int $discount = null,
        public ?string $name = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'price' => $this->price,
            'tax' => $this->tax,
            'discount' => $this->discount,
            'name' => $this->name,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): static
    {
        return new static(
            price: $data['price'] ?? null,
            tax: $data['tax'] ?? null,
            discount: $data['discount'] ?? null,
            name: $data['name'] ?? null,
        );
    }
}
