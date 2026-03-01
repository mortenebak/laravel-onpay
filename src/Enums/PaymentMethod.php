<?php

namespace Netbums\Onpay\Enums;

enum PaymentMethod: string
{
    case Anyday = 'anyday';
    case ApplePay = 'applepay';
    case Card = 'card';
    case GooglePay = 'googlepay';
    case Klarna = 'klarna';
    case MobilePay = 'mobilepay';
    case Swish = 'swish';
    case ViaBill = 'viabill';
    case Vipps = 'vipps';

    public function supportsSubscriptions(): bool
    {
        return match ($this) {
            self::ApplePay, self::Card, self::GooglePay => true,
            default => false,
        };
    }
}
