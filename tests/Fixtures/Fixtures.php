<?php

namespace Netbums\Onpay\Tests\Fixtures;

class Fixtures
{
    public static function paymentCreateResponse(): array
    {
        return [
            'data' => [
                'payment_uuid' => 'dfe8bf50-aaaa-11e7-898d-be9d7bb73511',
                'amount' => 12000,
                'currency_code' => '208',
                'expiration' => '2025-03-02 11:12:14',
                'language' => 'en',
                'method' => 'card',
            ],
            'links' => [
                'payment_window' => 'https://onpay.io/window/v3/dfe8bf50-aaaa-11e7-898d-be9d7bb73511',
            ],
        ];
    }

    public static function simpleTransaction(): array
    {
        return [
            '3dsecure' => false,
            'acquirer' => 'nets',
            'amount' => 12300,
            'card_type' => 'visa',
            'charged' => 0,
            'created' => '2024-10-04 09:38:46',
            'currency_code' => 208,
            'order_id' => '1234567890',
            'refunded' => 0,
            'status' => 'active',
            'transaction_number' => 1234,
            'uuid' => '0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
            'wallet' => 'mobilepay',
            'testmode' => false,
            'has_cardholder_data' => false,
            'links' => [
                'self' => '/v1/transaction/0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
            ],
        ];
    }

    public static function transactionListResponse(): array
    {
        return [
            'data' => [self::simpleTransaction()],
            'meta' => [
                'pagination' => [
                    'total' => 200,
                    'count' => 10,
                    'per_page' => 10,
                    'current_page' => 1,
                    'total_pages' => 20,
                    'links' => [
                        'next' => 'https://api.onpay.io/v1/transaction/?page=2',
                    ],
                ],
            ],
        ];
    }

    public static function transactionEventListResponse(): array
    {
        return [
            'data' => [
                [
                    'uuid' => 'dfe8bf50-aaaa-11e7-898d-be9d7bb73511',
                    'transaction' => 'df682406-aaaa-11e7-898d-be9d7bb73511',
                    'date_time' => '2025-03-24 07:49:49',
                    'action' => 'authorize',
                    'successful' => true,
                    'amount' => 8584,
                    'result_code' => '0',
                    'result_text' => 'approved',
                    'author' => 'system',
                    'ip' => '127.0.0.1',
                    'links' => [
                        'transaction' => 'https://api.onpay.io/v1/transaction/df682406-aaaa-11e7-898d-be9d7bb73511',
                    ],
                ],
            ],
            'meta' => [
                'next_cursor' => '/v1/transaction/events/?cursor=abc123',
            ],
        ];
    }

    public static function detailedTransaction(): array
    {
        return [
            'data' => [
                '3dsecure' => false,
                'acquirer' => 'nets',
                'amount' => 12300,
                'card_bin' => '457199',
                'card_mask' => '457199XXXXXX1234',
                'card_type' => 'visa',
                'card_country' => 208,
                'charged' => 0,
                'created' => '2024-10-04 09:38:46',
                'currency_code' => 208,
                'expiry_month' => 4,
                'expiry_year' => 2028,
                'ip' => '127.0.0.1',
                'ip_country' => 208,
                'order_id' => '1234567890',
                'refunded' => 0,
                'status' => 'active',
                'subscription_number' => 321,
                'subscription_uuid' => '03e8162a-a7ac-11e7-9d00-b61928e29a9f',
                'transaction_number' => 1234,
                'uuid' => '0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
                'wallet' => 'mobilepay',
                'has_cardholder_data' => true,
                'cardholder_data' => [
                    'first_name' => 'First name',
                    'last_name' => 'Last name',
                    'attention' => 'Attention name',
                    'company' => 'OnPay',
                    'address1' => 'Street name',
                    'address2' => '42',
                    'postal_code' => '0000',
                    'city' => 'City',
                    'country' => 208,
                    'email' => 'email@onpay.io',
                    'phone' => '11223344',
                    'delivery_address' => [
                        'first_name' => 'Del First',
                        'last_name' => 'Del Last',
                        'attention' => '',
                        'company' => '',
                        'address1' => 'Del Street',
                        'address2' => '',
                        'postal_code' => '1111',
                        'city' => 'Del City',
                        'country' => 208,
                    ],
                ],
                'testmode' => false,
                'history' => [
                    [
                        'action' => 'authorize',
                        'amount' => 12300,
                        'author' => 'email@onpay.io',
                        'date_time' => '2024-10-04 09:38:46',
                        'ip' => '127.0.0.1',
                        'result_code' => '000',
                        'result_text' => 'Approved',
                        'uuid' => '15194d80-32ae-45a9-843d-f1acb9b9d484',
                        'successful' => true,
                    ],
                ],
            ],
            'links' => [
                'self' => '/v1/transaction/0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
                'subscription' => '/v1/subscription/03e8162a-a7ac-11e7-9d00-b61928e29a9f',
            ],
        ];
    }

    public static function capturedTransaction(): array
    {
        $data = self::detailedTransaction();
        $data['data']['charged'] = 12300;
        $data['data']['status'] = 'finished';

        return $data;
    }

