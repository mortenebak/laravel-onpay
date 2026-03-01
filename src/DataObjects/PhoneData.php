<?php

namespace Netbums\Onpay\DataObjects;

readonly class PhoneData
{
    public function __construct(
        public ?string $homeCc = null,
        public ?string $homeNumber = null,
        public ?string $mobileCc = null,
        public ?string $mobileNumber = null,
        public ?string $workCc = null,
        public ?string $workNumber = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'home_cc' => $this->homeCc,
            'home_number' => $this->homeNumber,
            'mobile_cc' => $this->mobileCc,
            'mobile_number' => $this->mobileNumber,
            'work_cc' => $this->workCc,
            'work_number' => $this->workNumber,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): static
    {
        return new static(
            homeCc: $data['home_cc'] ?? null,
            homeNumber: $data['home_number'] ?? null,
            mobileCc: $data['mobile_cc'] ?? null,
            mobileNumber: $data['mobile_number'] ?? null,
            workCc: $data['work_cc'] ?? null,
            workNumber: $data['work_number'] ?? null,
        );
    }
}
