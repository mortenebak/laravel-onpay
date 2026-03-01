<?php

namespace Netbums\Onpay\Resources\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Netbums\Onpay\Exceptions\OnpayApiException;
use Netbums\Onpay\Exceptions\OnpayAuthenticationException;
use Netbums\Onpay\Exceptions\OnpayValidationException;

trait OnpayApiConsumer
{
    public function __construct(
        protected PendingRequest $client,
    ) {}

    protected function get(string $endpoint, array $query = []): array
    {
        return $this->request('get', $endpoint, query: $query);
    }

    protected function post(string $endpoint, array $data = []): array
    {
        return $this->request('post', $endpoint, data: $data);
    }

    protected function patch(string $endpoint, array $data = []): array
    {
        return $this->request('patch', $endpoint, data: $data);
    }

    protected function request(string $method, string $endpoint, array $data = [], array $query = []): array
    {
        $response = match ($method) {
            'get' => $this->client->get($endpoint, $query),
            'post' => $this->client->post($endpoint, $data),
            'patch' => $this->client->patch($endpoint, $data),
            'delete' => $this->client->delete($endpoint, $data),
            default => throw new OnpayApiException("Unsupported HTTP method: {$method}", 405),
        };

        if ($response->status() === 401) {
            throw new OnpayAuthenticationException;
        }

        if ($response->status() === 422) {
            $body = $response->json();
            $message = $this->extractErrorMessage($body);

            throw new OnpayValidationException(
                message: $message,
                statusCode: 422,
                errors: $body['errors'] ?? null,
            );
        }

        if ($response->failed()) {
            $body = $response->json();
            $message = $this->extractErrorMessage($body);

            throw new OnpayApiException(
                message: $message,
                statusCode: $response->status(),
                errors: $body['errors'] ?? null,
                acquirerCode: $body['acquirerCode'] ?? null,
            );
        }

        return $response->json() ?? [];
    }

    private function extractErrorMessage(?array $body): string
    {
        if (! $body) {
            return 'Unknown OnPay API error';
        }

        if (isset($body['errors'])) {
            return collect($body['errors'])
                ->pluck('message')
                ->implode(', ');
        }

        return $body['message'] ?? 'OnPay API request failed';
    }
}
