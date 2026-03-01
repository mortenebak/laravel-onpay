<?php

namespace Netbums\Onpay\DataObjects;

readonly class DetailedAcquirerData
{
    public function __construct(
        public string $name,
        public bool $active,
        public ?array $links = null,
        public ?string $apiKey = null,
        public ?string $mastercardBin = null,
        public ?string $mcc = null,
        public ?string $merchantId = null,
        public ?string $scaMode = null,
        public ?string $visaBin = null,
        public ?string $tof = null,
        public ?string $customerNumber = null,
        public ?string $amexId = null,
        public ?string $internationalId = null,
        public ?array $exemptions = null,
    ) {}

    public static function fromArray(array $response): static
    {
        $data = $response['data'] ?? $response;

        return new static(
            name: $data['name'],
            active: $data['active'],
            links: $response['links'] ?? $data['links'] ?? null,
            apiKey: $data['api_key'] ?? null,
            mastercardBin: $data['mastercard_bin'] ?? null,
            mcc: $data['mcc'] ?? null,
            merchantId: $data['merchant_id'] ?? null,
            scaMode: $data['sca_mode'] ?? null,
            visaBin: $data['visa_bin'] ?? null,
            tof: $data['tof'] ?? null,
            customerNumber: $data['customer_number'] ?? null,
            amexId: $data['amex_id'] ?? null,
            internationalId: $data['international_id'] ?? null,
            exemptions: $data['exemptions'] ?? $data['exemption'] ?? null,
        );
    }
}
