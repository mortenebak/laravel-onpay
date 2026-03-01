<?php

namespace Netbums\Onpay\DataObjects;

readonly class TransactionEventListData
{
    /**
     * @param  TransactionEventData[]  $events
     */
    public function __construct(
        public array $events,
        public ?string $nextCursor = null,
    ) {}

    public static function fromArray(array $response): static
    {
        return new static(
            events: array_map(
                fn (array $item) => TransactionEventData::fromArray($item),
                $response['data']
            ),
            nextCursor: $response['meta']['next_cursor'] ?? null,
        );
    }
}
