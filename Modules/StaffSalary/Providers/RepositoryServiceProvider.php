<?php

namespace Modules\StaffSalary\Providers;

use Modules\StaffSalary\Repositories\ReportBudgetBranch\ReportBudgetBranchRepo;
use Modules\StaffSalary\Repositories\ReportBudgetBranch\ReportBudgetBranchRepoInterface;
use Modules\StaffSalary\Repositories\StaffHoliday\StaffHolidayRepoInterface;
use Modules\StaffSalary\Repositories\StaffHoliday\StaffHolidayRepo;
use Modules\StaffSalary\Repositories\StaffSalary\StaffSalaryRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalary\StaffSalaryRepo;
use Modules\StaffSalary\Repositories\SalaryAllowance\SalaryAllowanceRepoInterface;
use Modules\StaffSalary\Repositories\SalaryAllowance\SalaryAllowanceRepo;
use Modules\StaffSalary\Repositories\SalaryBonusMinus\SalaryBonusMinusRepoInterface;
use Modules\StaffSalary\Repositories\SalaryBonusMinus\SalaryBonusMinusRepo;
use Modules\StaffSalary\Repositories\StaffSalaryPayPeriod\StaffSalaryPayPeriodRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryPayPeriod\StaffSalaryPayPeriodRepo;
use Modules\StaffSalary\Repositories\StaffSalaryAttribute\StaffSalaryAttributeRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryAttribute\StaffSalaryAttributeRepo;
use Modules\StaffSalary\Repositories\StaffSalaryConfig\StaffSalaryConfigRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryConfig\StaffSalaryConfigRepo;
use Modules\StaffSalary\Repositories\StaffSalaryDetail\StaffSalaryDetailRepoInterface;
use Modules\StaffSalary\Repositories\StaffSalaryDetail\StaffSalaryDetailRepo;
use Illuminate\Support\ServiceProvider;
use Modules\StaffSalary\Repositories\Template\TemplateRepo;
use Modules\StaffSalary\Repositories\Template\TemplateRepoInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StaffHolidayRepoInterface::class, StaffHolidayRepo::class);
        $this->app->singleton(StaffSalaryRepoInterface::class, StaffSalaryRepo::class);
        $this->app->singleton(SalaryAllowanceRepoInterface::class, SalaryAllowanceRepo::class);
        $this->app->singleton(SalaryBonusMinusRepoInterface::class, SalaryBonusMinusRepo::class);
        $this->app->singleton(StaffSalaryPayPeriodRepoInterface::class, StaffSalaryPayPeriodRepo::class);
        $this->app->singleton(StaffSalaryAttributeRepoInterface::class, StaffSalaryAttributeRepo::class);
        $this->app->singleton(StaffSalaryConfigRepoInterface::class, StaffSalaryConfigRepo::class);
        $this->app->singleton(StaffSalaryDetailRepoInterface::class, StaffSalaryDetailRepo::class);
        $this->app->singleton(ReportBudgetBranchRepoInterface::class, ReportBudgetBranchRepo::class);
        $this->app->singleton(TemplateRepoInterface::class, TemplateRepo::class);
    }
}