<?php

namespace Netbums\Onpay\Resources;

use Netbums\Onpay\DataObjects\SimpleWalletData;
use Netbums\Onpay\Resources\Concerns\OnpayApiConsumer;

class WalletResource
{
    use OnpayApiConsumer;

    /**
     * @return SimpleWalletData[]
     */
    public function all(): array
    {
        $response = $this->get('/v1/wallet');

        return array_map(
            fn (array $item) => SimpleWalletData::fromArray($item),
            $response['data']
        );
    }
}
