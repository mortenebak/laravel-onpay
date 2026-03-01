<?php

namespace Netbums\Onpay\DataObjects;

use Netbums\Onpay\Enums\CartItemType;

readonly class CartItemData
{
    public function __construct(
        public string $name,
        public int $price,
        public int $tax,
        public int $quantity,
        public ?string $description = null,
        public ?string $sku = null,
        public ?string $quantityUnit = null,
        public ?string $globalTradeItemNumber = null,
        public ?CartItemType $type = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'price' => $this->price,
            'tax' => $this->tax,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'sku' => $this->sku,
            'quantity_unit' => $this->quantityUnit,
            'global_trade_item_number' => $this->globalTradeItemNumber,
            'type' => $this->type?->value,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'],
            price: $data['price'],
            tax: $data['tax'],
            quantity: $data['quantity'],
            description: $data['description'] ?? null,
            sku: $data['sku'] ?? null,
            quantityUnit: $data['quantity_unit'] ?? null,
            globalTradeItemNumber: $data['global_trade_item_number'] ?? null,
            type: isset($data['type']) ? CartItemType::from($data['type']) : null,
        );
    }
}
