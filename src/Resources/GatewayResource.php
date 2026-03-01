<?php

namespace Netbums\Onpay\Resources;

use Netbums\Onpay\DataObjects\GatewayInformationData;
use Netbums\Onpay\DataObjects\GatewayWindowDesignData;
use Netbums\Onpay\DataObjects\GatewayWindowIntegrationData;
use Netbums\Onpay\DataObjects\GatewayWindowLanguageData;
use Netbums\Onpay\Resources\Concerns\OnpayApiConsumer;

class GatewayResource
{
    use OnpayApiConsumer;

    public function information(): GatewayInformationData
    {
        $response = $this->get('/v1/gateway/information');

        return GatewayInformationData::fromArray($response);
    }

    public function windowIntegration(): GatewayWindowIntegrationData
    {
        $response = $this->get('/v1/gateway/window/v3/integration/');

        return GatewayWindowIntegrationData::fromArray($response);
    }

    /**
     * @return GatewayWindowDesignData[]
     */
    public function windowDesigns(): array
    {
        $response = $this->get('/v1/gateway/window/v3/design/');

        return array_map(
            fn (array $item) => GatewayWindowDesignData::fromArray($item),
            $response['data']
        );
    }

    /**
     * @return GatewayWindowLanguageData[]
     */
    public function windowLanguages(): array
    {
        $response = $this->get('/v1/gateway/window/v3/language/');

        return array_map(
            fn (array $item) => GatewayWindowLanguageData::fromArray($item),
            $response['data']
        );
    }
}
