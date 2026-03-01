<?php

namespace Netbums\Onpay;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OnpayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-onpay')
            ->hasConfigFile('onpay')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->copyAndRegisterServiceProviderInApp()
                    ->askToStarRepoOnGitHub('netbums/laravel-onpay');
            });
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Onpay::class, fn () => new Onpay);
    }
}
