<?php

namespace Netbums\Onpay\Enums;

enum SubscriptionStatus: string
{
    case Active = 'active';
    case Cancelled = 'cancelled';
    case Created = 'created';
}
