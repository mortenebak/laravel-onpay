<?php

namespace Netbums\Onpay\Enums;

enum TransactionOrderBy: string
{
    case TransactionNumber = 'transaction_number';
    case Created = 'created';
    case OrderId = 'order_id';
    case Amount = 'amount';
}
