<?php

namespace Netbums\Onpay\Enums;

enum HistoryAction: string
{
    case Acs = 'acs';
    case AmountChange = 'amount-change';
    case Authorize = 'authorize';
    case Cancel = 'cancel';
    case Capture = 'capture';
    case Create = 'create';
    case Refund = 'refund';
    case Renew = 'renew';
}
