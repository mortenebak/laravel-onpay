<?php

namespace Netbums\Onpay\DataObjects;

readonly class CartData
{
    /**
     * @param  CartItemData[]  $items
     */
    public function __construct(
        public array $items = [],
        public ?CartShippingData $shipping = null,
        public ?CartHandlingData $handling = null,
        public ?int $discount = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'shipping' => $this->shipping?->toArray(),
            'handling' => $this->handling?->toArray(),
            'discount' => $this->discount,
            'items' => array_map(fn (CartItemData $item) => $item->toArray(), $this->items),
        ], fn ($value) => $value !== null);
    }
}
