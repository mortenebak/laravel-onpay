<?php

namespace Netbums\Onpay\Enums;

enum ShippingMethod: string
{
    case ShipToBillingAddress = '01';
    case ShipToVerifiedAddress = '02';
    case ShipToOther = '03';
    case ShipToStore = '04';
    case DigitalGoods = '05';
    case TravelAndEventTickets = '06';
    case Other = '07';
}
