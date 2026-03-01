<?php

namespace Netbums\Onpay\DataObjects;

readonly class CaptureData
{
    public function __construct(
        public ?int $amount = null,
        public ?int $postActionChargeAmount = null,
    ) {}

    public function toArray(): array
    {
        return ['data' => array_filter([
            'amount' => $this->amount,
            'postActionChargeAmount' => $this->postActionChargeAmount,
        ], fn ($value) => $value !== null)];
    }
}
