<?php

/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 29/03/2018
 * Time: 1:46 SA
 */

namespace Modules\ReportSale\Providers;


use Illuminate\Support\ServiceProvider;

use Modules\ReportSale\Repositories\ReportSale\ReportSaleRepositoryInterface;
use Modules\ReportSale\Repositories\ReportSale\ReportSaleRepository;
use Modules\ReportSale\Repositories\ReportSaleCustomer\ReportSaleCustomerRepositoryInterface;
use Modules\ReportSale\Repositories\ReportSaleCustomer\ReportSaleCustomerRepository;
use Modules\ReportSale\Repositories\ReportStaff\ReportSaleStaffRepo;
use Modules\ReportSale\Repositories\ReportStaff\ReportSaleStaffRepoInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ReportSaleRepositoryInterface::class, ReportSaleRepository::class);
        $this->app->singleton(ReportSaleCustomerRepositoryInterface::class, ReportSaleCustomerRepository::class);
        $this->app->singleton(ReportSaleStaffRepoInterface::class, ReportSaleStaffRepo::class);
    }
}