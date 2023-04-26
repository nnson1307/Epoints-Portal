<?php

namespace Modules\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Api\Repositories\ServiceBrand\ServiceBrandRepository;
use Modules\Api\Repositories\ServiceBrand\ServiceBrandRepositoryInterface;
use Modules\Api\Repositories\UserBrand\UserBrandRepository;
use Modules\Api\Repositories\UserBrand\UserBrandRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ServiceBrandRepositoryInterface::class, ServiceBrandRepository::class);
        $this->app->singleton(UserBrandRepositoryInterface::class, UserBrandRepository::class);
    }
}
