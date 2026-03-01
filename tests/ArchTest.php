<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('all data objects are readonly')
    ->expect('Netbums\Onpay\DataObjects')
    ->toBeReadonly();

arch('all enums are backed')
    ->expect('Netbums\Onpay\Enums')
    ->toBeEnums();

arch('all exceptions extend the base exception')
    ->expect('Netbums\Onpay\Exceptions')
    ->toExtend('Netbums\Onpay\Exceptions\OnpayException');

arch('resource classes use the api consumer trait')
    ->expect('Netbums\Onpay\Resources\PaymentResource')
    ->toUseTrait('Netbums\Onpay\Resources\Concerns\OnpayApiConsumer');

arch('transaction resource uses the api consumer trait')
    ->expect('Netbums\Onpay\Resources\TransactionResource')
    ->toUseTrait('Netbums\Onpay\Resources\Concerns\OnpayApiConsumer');

arch('subscription resource uses the api consumer trait')
    ->expect('Netbums\Onpay\Resources\SubscriptionResource')
    ->toUseTrait('Netbums\Onpay\Resources\Concerns\OnpayApiConsumer');
