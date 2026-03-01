<?php

namespace Netbums\Onpay\Resources;

use Netbums\Onpay\DataObjects\CreatePaymentData;
use Netbums\Onpay\DataObjects\PaymentResponseData;
use Netbums\Onpay\Resources\Concerns\OnpayApiConsumer;

class PaymentResource
{
    use OnpayApiConsumer;

    public function create(CreatePaymentData $payment): PaymentResponseData
    {
        $response = $this->post('/v1/payment/create', $payment->toArray());

        return PaymentResponseData::fromArray($response);
    }
}
