<?php

namespace Netbums\Onpay\Resources;

use Netbums\Onpay\DataObjects\DetailedSubscriptionData;
use Netbums\Onpay\DataObjects\DetailedTransactionData;
use Netbums\Onpay\DataObjects\SubscriptionAuthorizeData;
use Netbums\Onpay\DataObjects\SubscriptionListData;
use Netbums\Onpay\Enums\SortDirection;
use Netbums\Onpay\Enums\SubscriptionOrderBy;
use Netbums\Onpay\Enums\SubscriptionStatus;
use Netbums\Onpay\Resources\Concerns\OnpayApiConsumer;

class SubscriptionResource
{
    use OnpayApiConsumer;

    public function all(
        ?int $page = null,
        ?int $pageSize = null,
        ?SubscriptionOrderBy $orderBy = null,
        ?SortDirection $direction = null,
        ?string $query = null,
        ?SubscriptionStatus $status = null,
        ?string $dateAfter = null,
        ?string $dateBefore = null,
    ): SubscriptionListData {
        $params = array_filter([
            'page' => $page,
            'page_size' => $pageSize,
            'order_by' => $orderBy?->value,
            'direction' => $direction?->value,
            'query' => $query,
            'status' => $status?->value,
            'date_after' => $dateAfter,
            'date_before' => $dateBefore,
        ], fn ($value) => $value !== null);

        $response = $this->get('/v1/subscription', $params);

        return SubscriptionListData::fromArray($response);
    }

    public function find(string $uuidOrNumber): DetailedSubscriptionData
    {
        $response = $this->get("/v1/subscription/{$uuidOrNumber}");

        return DetailedSubscriptionData::fromArray($response);
    }

    public function authorize(string $uuidOrNumber, SubscriptionAuthorizeData $data): DetailedTransactionData
    {
        $response = $this->post(
            "/v1/subscription/{$uuidOrNumber}/authorize",
            $data->toArray()
        );

        return DetailedTransactionData::fromArray($response);
    }

    public function cancel(string $uuidOrNumber): DetailedSubscriptionData
    {
        $response = $this->post("/v1/subscription/{$uuidOrNumber}/cancel");

        return DetailedSubscriptionData::fromArray($response);
    }
}
