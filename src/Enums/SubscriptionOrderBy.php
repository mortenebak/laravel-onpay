<?php

namespace Netbums\Onpay\Enums;

enum SubscriptionOrderBy: string
{
    case SubscriptionNumber = 'subscription_number';
    case Created = 'created';
    case OrderId = 'order_id';
    case Status = 'status';
}
