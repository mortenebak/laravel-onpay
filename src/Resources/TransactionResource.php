<?php

namespace Netbums\Onpay\Resources;

use Netbums\Onpay\DataObjects\CaptureData;
use Netbums\Onpay\DataObjects\DetailedTransactionData;
use Netbums\Onpay\DataObjects\RefundData;
use Netbums\Onpay\DataObjects\TransactionEventListData;
use Netbums\Onpay\DataObjects\TransactionListData;
use Netbums\Onpay\Enums\SortDirection;
use Netbums\Onpay\Enums\TransactionOrderBy;
use Netbums\Onpay\Enums\TransactionStatus;
use Netbums\Onpay\Resources\Concerns\OnpayApiConsumer;

class TransactionResource
{
    use OnpayApiConsumer;

    public function all(
        ?int $page = null,
        ?int $pageSize = null,
        ?TransactionOrderBy $orderBy = null,
        ?SortDirection $direction = null,
        ?string $query = null,
        ?TransactionStatus $status = null,
        ?string $dateAfter = null,
        ?string $dateBefore = null,
    ): TransactionListData {
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

        $response = $this->get('/v1/transaction/', $params);

        return TransactionListData::fromArray($response);
    }

    public function events(?string $cursor = null): TransactionEventListData
    {
        $params = array_filter(['cursor' => $cursor], fn ($v) => $v !== null);

        $response = $this->get('/v1/transaction/events/', $params);

        return TransactionEventListData::fromArray($response);
    }

    public function find(string $uuidOrNumber): DetailedTransactionData
    {
        $response = $this->get("/v1/transaction/{$uuidOrNumber}");

        return DetailedTransactionData::fromArray($response);
    }

    public function capture(string $uuidOrNumber, ?CaptureData $data = null): DetailedTransactionData
    {
        $response = $this->post(
            "/v1/transaction/{$uuidOrNumber}/capture",
            $data?->toArray() ?? []
        );

        return DetailedTransactionData::fromArray($response);
    }

    public function refund(string $uuidOrNumber, ?RefundData $data = null): DetailedTransactionData
    {
        $response = $this->post(
            "/v1/transaction/{$uuidOrNumber}/refund",
            $data?->toArray() ?? []
        );

        return DetailedTransactionData::fromArray($response);
    }

    public function cancel(string $uuidOrNumber): DetailedTransactionData
    {
        $response = $this->post("/v1/transaction/{$uuidOrNumber}/cancel");

        return DetailedTransactionData::fromArray($response);
    }
}
