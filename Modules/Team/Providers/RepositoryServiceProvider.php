<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Team\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Team\Repositories\Company\CompanyRepo;
use Modules\Team\Repositories\Company\CompanyRepoInterface;
use Modules\Team\Repositories\Team\TeamRepo;
use Modules\Team\Repositories\Team\TeamRepoInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TeamRepoInterface::class, TeamRepo::class);
        $this->app->singleton(CompanyRepoInterface::class, CompanyRepo::class);
    }
}