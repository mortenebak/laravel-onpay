<?php

namespace Netbums\Onpay\Enums;

enum Currency: string
{
    case AUD = 'AUD';
    case CAD = 'CAD';
    case CNY = 'CNY';
    case CZK = 'CZK';
    case DKK = 'DKK';
    case HUF = 'HUF';
    case ISK = 'ISK';
    case INR = 'INR';
    case JPY = 'JPY';
    case NZD = 'NZD';
    case NOK = 'NOK';
    case RUB = 'RUB';
    case SGD = 'SGD';
    case ZAR = 'ZAR';
    case SZL = 'SZL';
    case SEK = 'SEK';
    case CHF = 'CHF';
    case EGP = 'EGP';
    case GBP = 'GBP';
    case USD = 'USD';
    case EUR = 'EUR';
    case UAH = 'UAH';
    case PLN = 'PLN';
    case BRL = 'BRL';

    public function numericCode(): int
    {
        return match ($this) {
            self::AUD => 36,
            self::CAD => 124,
            self::CNY => 156,
            self::CZK => 203,
            self::DKK => 208,
            self::HUF => 348,
            self::ISK => 352,
            self::INR => 356,
            self::JPY => 392,
            self::NZD => 554,
            self::NOK => 578,
            self::RUB => 643,
            self::SGD => 702,
            self::ZAR => 710,
            self::SZL => 748,
            self::SEK => 752,
            self::CHF => 756,
            self::EGP => 818,
            self::GBP => 826,
            self::USD => 840,
            self::EUR => 978,
            self::UAH => 980,
            self::PLN => 985,
            self::BRL => 986,
        };
    }

    public function exponent(): int
    {
        return match ($this) {
            self::ISK, self::JPY => 0,
            default => 2,
        };
    }
}
