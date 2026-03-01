<?php

namespace Netbums\Onpay\DataObjects;

readonly class PaymentResponseData
{
    public function __construct(
        public string $paymentUuid,
        public int $amount,
        public string $currencyCode,
        public string $expiration,
        public string $language,
        public ?string $method,
        public string $paymentWindowUrl,
    ) {}

    public static function fromArray(array $response): static
    {
        return new static(
            paymentUuid: $response['data']['payment_uuid'],
            amount: $response['data']['amount'],
            currencyCode: $response['data']['currency_code'],
            expiration: $response['data']['expiration'],
            language: $response['data']['language'],
            method: $response['data']['method'] ?? null,
            paymentWindowUrl: $response['links']['payment_window'],
        );
    }
}
