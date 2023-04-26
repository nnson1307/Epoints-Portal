<?php

namespace Modules\Kpi\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Kpi\Repositories\BudgetMarketing\BudgetMarketingRepo;
use Modules\Kpi\Repositories\BudgetMarketing\BudgetMarketingRepoInterface;
use Modules\Kpi\Repositories\CalculateKpi\CalculateKpiRepo;
use Modules\Kpi\Repositories\CalculateKpi\CalculateKpiRepoInterface;
use Modules\Kpi\Repositories\Criteria\KpiCriteriaRepoInterface;
use Modules\Kpi\Repositories\Criteria\KpiCriteriaRepo;
use Modules\Kpi\Repositories\Note\KpiNoteRepoInterface;
use Modules\Kpi\Repositories\Note\KpiNoteRepo;
use Modules\Kpi\Repositories\Report\_ReportRepo;
use Modules\Kpi\Repositories\Report\_ReportRepoInterface;
use Modules\Kpi\Repositories\Report\ReportRepo;
use Modules\Kpi\Repositories\Report\ReportRepoInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // HaoNMN
        $this->app->singleton(KpiCriteriaRepoInterface::class, KpiCriteriaRepo::class);
        $this->app->singleton(KpiNoteRepoInterface::class, KpiNoteRepo::class);

        $this->app->singleton(CalculateKpiRepoInterface::class, CalculateKpiRepo::class);

        $this->app->singleton(BudgetMarketingRepoInterface::class, BudgetMarketingRepo::class);
        $this->app->singleton(ReportRepoInterface::class,ReportRepo::class);

        $this->app->singleton(_ReportRepoInterface::class, _ReportRepo::class);
    }
}