<?php

namespace Netbums\Onpay\Resources;

use Netbums\Onpay\DataObjects\SimpleProviderData;
use Netbums\Onpay\Resources\Concerns\OnpayApiConsumer;

class ProviderResource
{
    use OnpayApiConsumer;

    /**
     * @return SimpleProviderData[]
     */
    public function all(): array
    {
        $response = $this->get('/v1/provider');

        return array_map(
            fn (array $item) => SimpleProviderData::fromArray($item),
            $response['data']
        );
    }
}
