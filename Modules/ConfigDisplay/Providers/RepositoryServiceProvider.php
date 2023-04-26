<?php

/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\ConfigDisplay\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\ConfigDisplay\Repositories\ConfigDisplayRepo;
use Modules\ConfigDisplay\Repositories\ConfigDisplayDetailRepo;
use Modules\ConfigDisplay\Repositories\ConfigDisplayRepoInterface;
use Modules\ConfigDisplay\Repositories\ConfigDisplayDetailRepoInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ConfigDisplayRepoInterface::class, ConfigDisplayRepo::class);
        $this->app->singleton(ConfigDisplayDetailRepoInterface::class, ConfigDisplayDetailRepo::class);

    }
}
