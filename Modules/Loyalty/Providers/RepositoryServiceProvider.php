<?php

namespace Modules\Loyalty\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Loyalty\Repositories\AccumulatePointsProgram\AccumulatePointsProgramRepository;
use Modules\Loyalty\Repositories\AccumulatePointsProgram\AccumulatePointsProgramRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            AccumulatePointsProgramRepositoryInterface::class,
            AccumulatePointsProgramRepository::class
        );
    }
}
