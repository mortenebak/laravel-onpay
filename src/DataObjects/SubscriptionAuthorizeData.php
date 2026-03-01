<?php

namespace Netbums\Onpay\DataObjects;

readonly class SubscriptionAuthorizeData
{
    public function __construct(
        public int $amount,
        public string $orderId,
        public ?bool $surchargeEnabled = null,
        public ?int $surchargeVatRate = null,
    ) {}

    public function toArray(): array
    {
        return ['data' => array_filter([
            'amount' => $this->amount,
            'order_id' => $this->orderId,
            'surcharge_enabled' => $this->surchargeEnabled,
            'surcharge_vat_rate' => $this->surchargeVatRate,
        ], fn ($value) => $value !== null)];
    }
}
