<?php

namespace Netbums\Onpay\DataObjects;

readonly class DetailedSubscriptionData
{
    /**
     * @param  SubscriptionHistoryData[]  $history
     * @param  SimpleTransactionData[]  $transactions
     */
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
        public ?string $cardBin,
        public ?string $cardMask,
        public ?int $cardCountry,
        public ?int $expiryMonth,
        public ?int $expiryYear,
        public ?string $ip,
        public ?int $ipCountry,
        public ?string $wallet,
        public bool $testmode,
        public array $history = [],
        public array $transactions = [],
        public ?array $links = null,
    ) {}

    public static function fromArray(array $response): static
    {
        $data = $response['data'] ?? $response;

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
            cardBin: $data['card_bin'] ?? null,
            cardMask: $data['card_mask'] ?? null,
            cardCountry: $data['card_country'] ?? null,
            expiryMonth: $data['expiry_month'] ?? null,
            expiryYear: $data['expiry_year'] ?? null,
            ip: $data['ip'] ?? null,
            ipCountry: $data['ip_country'] ?? null,
            wallet: $data['wallet'] ?? null,
            testmode: $data['testmode'],
            history: array_map(
                fn (array $item) => SubscriptionHistoryData::fromArray($item),
                $data['history'] ?? []
            ),
            transactions: array_map(
                fn (array $item) => SimpleTransactionData::fromArray($item),
                $data['transactions'] ?? []
            ),
            links: $response['links'] ?? $data['links'] ?? null,
        );
    }
}
