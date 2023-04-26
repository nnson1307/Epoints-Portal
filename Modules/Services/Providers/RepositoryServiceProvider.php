<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 29/03/2018
 * Time: 1:46 SA
 */

namespace Modules\Services\Providers;


use Illuminate\Support\ServiceProvider;

use Modules\Services\Repositories\Services\ServicesRepositoryInterface;
use Modules\Services\Repositories\ServiceTime\ServiceTimeRepository;
use Modules\Services\Repositories\ServiceTime\ServiceTimeRepositoryInterface;
use Modules\Services\Repositories\Services\ServicesRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ServicesRepositoryInterface::class,ServicesRepository::class);
        $this->app->singleton(ServiceTimeRepositoryInterface::class, ServiceTimeRepository::class);
    }
}