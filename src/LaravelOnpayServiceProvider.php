<?php

namespace Netbums\LaravelOnpay;

use Netbums\LaravelOnpay\Commands\LaravelOnpayCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelOnpayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-onpay')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_onpay_table')
            ->hasCommand(LaravelOnpayCommand::class);
    }
}
