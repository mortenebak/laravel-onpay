<?php

namespace Netbums\Onpay\DataObjects;

readonly class DetailedTransactionData
{
    /**
     * @param  TransactionHistoryData[]  $history
     */
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
        public ?string $cardBin,
        public ?string $cardMask,
        public ?int $cardCountry,
        public ?int $expiryMonth,
        public ?int $expiryYear,
        public ?string $ip,
        public ?int $ipCountry,
        public ?string $wallet,
        public ?int $subscriptionNumber,
        public ?string $subscriptionUuid,
        public bool $hasCardholderData,
        public ?CardholderData $cardholderData,
        public bool $testmode,
        public array $history = [],
        public ?int $fee = null,
        public ?array $links = null,
    ) {}

    public static function fromArray(array $response): static
    {
        $data = $response['data'] ?? $response;

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
            cardBin: $data['card_bin'] ?? null,
            cardMask: $data['card_mask'] ?? null,
            cardCountry: $data['card_country'] ?? null,
            expiryMonth: $data['expiry_month'] ?? null,
            expiryYear: $data['expiry_year'] ?? null,
            ip: $data['ip'] ?? null,
            ipCountry: $data['ip_country'] ?? null,
            wallet: $data['wallet'] ?? null,
            subscriptionNumber: $data['subscription_number'] ?? null,
            subscriptionUuid: $data['subscription_uuid'] ?? null,
            hasCardholderData: $data['has_cardholder_data'] ?? false,
            cardholderData: isset($data['cardholder_data']) ? CardholderData::fromArray($data['cardholder_data']) : null,
            testmode: $data['testmode'],
            history: array_map(
                fn (array $item) => TransactionHistoryData::fromArray($item),
                $data['history'] ?? []
            ),
            fee: $data['fee'] ?? null,
            links: $response['links'] ?? $data['links'] ?? null,
        );
    }
}
