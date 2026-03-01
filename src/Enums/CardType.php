<?php

namespace Netbums\Onpay\Enums;

enum CardType: string
{
    case Dankort = 'dankort';
    case Visa = 'visa';
    case Amex = 'amex';
    case Maestro = 'maestro';
    case Mastercard = 'mastercard';
    case Diners = 'diners';
    case Jcb = 'jcb';
    case UnionPay = 'unionpay';
    case Discover = 'discover';
    case Fbf = 'fbf';
}
