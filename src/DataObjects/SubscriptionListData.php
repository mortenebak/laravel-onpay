<?php

namespace Netbums\Onpay\DataObjects;

readonly class SubscriptionListData
{
    /**
     * @param  SimpleSubscriptionData[]  $subscriptions
     */
    public function __construct(
        public array $subscriptions,
        public PaginationData $pagination,
    ) {}

    public static function fromArray(array $response): static
    {
        return new static(
            subscriptions: array_map(
                fn (array $item) => SimpleSubscriptionData::fromArray($item),
                $response['data']
            ),
            pagination: PaginationData::fromArray($response['meta']),
        );
    }
}
