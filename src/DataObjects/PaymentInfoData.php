<?php

namespace Netbums\Onpay\DataObjects;

use Netbums\Onpay\Enums\DeliveryTimeFrame;
use Netbums\Onpay\Enums\ShippingMethod;

readonly class PaymentInfoData
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?AccountInfoData $account = null,
        public ?AddressData $billing = null,
        public ?AddressData $shipping = null,
        public ?PhoneData $phone = null,
        public ?string $addressIdenticalShipping = null,
        public ?string $deliveryEmail = null,
        public ?DeliveryTimeFrame $deliveryTimeFrame = null,
        public ?int $giftCardAmount = null,
        public ?int $giftCardCount = null,
        public ?string $preorder = null,
        public ?string $preorderDate = null,
        public ?string $reorder = null,
        public ?ShippingMethod $shippingMethod = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'account' => $this->account?->toArray(),
            'billing' => $this->billing?->toArray('address'),
            'shipping' => $this->shipping?->toArray('address'),
            'phone' => $this->phone?->toArray(),
            'address_identical_shipping' => $this->addressIdenticalShipping,
            'delivery_email' => $this->deliveryEmail,
            'delivery_time_frame' => $this->deliveryTimeFrame?->value,
            'gift_card_amount' => $this->giftCardAmount,
            'gift_card_count' => $this->giftCardCount,
            'preorder' => $this->preorder,
            'preorder_date' => $this->preorderDate,
            'reorder' => $this->reorder,
            'shipping_method' => $this->shippingMethod?->value,
        ], fn ($value) => $value !== null);
    }
}
