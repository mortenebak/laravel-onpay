<?php

namespace Netbums\LaravelOnpay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Netbums\LaravelOnpay\LaravelOnpay
 */
class LaravelOnpay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Netbums\LaravelOnpay\LaravelOnpay::class;
    }
}
