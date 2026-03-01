<?php

namespace Netbums\Onpay\Tests;

use Netbums\Onpay\OnpayServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [OnpayServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('onpay.api_token', 'test-api-token');
        $app['config']->set('onpay.gateway_id', '20007895654');
        $app['config']->set('onpay.secret', 'e88ebc73104651e3c8ee9af666c19b0626c9ecacd7f8f857e3633e355776baad92e67b7faf9b87744f8c6ce4303978ed65b4165f29534118c882c0fd95f52d0c');
        $app['config']->set('onpay.base_url', 'https://api.onpay.io');
    }
}
