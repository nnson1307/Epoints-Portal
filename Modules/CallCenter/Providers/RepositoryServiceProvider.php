<?php

namespace Modules\CallCenter\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CallCenter\Repositories\CallCenter\CallCenterRepoInterface;
use Modules\CallCenter\Repositories\CallCenter\CallCenterRepo;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CallCenterRepoInterface::class, CallCenterRepo::class);
    }
}