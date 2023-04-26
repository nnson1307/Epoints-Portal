<?php

namespace Modules\People\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\People\Repositories\People\PeopleRepo;
use Modules\People\Repositories\People\PeopleRepoIf;
use Modules\People\Repositories\PeopleReport\PeopleReportInterface;
use Modules\People\Repositories\PeopleReport\PeopleReportRepo;
use Modules\People\Repositories\PeopleVerify\PeopleVerifyRepo;
use Modules\People\Repositories\PeopleVerify\PeopleVerifyRepoIf;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PeopleRepoIf::class, PeopleRepo::class);
        $this->app->singleton(PeopleVerifyRepoIf::class, PeopleVerifyRepo::class);
        $this->app->singleton(PeopleReportInterface::class, PeopleReportRepo::class);
    }
}