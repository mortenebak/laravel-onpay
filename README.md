# Laravel OnPay

A fluent Laravel package for the [OnPay](https://onpay.io) payment gateway. Provides full coverage of the OnPay API v1 with typed DataObjects, enums, and a clean resource-based interface.

## Requirements

- PHP 8.4+
- Laravel 11.x or 12.x

## Installation

```bash
composer require netbums/laravel-onpay
```

Publish the config file:

```bash
php artisan vendor:publish --tag="laravel-onpay-config"
```

## Configuration

Add the following to your `.env` file:

```dotenv
ONPAY_API_TOKEN=your-api-token
ONPAY_GATEWAY_ID=your-gateway-id
ONPAY_SECRET=your-payment-window-secret
```

You can create an API token in the OnPay management panel under **Settings > API**.

The gateway ID and secret are used for Payment Window v3 integrations (HMAC signature calculation). If you only use the API for processing, the API token is sufficient.

The published config file at `config/onpay.php` supports these options:

```php
return [
    'api_token'  => env('ONPAY_API_TOKEN'),
    'gateway_id' => env('ONPAY_GATEWAY_ID'),
    'secret'     => env('ONPAY_SECRET'),
    'base_url'   => env('ONPAY_BASE_URL', 'https://api.onpay.io'),
    'window_url' => env('ONPAY_WINDOW_URL', 'https://onpay.io/window/v3/'),
];
```

## Quick start

```php
use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\DataObjects\CreatePaymentData;
use Netbums\Onpay\Enums\PaymentMethod;
use Netbums\Onpay\Enums\Language;

$payment = Onpay::payments()->create(new CreatePaymentData(
    currency: 'DKK',
    amount: 12000, // 120.00 DKK in minor units
    reference: 'ORDER-123',
    website: 'https://myshop.dk',
    acceptUrl: 'https://myshop.dk/payment/accept',
    declineUrl: 'https://myshop.dk/payment/decline',
    callbackUrl: 'https://myshop.dk/payment/callback',
    method: PaymentMethod::Card,
    language: Language::Danish,
));

// Redirect the customer to the payment window
return redirect($payment->paymentWindowUrl);
```

## Usage

All examples below use the `Onpay` facade. You can also inject `Netbums\Onpay\Onpay` directly from the container.

---

### Payments

#### Create a payment

```php
use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\DataObjects\CreatePaymentData;
use Netbums\Onpay\Enums\PaymentMethod;
use Netbums\Onpay\Enums\PaymentType;
use Netbums\Onpay\Enums\Language;

$payment = Onpay::payments()->create(new CreatePaymentData(
    currency: 'DKK',
    amount: 12000,
    reference: 'ORDER-456',
    website: 'https://myshop.dk',
    acceptUrl: 'https://myshop.dk/accept',
    type: PaymentType::Transaction,
    method: PaymentMethod::Card,
    language: Language::Danish,
    callbackUrl: 'https://myshop.dk/callback',
    expiration: 3600, // 1 hour
));

$payment->paymentUuid;      // "dfe8bf50-aaaa-11e7-898d-..."
$payment->paymentWindowUrl;  // "https://onpay.io/window/v3/dfe8bf50-..."
$payment->amount;            // 12000
$payment->currencyCode;      // "208"
```

#### Create a subscription payment

```php
$subscription = Onpay::payments()->create(new CreatePaymentData(
    currency: 'DKK',
    amount: 0,
    reference: 'SUB-789',
    website: 'https://myshop.dk',
    acceptUrl: 'https://myshop.dk/accept',
    type: PaymentType::Subscription,
    method: PaymentMethod::Card,
));
```

#### Create a subscription with initial transaction

```php
$payment = Onpay::payments()->create(new CreatePaymentData(
    currency: 'DKK',
    amount: 9900,
    reference: 'SUB-INIT-101',
    website: 'https://myshop.dk',
    acceptUrl: 'https://myshop.dk/accept',
    type: PaymentType::Subscription,
    method: PaymentMethod::Card,
    createTransaction: true,
));
```

#### Payment with customer info (3DS v2)

Providing customer info improves 3D Secure frictionless flow rates:

```php
use Netbums\Onpay\DataObjects\CreatePaymentData;
use Netbums\Onpay\DataObjects\PaymentInfoData;
use Netbums\Onpay\DataObjects\AccountInfoData;
use Netbums\Onpay\DataObjects\AddressData;
use Netbums\Onpay\DataObjects\PhoneData;
use Netbums\Onpay\Enums\ShippingMethod;

$payment = Onpay::payments()->create(new CreatePaymentData(
    currency: 'DKK',
    amount: 24900,
    reference: 'ORDER-3DS',
    website: 'https://myshop.dk',
    acceptUrl: 'https://myshop.dk/accept',
    info: new PaymentInfoData(
        name: 'Emil Pedersen',
        email: 'emil@example.org',
        account: new AccountInfoData(
            id: 'CUST-1234',
            dateCreated: '2024-01-15',
            purchases: 12,
        ),
        billing: new AddressData(
            city: 'Skanderborg',
            country: '208',
            line1: 'Højvangen 4',
            postalCode: '8660',
        ),
        shipping: new AddressData(
            city: 'Skanderborg',
            country: '208',
            line1: 'Højvangen 4',
            postalCode: '8660',
        ),
        phone: new PhoneData(
            mobileCc: '45',
            mobileNumber: '37123456',
        ),
        addressIdenticalShipping: 'Y',
        shippingMethod: ShippingMethod::ShipToBillingAddress,
    ),
));
```

#### Payment with cart data

Cart data is required for some payment methods (e.g. Klarna):

```php
use Netbums\Onpay\DataObjects\CreatePaymentData;
use Netbums\Onpay\DataObjects\CartData;
use Netbums\Onpay\DataObjects\CartItemData;
use Netbums\Onpay\DataObjects\CartShippingData;
use Netbums\Onpay\Enums\CartItemType;
use Netbums\Onpay\Enums\PaymentMethod;

$payment = Onpay::payments()->create(new CreatePaymentData(
    currency: 'DKK',
    amount: 34900,
    reference: 'ORDER-CART',
    website: 'https://myshop.dk',
    acceptUrl: 'https://myshop.dk/accept',
    method: PaymentMethod::Klarna,
    cart: new CartData(
        items: [
            new CartItemData(
                name: 'Organic T-Shirt',
                price: 14900,
                tax: 2980,
                quantity: 2,
                sku: 'TSH-001',
                type: CartItemType::Physical,
            ),
            new CartItemData(
                name: 'Gift Card',
                price: 5000,
                tax: 0,
                quantity: 1,
                type: CartItemType::GiftCard,
            ),
        ],
        shipping: new CartShippingData(
            price: 4900,
            tax: 980,
            name: 'Standard delivery',
        ),
        discount: 4800,
    ),
));
```

---

### Transactions

#### List transactions

```php
use Netbums\Onpay\Facades\Onpay;
use Netbums\Onpay\Enums\TransactionStatus;
use Netbums\Onpay\Enums\TransactionOrderBy;
use Netbums\Onpay\Enums\SortDirection;

// Basic list
$result = Onpay::transactions()->all();

$result->transactions;          // SimpleTransactionData[]
$result->pagination->total;     // 200
$result->pagination->totalPages; // 20

// Filtered and sorted
$result = Onpay::transactions()->all(
    page: 1,
    pageSize: 50,
    status: TransactionStatus::Active,
    orderBy: TransactionOrderBy::Created,
    direction: SortDirection::Descending,
    dateAfter: '2025-01-01',
    dateBefore: '2025-03-01',
);

// Search by order ID or transaction number
$result = Onpay::transactions()->all(query: 'ORDER-123');
```

#### Get a specific transaction

```php
// By UUID
$transaction = Onpay::transactions()->find('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');

// By transaction number
$transaction = Onpay::transactions()->find('1234');

$transaction->uuid;
$transaction->amount;           // 12300
$transaction->charged;          // 0
$transaction->status;           // "active"
$transaction->cardType;         // "visa"
$transaction->cardMask;         // "457199XXXXXX1234"
$transaction->hasCardholderData; // true
$transaction->cardholderData;   // CardholderData object
$transaction->history;          // TransactionHistoryData[]
```

#### Capture a transaction

```php
use Netbums\Onpay\DataObjects\CaptureData;

// Capture full amount
$transaction = Onpay::transactions()->capture('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');

// Capture specific amount (partial capture)
$transaction = Onpay::transactions()->capture(
    '0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
    new CaptureData(amount: 5000),
);

// Capture using expected post-action amount
$transaction = Onpay::transactions()->capture(
    '0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
    new CaptureData(postActionChargeAmount: 12300),
);
```

#### Refund a transaction

```php
use Netbums\Onpay\DataObjects\RefundData;

// Refund full amount
$transaction = Onpay::transactions()->refund('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');

// Refund specific amount (partial refund)
$transaction = Onpay::transactions()->refund(
    '0bb17c90-a80e-11e7-8fc8-b61928e29a9f',
    new RefundData(amount: 5000),
);
```

#### Cancel a transaction

```php
$transaction = Onpay::transactions()->cancel('0bb17c90-a80e-11e7-8fc8-b61928e29a9f');
// $transaction->status === "cancelled"
```

#### Transaction events (event-sourced updates)

Optimal for keeping an external system in sync. Returns events sorted newest-last with cursor-based pagination:

```php
// First fetch
$result = Onpay::transactions()->events();

foreach ($result->events as $event) {
    $event->uuid;        // Event UUID
    $event->transaction; // Transaction UUID
    $event->action;      // "authorize", "capture", etc.
    $event->amount;
    $event->successful;
}

// Save cursor, then resume later
$cursor = $result->nextCursor;

// Subsequent fetch with cursor
$result = Onpay::transactions()->events(cursor: $cursor);
```

---

### Subscriptions

#### List subscriptions

```php
use Netbums\Onpay\Enums\SubscriptionStatus;
use Netbums\Onpay\Enums\SubscriptionOrderBy;
use Netbums\Onpay\Enums\SortDirection;

$result = Onpay::subscriptions()->all(
    status: SubscriptionStatus::Active,
    orderBy: SubscriptionOrderBy::Created,
    direction: SortDirection::Descending,
);

$result->subscriptions;       // SimpleSubscriptionData[]
$result->pagination->total;
```

#### Get a specific subscription

```php
$subscription = Onpay::subscriptions()->find('03c8c78e-a7ac-11e7-9cff-b61928e29a9f');

$subscription->uuid;
$subscription->subscriptionNumber;
$subscription->status;          // "active"
$subscription->cardType;        // "visa"
$subscription->history;         // SubscriptionHistoryData[]
$subscription->transactions;    // SimpleTransactionData[]
```

#### Create a transaction from a subscription (recurring charge)

```php
use Netbums\Onpay\DataObjects\SubscriptionAuthorizeData;

$transaction = Onpay::subscriptions()->authorize(
    '03c8c78e-a7ac-11e7-9cff-b61928e29a9f',
    new SubscriptionAuthorizeData(
        amount: 9900,
        orderId: 'RECURRING-2025-03',
    ),
);

$transaction->uuid;    // New transaction UUID
$transaction->amount;  // 9900
$transaction->status;  // "active"
```

With surcharge:

```php
$transaction = Onpay::subscriptions()->authorize(
    '03c8c78e-a7ac-11e7-9cff-b61928e29a9f',
    new SubscriptionAuthorizeData(
        amount: 9900,
        orderId: 'RECURRING-2025-04',
        surchargeEnabled: true,
        surchargeVatRate: 2500, // 25%
    ),
);
```

#### Cancel a subscription

```php
$subscription = Onpay::subscriptions()->cancel('03c8c78e-a7ac-11e7-9cff-b61928e29a9f');
// $subscription->status === "cancelled"
```

---

### Gateway

```php
// Gateway information
$info = Onpay::gateway()->information();
$info->gatewayId;       // "12345678910111213141516"
$info->activeMethods;   // ["card", "mobilepay", "viabill"]

// Payment window secret
$integration = Onpay::gateway()->windowIntegration();
$integration->secret;

// Available window designs
$designs = Onpay::gateway()->windowDesigns();
// [GatewayWindowDesignData { name: "Danish window" }, ...]

// Available window languages
$languages = Onpay::gateway()->windowLanguages();
// [GatewayWindowLanguageData { locale: "da" }, ...]
```

---

### Acquirers

```php
// List all acquirers
$acquirers = Onpay::acquirers()->all();
// [SimpleAcquirerData { name: "nets", active: true }, ...]

// Get detailed acquirer info
$acquirer = Onpay::acquirers()->find('clearhaus');
$acquirer->name;          // "clearhaus"
$acquirer->active;        // true
$acquirer->apiKey;
$acquirer->mcc;
$acquirer->scaMode;       // "all"
$acquirer->exemptions;    // ["sca_low_value" => true]

// Update acquirer settings
$acquirer = Onpay::acquirers()->update('nets', [
    'active' => true,
    'tof' => '12345678',
]);
```

---

### Providers & Wallets

```php
// List payment providers
$providers = Onpay::providers()->all();
// [SimpleProviderData { name: "Klarna", active: true }, ...]

// List wallets
$wallets = Onpay::wallets()->all();
// [SimpleWalletData { name: "mobilepay", active: true }, ...]
```

---

### Payment Window v3 (HMAC)

The package includes an HMAC calculator for building and verifying Payment Window v3 form posts:

#### Calculating HMAC for a form post

```php
use Netbums\Onpay\Facades\Onpay;

$params = [
    'onpay_gatewayid' => Onpay::gatewayId(),
    'onpay_currency' => 'DKK',
    'onpay_amount' => '12000',
    'onpay_reference' => 'ORDER-123',
    'onpay_accepturl' => 'https://myshop.dk/accept',
    'onpay_website' => 'https://myshop.dk',
];

$hmac = Onpay::hmac()->calculate($params);

$params['onpay_hmac_sha1'] = $hmac;

// Now render your form with $params
```

#### Verifying HMAC on callback/accept

```php
public function handleCallback(Request $request)
{
    $params = $request->all();
    $hmac = $request->input('onpay_hmac_sha1');

    if (! Onpay::hmac()->verify($params, $hmac)) {
        abort(403, 'Invalid HMAC signature');
    }

    // Process the payment...
    $uuid = $request->input('onpay_uuid');
    $transaction = Onpay::transactions()->find($uuid);
}
```

---

## Error handling

All API errors throw typed exceptions you can catch:

```php
use Netbums\Onpay\Exceptions\OnpayApiException;
use Netbums\Onpay\Exceptions\OnpayAuthenticationException;
use Netbums\Onpay\Exceptions\OnpayValidationException;

try {
    $transaction = Onpay::transactions()->capture($uuid, new CaptureData(amount: 999));
} catch (OnpayAuthenticationException $e) {
    // 401 - Invalid API token
} catch (OnpayValidationException $e) {
    // 422 - Validation error
    $e->getMessage(); // Human-readable message
    $e->errors;       // Raw error array from API
} catch (OnpayApiException $e) {
    // 4xx/5xx - General API error
    $e->getCode();      // HTTP status code
    $e->getMessage();
    $e->acquirerCode;   // Acquirer-specific code (if available)
}
```

Exception hierarchy:

```
OnpayException
  └── OnpayApiException
        ├── OnpayValidationException   (422)
        └── OnpayAuthenticationException (401)
InvalidConfigException
```

---

## Available enums

The package uses PHP backed enums everywhere instead of magic strings:

| Enum | Values |
|---|---|
| `Currency` | `DKK`, `EUR`, `USD`, `GBP`, `SEK`, `NOK`, ... (24 currencies) |
| `PaymentMethod` | `Card`, `MobilePay`, `ApplePay`, `GooglePay`, `Klarna`, `Swish`, `ViaBill`, `Vipps`, `Anyday` |
| `PaymentType` | `Transaction`, `Subscription` |
| `Language` | `Danish`, `English`, `German`, `Swedish`, `Norwegian`, ... (12 languages) |
| `TransactionStatus` | `Active`, `Cancelled`, `Created`, `Declined`, `Finished`, `Refunded`, `PreAuth` |
| `SubscriptionStatus` | `Active`, `Cancelled`, `Created` |
| `CardType` | `Visa`, `Mastercard`, `Dankort`, `Amex`, `Maestro`, ... |
| `Acquirer` | `Nets`, `Clearhaus`, `Bambora`, `Swedbank` |
| `Wallet` | `MobilePay`, `ApplePay`, `GooglePay`, `Vipps` |
| `CartItemType` | `Physical`, `Virtual`, `GiftCard` |
| `DeliveryTimeFrame` | `Electronic`, `SameDay`, `Overnight`, `TwoDayOrMore` |
| `ShippingMethod` | `ShipToBillingAddress`, `ShipToVerifiedAddress`, `ShipToOther`, `ShipToStore`, `DigitalGoods`, `TravelAndEventTickets`, `Other` |
| `SortDirection` | `Ascending`, `Descending` |
| `TransactionOrderBy` | `TransactionNumber`, `Created`, `OrderId`, `Amount` |
| `SubscriptionOrderBy` | `SubscriptionNumber`, `Created`, `OrderId`, `Status` |
| `ScaMode` | `All`, `Eu`, `Off` |
| `HistoryAction` | `Authorize`, `Capture`, `Refund`, `Cancel`, `Create`, `Acs`, `AmountChange`, `Renew` |

The `Currency` enum also provides helper methods:

```php
use Netbums\Onpay\Enums\Currency;

Currency::DKK->numericCode(); // 208
Currency::DKK->exponent();    // 2
Currency::JPY->exponent();    // 0

// Convert major to minor units
$minorUnits = 120.00 * (10 ** Currency::DKK->exponent()); // 12000
```

---

## DataObjects

All request and response data is represented as **readonly PHP classes** -- no magic arrays. Key DataObjects:

**Request DTOs:**
- `CreatePaymentData` -- Create a payment
- `CaptureData` -- Capture amount/postActionChargeAmount
- `RefundData` -- Refund amount/postActionRefundAmount
- `SubscriptionAuthorizeData` -- Recurring charge from subscription
- `PaymentInfoData` -- 3DS v2 customer info
- `CartData`, `CartItemData`, `CartShippingData`, `CartHandlingData` -- Cart details
- `AccountInfoData`, `AddressData`, `PhoneData` -- Customer details

**Response DTOs:**
- `PaymentResponseData` -- Payment creation response
- `SimpleTransactionData`, `DetailedTransactionData` -- Transaction data
- `SimpleSubscriptionData`, `DetailedSubscriptionData` -- Subscription data
- `TransactionListData`, `SubscriptionListData` -- Paginated lists
- `TransactionHistoryData`, `SubscriptionHistoryData` -- History entries
- `TransactionEventData`, `TransactionEventListData` -- Event-sourced updates
- `CardholderData`, `DeliveryAddressData` -- Cardholder info
- `PaginationData` -- Pagination metadata
- `GatewayInformationData`, `GatewayWindowIntegrationData`, `GatewayWindowDesignData`, `GatewayWindowLanguageData`
- `SimpleAcquirerData`, `DetailedAcquirerData`
- `SimpleProviderData`, `SimpleWalletData`

---

## API reference

### `Onpay::payments()`

| Method | Return type | Description |
|---|---|---|
| `create(CreatePaymentData)` | `PaymentResponseData` | Create a new payment |

### `Onpay::transactions()`

| Method | Return type | Description |
|---|---|---|
| `all(...)` | `TransactionListData` | List transactions (paginated, filterable) |
| `find(string $uuidOrNumber)` | `DetailedTransactionData` | Get a specific transaction |
| `events(?string $cursor)` | `TransactionEventListData` | Get transaction events |
| `capture(string $uuidOrNumber, ?CaptureData)` | `DetailedTransactionData` | Capture a transaction |
| `refund(string $uuidOrNumber, ?RefundData)` | `DetailedTransactionData` | Refund a transaction |
| `cancel(string $uuidOrNumber)` | `DetailedTransactionData` | Cancel a transaction |

### `Onpay::subscriptions()`

| Method | Return type | Description |
|---|---|---|
| `all(...)` | `SubscriptionListData` | List subscriptions (paginated, filterable) |
| `find(string $uuidOrNumber)` | `DetailedSubscriptionData` | Get a specific subscription |
| `authorize(string $uuidOrNumber, SubscriptionAuthorizeData)` | `DetailedTransactionData` | Create transaction from subscription |
| `cancel(string $uuidOrNumber)` | `DetailedSubscriptionData` | Cancel a subscription |

### `Onpay::gateway()`

| Method | Return type | Description |
|---|---|---|
| `information()` | `GatewayInformationData` | Get gateway info |
| `windowIntegration()` | `GatewayWindowIntegrationData` | Get window secret |
| `windowDesigns()` | `GatewayWindowDesignData[]` | List window designs |
| `windowLanguages()` | `GatewayWindowLanguageData[]` | List window languages |

### `Onpay::acquirers()`

| Method | Return type | Description |
|---|---|---|
| `all()` | `SimpleAcquirerData[]` | List all acquirers |
| `find(string $name)` | `DetailedAcquirerData` | Get acquirer details |
| `update(string $name, array $data)` | `DetailedAcquirerData` | Update acquirer settings |

### `Onpay::providers()`

| Method | Return type | Description |
|---|---|---|
| `all()` | `SimpleProviderData[]` | List all providers |

### `Onpay::wallets()`

| Method | Return type | Description |
|---|---|---|
| `all()` | `SimpleWalletData[]` | List all wallets |

### `Onpay::hmac()`

| Method | Return type | Description |
|---|---|---|
| `calculate(array $params)` | `string` | Calculate HMAC for payment window |
| `verify(array $params, string $hmac)` | `bool` | Verify HMAC from callback |

---

## Testing

```bash
composer test
```

The test suite uses [Pest](https://pestphp.com) with Laravel HTTP faking, so no actual API calls are made.

When testing your own application, you can fake the Onpay facade:

```php
use Illuminate\Support\Facades\Http;

Http::fake([
    'api.onpay.io/*' => Http::response([...]),
]);

// Your code that calls Onpay will use the faked responses
```

---

## Test cards

When test mode is enabled on your OnPay merchant account, use these cards (any future expiry date and any CVC):

| Card | PAN | Result |
|---|---|---|
| Visa/Dankort | `4571 9900 2080 0010` | Accepted |
| Visa/Dankort | `4571 9900 2080 0028` | Denied |
| Visa | `4687 3800 2080 0015` | Accepted |
| MasterCard | `5204 7400 2080 0011` | Accepted |
| Dankort | `5019 9900 2080 0017` | Accepted |

See the [OnPay documentation](https://onpay.io/docs/technical/index.html#test-data) for the full list of test cards.

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
