<?php

namespace Modules\Commission\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\Commission\Repositories\CommissionRepoInterface;
use Modules\Commission\Repositories\CommissionRepo;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // HaoNMN
        $this->app->singleton(CommissionRepoInterface::class, CommissionRepo::class);
    }
}