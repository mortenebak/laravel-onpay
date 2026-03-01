<?php

namespace Netbums\Onpay\DataObjects;

readonly class SubscriptionHistoryData
{
    public function __construct(
        public string $action,
        public string $author,
        public string $dateTime,
        public string $ip,
        public string $resultCode,
        public string $resultText,
        public bool $successful,
        public ?string $uuid = null,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            action: $data['action'],
            author: $data['author'],
            dateTime: $data['date_time'],
            ip: $data['ip'],
            resultCode: (string) $data['result_code'],
            resultText: $data['result_text'],
            successful: $data['successful'],
            uuid: $data['uuid'] ?? null,
        );
    }
}
