<?php

namespace Netbums\Onpay\DataObjects;

readonly class TransactionListData
{
    /**
     * @param  SimpleTransactionData[]  $transactions
     */
    public function __construct(
        public array $transactions,
        public PaginationData $pagination,
    ) {}

    public static function fromArray(array $response): static
    {
        return new static(
            transactions: array_map(
                fn (array $item) => SimpleTransactionData::fromArray($item),
                $response['data']
            ),
            pagination: PaginationData::fromArray($response['meta']),
        );
    }
}
