<?php

namespace Netbums\Onpay\DataObjects;

readonly class AccountInfoData
{
    public function __construct(
        public ?string $id = null,
        public ?string $dateCreated = null,
        public ?string $dateChange = null,
        public ?string $datePasswordChange = null,
        public ?int $purchases = null,
        public ?int $attempts = null,
        public ?string $shippingFirstUseDate = null,
        public ?string $shippingIdenticalName = null,
        public ?string $suspicious = null,
        public ?int $attemptsDay = null,
        public ?int $attemptsYear = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'date_created' => $this->dateCreated,
            'date_change' => $this->dateChange,
            'date_password_change' => $this->datePasswordChange,
            'purchases' => $this->purchases,
            'attempts' => $this->attempts,
            'shipping_first_use_date' => $this->shippingFirstUseDate,
            'shipping_identical_name' => $this->shippingIdenticalName,
            'suspicious' => $this->suspicious,
            'attempts_day' => $this->attemptsDay,
            'attempts_year' => $this->attemptsYear,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'] ?? null,
            dateCreated: $data['date_created'] ?? null,
            dateChange: $data['date_change'] ?? $data['date_change_date'] ?? null,
            datePasswordChange: $data['date_password_change'] ?? null,
            purchases: $data['purchases'] ?? null,
            attempts: $data['attempts'] ?? null,
            shippingFirstUseDate: $data['shipping_first_use_date'] ?? null,
            shippingIdenticalName: $data['shipping_identical_name'] ?? null,
            suspicious: $data['suspicious'] ?? null,
            attemptsDay: $data['attempts_day'] ?? null,
            attemptsYear: $data['attempts_year'] ?? null,
        );
    }
}
