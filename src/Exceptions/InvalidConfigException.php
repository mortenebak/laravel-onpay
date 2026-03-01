<?php

namespace Netbums\Onpay\Exceptions;

class InvalidConfigException extends OnpayException
{
    public function __construct(string $message = 'Invalid OnPay configuration. Please check your onpay config file.')
    {
        parent::__construct($message);
    }
}
