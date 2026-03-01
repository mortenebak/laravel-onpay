<?php

namespace Netbums\Onpay\Enums;

enum TransactionStatus: string
{
    case Active = 'active';
    case Cancelled = 'cancelled';
    case Created = 'created';
    case Declined = 'declined';
    case Finished = 'finished';
    case Refunded = 'refunded';
    case PreAuth = 'pre_auth';
}
