<?php

namespace App\Providers;

use App\Repositories\Activities\ActivityRepository;
use App\Repositories\Activities\IActivityRepository;
use App\Repositories\Currencies\CurrencyRepository;
use App\Repositories\Currencies\ICurrencyRepository;
use App\Repositories\Properties\IPropertyRepository;
use App\Repositories\Properties\PropertyRepository;
use App\Repositories\PropertyTypes\IPropertyTypeRepository;
use App\Repositories\PropertyTypes\PropertyTypeRepository;
use App\Repositories\Services\IServiceRepository;
use App\Repositories\Services\ServiceRepository;
use App\Repositories\Suburbs\ISuburbRepository;
use App\Repositories\Suburbs\SuburbRepository;
use App\Repositories\TariffGroups\ITariffGroupRepository;
use App\Repositories\TariffGroups\TariffGroupRepository;
use App\Repositories\Users\IUserRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ICurrencyRepository::class, CurrencyRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IActivityRepository::class, ActivityRepository::class);
        $this->app->bind(ISuburbRepository::class, SuburbRepository::class);
        $this->app->bind(ITariffGroupRepository::class, TariffGroupRepository::class);
        $this->app->bind(IPropertyRepository::class, PropertyRepository::class);
        $this->app->bind(IServiceRepository::class, ServiceRepository::class);
        $this->app->bind(IPropertyTypeRepository::class, PropertyTypeRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
