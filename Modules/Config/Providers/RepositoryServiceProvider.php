<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Config\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Config\Repositories\ConfigCustomerParameter\ConfigCustomerParameterRepo;
use Modules\Config\Repositories\ConfigCustomerParameter\ConfigCustomerParameterRepoInterface;
use Modules\Config\Repositories\ConfigRejectOrder\ConfigRejectOrderRepo;
use Modules\Config\Repositories\ConfigRejectOrder\ConfigRejectOrderRepoInterface;
use Modules\Config\Repositories\ConfigReview\ConfigReviewRepo;
use Modules\Config\Repositories\ConfigReview\ConfigReviewRepoInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ConfigReviewRepoInterface::class, ConfigReviewRepo::class);
        $this->app->singleton(ConfigCustomerParameterRepoInterface::class, ConfigCustomerParameterRepo::class);
        $this->app->singleton(ConfigRejectOrderRepoInterface::class, ConfigRejectOrderRepo::class);
    }
}