<?php
namespace Modules\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Customer\Repositories\CustomerInfoTemp\CustomerInfoTempRepo;
use Modules\Customer\Repositories\CustomerInfoTemp\CustomerInfoTempRepoInterface;
use Modules\Customer\Repositories\CustomerInfoType\CustomerInfoTypeRepo;
use Modules\Customer\Repositories\CustomerInfoType\CustomerInfoTypeRepoInterface;
use Modules\Customer\Repositories\CustomerRemindUse\CustomerRemindUseRepo;
use Modules\Customer\Repositories\CustomerRemindUse\CustomerRemindUseRepoInterface;

/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 29/03/2018
 * Time: 1:46 SA
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CustomerInfoTypeRepoInterface::class, CustomerInfoTypeRepo::class);
        $this->app->singleton(CustomerInfoTempRepoInterface::class, CustomerInfoTempRepo::class);
        $this->app->singleton(CustomerRemindUseRepoInterface::class, CustomerRemindUseRepo::class);
    }
}