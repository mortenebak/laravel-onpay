<?php

use Netbums\Onpay\Enums\Acquirer;
use Netbums\Onpay\Enums\CardType;
use Netbums\Onpay\Enums\CartItemType;
use Netbums\Onpay\Enums\Currency;
use Netbums\Onpay\Enums\DeliveryTimeFrame;
use Netbums\Onpay\Enums\HistoryAction;
use Netbums\Onpay\Enums\Language;
use Netbums\Onpay\Enums\PaymentMethod;
use Netbums\Onpay\Enums\PaymentType;
use Netbums\Onpay\Enums\ScaMode;
use Netbums\Onpay\Enums\ShippingMethod;
use Netbums\Onpay\Enums\SortDirection;
use Netbums\Onpay\Enums\SubscriptionOrderBy;
use Netbums\Onpay\Enums\SubscriptionStatus;
use Netbums\Onpay\Enums\TransactionOrderBy;
use Netbums\Onpay\Enums\TransactionStatus;
use Netbums\Onpay\Enums\Wallet;

it('has correct currency numeric codes', function () {
    expect(Currency::DKK->numericCode())->toBe(208);
    expect(Currency::EUR->numericCode())->toBe(978);
    expect(Currency::USD->numericCode())->toBe(840);
    expect(Currency::GBP->numericCode())->toBe(826);
    expect(Currency::SEK->numericCode())->toBe(752);
    expect(Currency::NOK->numericCode())->toBe(578);
});

it('has correct currency exponents', function () {
    expect(Currency::DKK->exponent())->toBe(2);
    expect(Currency::ISK->exponent())->toBe(0);
    expect(Currency::JPY->exponent())->toBe(0);
    expect(Currency::EUR->exponent())->toBe(2);
});

it('determines which payment methods support subscriptions', function () {
    expect(PaymentMethod::Card->supportsSubscriptions())->toBeTrue();
    expect(PaymentMethod::ApplePay->supportsSubscriptions())->toBeTrue();
    expect(PaymentMethod::GooglePay->supportsSubscriptions())->toBeTrue();
    expect(PaymentMethod::MobilePay->supportsSubscriptions())->toBeFalse();
    expect(PaymentMethod::Anyday->supportsSubscriptions())->toBeFalse();
    expect(PaymentMethod::Klarna->supportsSubscriptions())->toBeFalse();
    expect(PaymentMethod::Swish->supportsSubscriptions())->toBeFalse();
    expect(PaymentMethod::ViaBill->supportsSubscriptions())->toBeFalse();
    expect(PaymentMethod::Vipps->supportsSubscriptions())->toBeFalse();
});

it('has correct payment type values', function () {
    expect(PaymentType::Transaction->value)->toBe('transaction');
    expect(PaymentType::Subscription->value)->toBe('subscription');
});

it('has correct language values', function () {
    expect(Language::Danish->value)->toBe('da');
    expect(Language::English->value)->toBe('en');
    expect(Language::German->value)->toBe('de');
});

it('has correct transaction status values', function () {
    expect(TransactionStatus::Active->value)->toBe('active');
    expect(TransactionStatus::Cancelled->value)->toBe('cancelled');
    expect(TransactionStatus::Finished->value)->toBe('finished');
    expect(TransactionStatus::PreAuth->value)->toBe('pre_auth');
});

it('has correct subscription status values', function () {
    expect(SubscriptionStatus::Active->value)->toBe('active');
    expect(SubscriptionStatus::Cancelled->value)->toBe('cancelled');
});

it('has correct card type values', function () {
    expect(CardType::Visa->value)->toBe('visa');
    expect(CardType::Mastercard->value)->toBe('mastercard');
    expect(CardType::Dankort->value)->toBe('dankort');
});

it('has correct acquirer values', function () {
    expect(Acquirer::Nets->value)->toBe('nets');
    expect(Acquirer::Clearhaus->value)->toBe('clearhaus');
    expect(Acquirer::Bambora->value)->toBe('bambora');
    expect(Acquirer::Swedbank->value)->toBe('swedbank');
});

it('has correct wallet values', function () {
    expect(Wallet::MobilePay->value)->toBe('mobilepay');
    expect(Wallet::ApplePay->value)->toBe('applepay');
    expect(Wallet::GooglePay->value)->toBe('googlepay');
    expect(Wallet::Vipps->value)->toBe('vipps');
});

it('has correct sort direction values', function () {
    expect(SortDirection::Ascending->value)->toBe('ASC');
    expect(SortDirection::Descending->value)->toBe('DESC');
});

it('has correct order by values', function () {
    expect(TransactionOrderBy::TransactionNumber->value)->toBe('transaction_number');
    expect(TransactionOrderBy::Created->value)->toBe('created');
    expect(SubscriptionOrderBy::SubscriptionNumber->value)->toBe('subscription_number');
});

it('has correct cart item type values', function () {
    expect(CartItemType::Physical->value)->toBe('physical');
    expect(CartItemType::Virtual->value)->toBe('virtual');
    expect(CartItemType::GiftCard->value)->toBe('giftcard');
});

it('has correct delivery time frame values', function () {
    expect(DeliveryTimeFrame::Electronic->value)->toBe('01');
    expect(DeliveryTimeFrame::SameDay->value)->toBe('02');
    expect(DeliveryTimeFrame::Overnight->value)->toBe('03');
    expect(DeliveryTimeFrame::TwoDayOrMore->value)->toBe('04');
});

it('has correct shipping method values', function () {
    expect(ShippingMethod::ShipToBillingAddress->value)->toBe('01');
    expect(ShippingMethod::DigitalGoods->value)->toBe('05');
    expect(ShippingMethod::Other->value)->toBe('07');
});

it('has correct sca mode values', function () {
    expect(ScaMode::All->value)->toBe('all');
    expect(ScaMode::Eu->value)->toBe('eu');
    expect(ScaMode::Off->value)->toBe('off');
});

it('has correct history action values', function () {
    expect(HistoryAction::Authorize->value)->toBe('authorize');
    expect(HistoryAction::Capture->value)->toBe('capture');
    expect(HistoryAction::Refund->value)->toBe('refund');
    expect(HistoryAction::Cancel->value)->toBe('cancel');
    expect(HistoryAction::AmountChange->value)->toBe('amount-change');
});
