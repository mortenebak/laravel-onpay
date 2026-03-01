<?php

namespace Netbums\Onpay\DataObjects;

readonly class GatewayInformationData
{
    /**
     * @param  string[]  $activeMethods
     */
    public function __construct(
        public string $gatewayId,
        public array $activeMethods,
    ) {}

    public static function fromArray(array $response): static
    {
        $data = $response['data'] ?? $response;

        return new static(
            gatewayId: $data['gateway_id'],
            activeMethods: $data['active_methods'],
        );
    }
}
