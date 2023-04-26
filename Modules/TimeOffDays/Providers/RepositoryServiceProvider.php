<?php

namespace Modules\TimeOffDays\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\TimeOffDays\Repositories\TimeOffDays\TimeOffDaysRepository;
use Modules\TimeOffDays\Repositories\TimeOffDays\TimeOffDaysRepositoryInterface;
use Modules\TimeOffDays\Repositories\Staffs\StaffsRepository;
use Modules\TimeOffDays\Repositories\Staffs\StaffsRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepository;
use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysTotal\TimeOffDaysTotalRepository;
use Modules\TimeOffDays\Repositories\TimeOffDaysTotal\TimeOffDaysTotalRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeWorkingStaffs\TimeWorkingStaffsRepository;
use Modules\TimeOffDays\Repositories\TimeWorkingStaffs\TimeWorkingStaffsRepositoryInterface;
use Modules\TimeOffDays\Repositories\SFShifts\SFShiftsRepository;
use Modules\TimeOffDays\Repositories\SFShifts\SFShiftsRepositoryInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysLog\TimeOffDaysLogRepository;
use Modules\TimeOffDays\Repositories\TimeOffDaysLog\TimeOffDaysLogRepositoryInterface;

use Modules\TimeOffDays\Repositories\TimeOffTypeOption\TimeOffTypeOptionRepository;
use Modules\TimeOffDays\Repositories\TimeOffTypeOption\TimeOffTypeOptionRepositoryInterface;

use Modules\TimeOffDays\Repositories\StaffTitle\StaffTitleRepository;
use Modules\TimeOffDays\Repositories\StaffTitle\StaffTitleRepositoryInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove\TimeOffDaysConfigApproveRepository;
use Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove\TimeOffDaysConfigApproveRepositoryInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysFiles\TimeOffDaysFilesRepository;
use Modules\TimeOffDays\Repositories\TimeOffDaysFiles\TimeOffDaysFilesRepositoryInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysShifts\TimeOffDaysShiftsRepository;
use Modules\TimeOffDays\Repositories\TimeOffDaysShifts\TimeOffDaysShiftsRepositoryInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysTotalLog\TimeOffDaysTotalLogRepository;
use Modules\TimeOffDays\Repositories\TimeOffDaysTotalLog\TimeOffDaysTotalLogRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TimeOffDaysRepositoryInterface::class, TimeOffDaysRepository::class);
        $this->app->singleton(StaffsRepositoryInterface::class, StaffsRepository::class);
        $this->app->singleton(TimeOffTypeRepositoryInterface::class, TimeOffTypeRepository::class);
        $this->app->singleton(TimeOffDaysTotalRepositoryInterface::class, TimeOffDaysTotalRepository::class);
        $this->app->singleton(TimeWorkingStaffsRepositoryInterface::class, TimeWorkingStaffsRepository::class);
        $this->app->singleton(SFShiftsRepositoryInterface::class, SFShiftsRepository::class);
        $this->app->singleton(TimeOffDaysLogRepositoryInterface::class, TimeOffDaysLogRepository::class);
        $this->app->singleton(TimeOffTypeOptionRepositoryInterface::class, TimeOffTypeOptionRepository::class);
        $this->app->singleton(StaffTitleRepositoryInterface::class, StaffTitleRepository::class);
        $this->app->singleton(TimeOffDaysConfigApproveRepositoryInterface::class, TimeOffDaysConfigApproveRepository::class);
        $this->app->singleton(TimeOffDaysFilesRepositoryInterface::class, TimeOffDaysFilesRepository::class);
        $this->app->singleton(TimeOffDaysShiftsRepositoryInterface::class, TimeOffDaysShiftsRepository::class);
        $this->app->singleton(TimeOffDaysTotalLogRepositoryInterface::class, TimeOffDaysTotalLogRepository::class);
        
    }
}