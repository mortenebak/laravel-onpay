<?php

namespace Netbums\Onpay\DataObjects;

readonly class SimpleSubscriptionData
{
    public function __construct(
        public string $uuid,
        public int $subscriptionNumber,
        public int $currencyCode,
        public string $orderId,
        public string $status,
        public string $created,
        public bool $threeDSecure,
        public string $acquirer,
        public string $cardType,
        public ?string $wallet,
        public bool $testmode,
        public ?array $links = null,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            uuid: $data['uuid'],
            subscriptionNumber: $data['subscription_number'],
            currencyCode: $data['currency_code'],
            orderId: $data['order_id'],
            status: $data['status'],
            created: $data['created'],
            threeDSecure: $data['3dsecure'],
            acquirer: $data['acquirer'],
            cardType: $data['card_type'],
            wallet: $data['wallet'] ?? null,
            testmode: $data['testmode'],
            links: $data['links'] ?? null,
        );
    }
}
