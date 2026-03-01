<?php

namespace Netbums\Onpay\DataObjects;

readonly class SimpleTransactionData
{
    public function __construct(
        public string $uuid,
        public int $transactionNumber,
        public int $amount,
        public int $charged,
        public int $refunded,
        public int $currencyCode,
        public string $orderId,
        public string $status,
        public string $created,
        public bool $threeDSecure,
        public string $acquirer,
        public string $cardType,
        public ?string $wallet,
        public bool $testmode,
        public ?bool $hasCardholderData = null,
        public ?array $links = null,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            uuid: $data['uuid'],
            transactionNumber: $data['transaction_number'],
            amount: $data['amount'],
            charged: $data['charged'],
            refunded: $data['refunded'],
            currencyCode: $data['currency_code'],
            orderId: $data['order_id'],
            status: $data['status'],
            created: $data['created'],
            threeDSecure: $data['3dsecure'],
            acquirer: $data['acquirer'],
            cardType: $data['card_type'],
            wallet: $data['wallet'] ?? null,
            testmode: $data['testmode'],
            hasCardholderData: $data['has_cardholder_data'] ?? null,
            links: $data['links'] ?? null,
        );
    }
}
