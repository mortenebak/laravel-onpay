<?php

namespace Netbums\Onpay\DataObjects;

readonly class PaginationData
{
    public function __construct(
        public int $total,
        public int $count,
        public int $perPage,
        public int $currentPage,
        public int $totalPages,
        public ?string $nextUrl = null,
        public ?string $previousUrl = null,
    ) {}

    public static function fromArray(array $data): static
    {
        $pagination = $data['pagination'] ?? $data;

        return new static(
            total: $pagination['total'],
            count: $pagination['count'],
            perPage: $pagination['per_page'],
            currentPage: $pagination['current_page'],
            totalPages: $pagination['total_pages'],
            nextUrl: $pagination['links']['next'] ?? null,
            previousUrl: $pagination['links']['previous'] ?? null,
        );
    }
}
