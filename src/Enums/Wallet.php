<?php

namespace Netbums\Onpay\Enums;

enum Wallet: string
{
    case ApplePay = 'applepay';
    case GooglePay = 'googlepay';
    case MobilePay = 'mobilepay';
    case Vipps = 'vipps';
}
