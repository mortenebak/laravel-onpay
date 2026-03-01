<?php

namespace Netbums\Onpay\Enums;

enum PaymentType: string
{
    case Transaction = 'transaction';
    case Subscription = 'subscription';
}
