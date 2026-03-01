<?php

namespace Netbums\Onpay\DataObjects;

use Netbums\Onpay\Enums\Language;
use Netbums\Onpay\Enums\PaymentMethod;
use Netbums\Onpay\Enums\PaymentType;

readonly class CreatePaymentData
{
    public function __construct(
        public string $currency,
        public int $amount,
        public string $reference,
        public string $website,
        public ?string $acceptUrl = null,
        public ?PaymentType $type = null,
        public ?PaymentMethod $method = null,
        public ?bool $surchargeEnabled = null,
        public ?int $surchargeVatRate = null,
        public ?Language $language = null,
        public ?string $declineUrl = null,
        public ?string $callbackUrl = null,
        public ?string $design = null,
        public ?int $expiration = null,
        public ?bool $createTransaction = null,
        public ?bool $testmode = null,
        public ?PaymentInfoData $info = null,
        public ?CartData $cart = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'currency' => $this->currency,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'website' => $this->website,
            'accepturl' => $this->acceptUrl,
            'type' => $this->type?->value,
            'method' => $this->method?->value,
            'surcharge_enabled' => $this->surchargeEnabled,
            'surcharge_vat_rate' => $this->surchargeVatRate,
            'language' => $this->language?->value,
            'declineurl' => $this->declineUrl,
            'callbackurl' => $this->callbackUrl,
            'design' => $this->design,
            'expiration' => $this->expiration,
            'create_transaction' => $this->createTransaction,
            'testmode' => $this->testmode ? 1 : null,
            'info' => $this->info?->toArray(),
            'cart' => $this->cart?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
