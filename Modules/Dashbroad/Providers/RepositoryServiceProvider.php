<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Dashbroad\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Dashbroad\Repositories\DashBoardConfig\DashBoardConfigRepo;
use Modules\Dashbroad\Repositories\DashBoardConfig\DashBoardConfigRepoInterface;
use Modules\Dashbroad\Repositories\DashbroadRepository;
use Modules\Dashbroad\Repositories\DashbroadRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DashbroadRepositoryInterface::class, DashbroadRepository::class);
        $this->app->singleton(DashBoardConfigRepoInterface::class, DashBoardConfigRepo::class);
    }
}