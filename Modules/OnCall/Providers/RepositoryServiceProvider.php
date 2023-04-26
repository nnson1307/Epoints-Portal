<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\OnCall\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\OnCall\Repositories\Extension\ExtensionRepo;
use Modules\OnCall\Repositories\Extension\ExtensionRepoInterface;
use Modules\OnCall\Repositories\History\HistoryRepo;
use Modules\OnCall\Repositories\History\HistoryRepoInterface;
use Modules\OnCall\Repositories\ReportOverview\ReportOverviewRepo;
use Modules\OnCall\Repositories\ReportOverview\ReportOverviewRepoInterface;
use Modules\OnCall\Repositories\ReportStaff\ReportStaffRepo;
use Modules\OnCall\Repositories\ReportStaff\ReportStaffRepoInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ExtensionRepoInterface::class, ExtensionRepo::class);
        $this->app->singleton(HistoryRepoInterface::class, HistoryRepo::class);
        $this->app->singleton(ReportStaffRepoInterface::class, ReportStaffRepo::class);
        $this->app->singleton(ReportOverviewRepoInterface::class, ReportOverviewRepo::class);
    }
}