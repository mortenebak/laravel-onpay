<?php

namespace Netbums\Onpay\Enums;

enum Acquirer: string
{
    case Bambora = 'bambora';
    case Clearhaus = 'clearhaus';
    case Nets = 'nets';
    case Swedbank = 'swedbank';
}
