<?php

namespace Netbums\LaravelOnpay;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Netbums\LaravelOnpay\Commands\LaravelOnpayCommand;

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
