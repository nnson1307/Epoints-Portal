<?php

namespace Modules\Shift\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Shift\Repositories\ConfigGeneral\ConfigGeneralRepo;
use Modules\Shift\Repositories\ConfigGeneral\ConfigGeneralRepoInterface;
use Modules\Shift\Repositories\ConfigNoti\ConfigNotiRepository;
use Modules\Shift\Repositories\ConfigNoti\ConfigNotiRepositoryInterface;
use Modules\Shift\Repositories\Recompense\RecompenseRepo;
use Modules\Shift\Repositories\Recompense\RecompenseRepoInterface;
use Modules\Shift\Repositories\Shift\ShiftRepo;
use Modules\Shift\Repositories\Shift\ShiftRepoInterface;
use Modules\Shift\Repositories\Timekeeping\TimekeepingRepo;
use Modules\Shift\Repositories\Timekeeping\TimekeepingRepoIf;
use Modules\Shift\Repositories\TimekeepingConfig\TimekeepingConfigRepo;
use Modules\Shift\Repositories\TimekeepingConfig\TimekeepingConfigRepoInterface;
use Modules\Shift\Repositories\TimeWorkingStaff\TimeWorkingStaffRepo;
use Modules\Shift\Repositories\TimeWorkingStaff\TimeWorkingStaffRepoInterface;
use Modules\Shift\Repositories\Attendances\AttendancesRepoInterface;
use Modules\Shift\Repositories\Attendances\AttendancesRepo;
use Modules\Shift\Repositories\WorkSchedule\WorkScheduleRepo;
use Modules\Shift\Repositories\WorkSchedule\WorkScheduleRepoInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TimeWorkingStaffRepoInterface::class, TimeWorkingStaffRepo::class);
        $this->app->singleton(WorkScheduleRepoInterface::class, WorkScheduleRepo::class);
        $this->app->singleton(ShiftRepoInterface::class, ShiftRepo::class);
        $this->app->singleton(AttendancesRepoInterface::class, AttendancesRepo::class);
        $this->app->singleton(TimekeepingConfigRepoInterface::class, TimekeepingConfigRepo::class);
        $this->app->singleton(TimekeepingRepoIf::class, TimekeepingRepo::class);
        $this->app->singleton(ConfigNotiRepositoryInterface::class,ConfigNotiRepository::class);
        $this->app->singleton(ConfigGeneralRepoInterface::class, ConfigGeneralRepo::class);
        $this->app->singleton(RecompenseRepoInterface::class, RecompenseRepo::class);
    }
}