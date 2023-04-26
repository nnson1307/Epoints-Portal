<?php

namespace Modules\Estimate\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\Estimate\Repositories\EstimateBranchTime\EstimateBranchTimeRepoInterface;
use Modules\Estimate\Repositories\EstimateBranchTime\EstimateBranchTimeRepo;


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
        $this->app->singleton(EstimateBranchTimeRepoInterface::class, EstimateBranchTimeRepo::class);
    }
}