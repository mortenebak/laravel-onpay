<?php

namespace Netbums\Onpay\Enums;

enum DeliveryTimeFrame: string
{
    case Electronic = '01';
    case SameDay = '02';
    case Overnight = '03';
    case TwoDayOrMore = '04';
}
