<?php

namespace Netbums\Onpay\DataObjects;

readonly class RefundData
{
    public function __construct(
        public ?int $amount = null,
        public ?int $postActionRefundAmount = null,
    ) {}

    public function toArray(): array
    {
        return ['data' => array_filter([
            'amount' => $this->amount,
            'postActionRefundAmount' => $this->postActionRefundAmount,
        ], fn ($value) => $value !== null)];
    }
}
