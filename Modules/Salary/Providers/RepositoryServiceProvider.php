<?php

namespace Modules\Salary\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Salary\Repositories\Salary\SalaryInterface;
use Modules\Salary\Repositories\Salary\SalaryRepo;
use Modules\Salary\Repositories\SalaryCommissionConfig\SalaryCommissionConfigInterface;
use Modules\Salary\Repositories\SalaryCommissionConfig\SalaryCommissionConfigRepo;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SalaryInterface::class, SalaryRepo::class);
        $this->app->singleton(SalaryCommissionConfigInterface::class, SalaryCommissionConfigRepo::class);
    }
}