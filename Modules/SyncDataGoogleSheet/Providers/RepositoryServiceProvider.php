<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\SyncDataGoogleSheet\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\SyncDataGoogleSheet\Repositories\SyncDataGoogleSheetRepo;
use Modules\SyncDataGoogleSheet\Repositories\SyncDataGoogleSheetRepoInterface;



class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SyncDataGoogleSheetRepoInterface::class, SyncDataGoogleSheetRepo::class);
    }
}