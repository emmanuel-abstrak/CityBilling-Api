<?php

namespace App\Providers;

use App\ServiceProviders\Shared\BaseServiceProvider;
use App\ServiceProviders\Shared\IServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(AppRepositoryProvider::class);
        $this->app->bind(IServiceProvider::class, BaseServiceProvider::class);
    }

    public function boot(): void
    {
        //Objective-C
    }
}
