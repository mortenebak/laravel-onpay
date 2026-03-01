<?php

namespace Netbums\Onpay\DataObjects;

readonly class TransactionEventData
{
    public function __construct(
        public string $uuid,
        public string $transaction,
        public string $dateTime,
        public string $action,
        public bool $successful,
        public ?int $amount,
        public string $resultCode,
        public string $resultText,
        public string $author,
        public string $ip,
        public ?array $links = null,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            uuid: $data['uuid'],
            transaction: $data['transaction'],
            dateTime: $data['date_time'],
            action: $data['action'],
            successful: $data['successful'],
            amount: $data['amount'] ?? null,
            resultCode: (string) $data['result_code'],
            resultText: $data['result_text'],
            author: $data['author'],
            ip: $data['ip'],
            links: $data['links'] ?? null,
        );
    }
}
