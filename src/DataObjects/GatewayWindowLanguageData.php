<?php

namespace Netbums\Onpay\DataObjects;

readonly class GatewayWindowLanguageData
{
    public function __construct(
        public string $locale,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            locale: $data['locale'],
        );
    }
}
