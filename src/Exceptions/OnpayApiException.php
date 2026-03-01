<?php

namespace Netbums\Onpay\Exceptions;

class OnpayApiException extends OnpayException
{
    public function __construct(
        string $message,
        int $statusCode,
        public readonly ?array $errors = null,
        public readonly ?string $acquirerCode = null,
    ) {
        parent::__construct($message, $statusCode);
    }
}
