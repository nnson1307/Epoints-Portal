<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Promotion\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Promotion\Repositories\Promotion\PromotionRepo;
use Modules\Promotion\Repositories\Promotion\PromotionRepoInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PromotionRepoInterface::class, PromotionRepo::class);
    }
}