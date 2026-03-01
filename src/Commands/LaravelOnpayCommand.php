<?php

namespace Netbums\LaravelOnpay\Commands;

use Illuminate\Console\Command;

class LaravelOnpayCommand extends Command
{
    public $signature = 'laravel-onpay';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
