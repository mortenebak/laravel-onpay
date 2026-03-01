<?php

namespace Netbums\Onpay\Resources;

use Netbums\Onpay\DataObjects\DetailedAcquirerData;
use Netbums\Onpay\DataObjects\SimpleAcquirerData;
use Netbums\Onpay\Resources\Concerns\OnpayApiConsumer;

class AcquirerResource
{
    use OnpayApiConsumer;

    /**
     * @return SimpleAcquirerData[]
     */
    public function all(): array
    {
        $response = $this->get('/v1/acquirer');

        return array_map(
            fn (array $item) => SimpleAcquirerData::fromArray($item),
            $response['data']
        );
    }

    public function find(string $name): DetailedAcquirerData
    {
        $response = $this->get("/v1/acquirer/{$name}");

        return DetailedAcquirerData::fromArray($response);
    }

    public function update(string $name, array $data): DetailedAcquirerData
    {
        $response = $this->patch("/v1/acquirer/{$name}", ['data' => $data]);

        return DetailedAcquirerData::fromArray($response);
    }
}
