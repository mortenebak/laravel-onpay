<?php

namespace Netbums\Onpay\Enums;

enum CartItemType: string
{
    case Physical = 'physical';
    case Virtual = 'virtual';
    case GiftCard = 'giftcard';
}
