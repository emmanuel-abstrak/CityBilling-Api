<?php

namespace App\Providers;

use App\Repositories\Currencies\CurrencyRepository;
use App\Repositories\Currencies\ICurrencyRepository;
use App\Repositories\Users\IUserRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ICurrencyRepository::class, CurrencyRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