    public static function refundedTransaction(): array
    {
        $data = self::detailedTransaction();
        $data['data']['refunded'] = 10000;
        $data['data']['status'] = 'active';

        return $data;
    }

    public static function cancelledTransaction(): array
    {
        $data = self::detailedTransaction();
        $data['data']['status'] = 'cancelled';

        return $data;
    }

    public static function simpleSubscription(): array
    {
        return [
            '3dsecure' => false,
            'acquirer' => 'nets',
            'card_type' => 'visa',
            'created' => '2024-10-04 09:38:46',
            'currency_code' => 208,
            'order_id' => '1234567891',
            'status' => 'active',
            'subscription_number' => 123,
            'uuid' => '03c8c78e-a7ac-11e7-9cff-b61928e29a9f',
            'wallet' => '',
            'testmode' => false,
            'links' => [
                'self' => '/v1/subscription/03c8c78e-a7ac-11e7-9cff-b61928e29a9f',
            ],
        ];
    }

    public static function subscriptionListResponse(): array
    {
        return [
            'data' => [self::simpleSubscription()],
            'meta' => [
                'pagination' => [
                    'total' => 200,
                    'count' => 10,
                    'per_page' => 10,
                    'current_page' => 1,
                    'total_pages' => 20,
                    'links' => [],
                ],
            ],
        ];
    }

    public static function detailedSubscription(): array
    {
        return [
            'data' => [
                '3dsecure' => false,
                'acquirer' => 'nets',
                'card_bin' => '457199',
                'card_mask' => '457199XXXXXX1234',
                'card_type' => 'visa',
                'card_country' => 208,
                'created' => '2024-10-04 09:38:46',
                'currency_code' => 208,
                'expiry_month' => 4,
                'expiry_year' => 2028,
                'ip' => '127.0.0.1',
                'ip_country' => 208,
                'order_id' => '1234567891',
                'status' => 'active',
                'subscription_number' => 123,
                'uuid' => '0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
                'wallet' => '',
                'testmode' => false,
                'history' => [
                    [
                        'action' => 'authorize',
                        'author' => 'email@onpay.io',
                        'date_time' => '2024-10-04 09:38:46',
                        'ip' => '127.0.0.1',
                        'result_code' => '000',
                        'result_text' => 'Approved',
                        'successful' => true,
                        'uuid' => 'abc-123',
                    ],
                ],
                'transactions' => [self::simpleTransaction()],
            ],
            'links' => [
                'self' => '/v1/subscription/0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
            ],
        ];
    }

    public static function gatewayInformation(): array
    {
        return [
            'data' => [
                'gateway_id' => '12345678910111213141516',
                'active_methods' => ['card', 'mobilepay', 'viabill'],
            ],
        ];
    }

    public static function gatewayWindowIntegration(): array
    {
        return [
            'data' => [
                'secret' => 'db78b4dac653b65a7bb09fa7d6512367c8056214c29558f6bd1e2327a3a2854c0c57683ec7f784ec596757adee0d901d53d88145ef7251c496444e8fececb0c7',
            ],
        ];
    }

    public static function gatewayWindowDesigns(): array
    {
        return [
            'data' => [
                ['name' => 'Danish window'],
                ['name' => 'International window'],
            ],
        ];
    }

    public static function gatewayWindowLanguages(): array
    {
        return [
            'data' => [
                ['locale' => 'da'],
                ['locale' => 'en'],
                ['locale' => 'de'],
            ],
        ];
    }

    public static function acquirerList(): array
    {
        return [
            'data' => [
                ['name' => 'nets', 'active' => true, 'links' => ['self' => '/v1/acquirer/nets']],
                ['name' => 'clearhaus', 'active' => false, 'links' => ['self' => '/v1/acquirer/clearhaus']],
            ],
        ];
    }

    public static function detailedAcquirer(): array
    {
        return [
            'data' => [
                'name' => 'clearhaus',
                'active' => true,
                'api_key' => '3764eca2-a225-44b6-b5cc-c069efaf38ac',
                'exemption' => ['sca_low_value' => true],
                'mastercard_bin' => '123456',
                'mcc' => '1234',
                'merchant_id' => '000000001234567',
                'sca_mode' => 'all',
                'visa_bin' => '123456',
            ],
            'links' => ['self' => '/v1/acquirer/clearhaus'],
        ];
    }

    public static function providerList(): array
    {
        return [
            'data' => [
                ['name' => 'Anyday', 'active' => true],
                ['name' => 'Klarna', 'active' => true],
                ['name' => 'Viabill', 'active' => false],
            ],
        ];
    }

    public static function walletList(): array
    {
        return [
            'data' => [
                ['name' => 'applepay', 'active' => true],
                ['name' => 'googlepay', 'active' => true],
                ['name' => 'mobilepay', 'active' => true],
            ],
        ];
    }

    public static function errorResponse(string $message = 'Error: card expired'): array
    {
        return [
            'errors' => [
                ['message' => $message],
            ],
        ];
    }
}
