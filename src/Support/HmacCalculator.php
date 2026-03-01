<?php

namespace Netbums\Onpay\Support;

class HmacCalculator
{
    public function __construct(
        protected string $secret,
    ) {}

    public function calculate(array $params): string
    {
        $onpayParams = [];

        foreach ($params as $key => $value) {
            if (str_starts_with($key, 'onpay_') && $key !== 'onpay_hmac_sha1') {
                $onpayParams[$key] = $value;
            }
        }

        ksort($onpayParams);

        $queryString = strtolower(http_build_query($onpayParams));

        return hash_hmac('sha1', $queryString, $this->secret);
    }

    public function verify(array $params, string $hmac): bool
    {
        return hash_equals($this->calculate($params), $hmac);
    }
}
