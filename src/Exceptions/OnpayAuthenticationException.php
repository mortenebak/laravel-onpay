<?php

namespace Netbums\Onpay\Exceptions;

class OnpayAuthenticationException extends OnpayException
{
    public function __construct(string $message = 'Authentication failed. Check your API token.')
    {
        parent::__construct($message, 401);
    }
}
