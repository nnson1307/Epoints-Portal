<?php

namespace Modules\ManagerWork\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ManagerWork\Repositories\Member\MemberRepository;
use Modules\ManagerWork\Repositories\Report\ReportRepository;
use Modules\ManagerWork\Repositories\Project\ProjectRepository;
use Modules\ManagerWork\Repositories\Departments\DepartmentsRepo;
use Modules\ManagerWork\Repositories\TypeWork\TypeWorkRepository;
use Modules\ManagerWork\Repositories\ManageConfig\ManageConfigRepo;
use Modules\ManagerWork\Repositories\ManageTags\ManageTagsRepository;
use Modules\ManagerWork\Repositories\Departments\DepartmentsInterface;
use Modules\ManagerWork\Repositories\Member\MemberRepositoryInterface;
use Modules\ManagerWork\Repositories\Report\ReportRepositoryInterface;
use Modules\ManagerWork\Repositories\ManagerWork\ManagerWorkRepository;
use Modules\ManagerWork\Repositories\ManagerWorkTag\ManagerWorkTagRepo;
use Modules\ManagerWork\Repositories\Project\ProjectRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageStatus\ManageStatusRepository;
use Modules\ManagerWork\Repositories\TypeWork\TypeWorkRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageRedmind\ManageRedmindRepository;
use Modules\ManagerWork\Repositories\StaffOverView\StaffOverViewRepository;
use Modules\ManagerWork\Repositories\ManagerWorkTag\ManagerWorkTagInterface;
use Modules\ManagerWork\Repositories\ManageTags\ManageTagsRepositoryInterface;
use Modules\ManagerWork\Repositories\ManagerWorkSupport\ManagerWorkSupportRepo;
use Modules\ManagerWork\Repositories\ManagerWork\ManagerWorkRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageConfig\ManageConfigRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageStatus\ManageStatusRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageRedmind\ManageRedmindRepositoryInterface;
use Modules\ManagerWork\Repositories\ManagerWorkSupport\ManagerWorkSupportInterface;
use Modules\ManagerWork\Repositories\StaffOverView\StaffOverViewRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->singleton(TypeWorkRepositoryInterface::class, TypeWorkRepository::class);
        $this->app->singleton(ManageRedmindRepositoryInterface::class, ManageRedmindRepository::class);
        $this->app->singleton(ManagerWorkRepositoryInterface::class, ManagerWorkRepository::class);
        $this->app->singleton(ManageStatusRepositoryInterface::class, ManageStatusRepository::class);
        $this->app->singleton(ManageTagsRepositoryInterface::class, ManageTagsRepository::class);
        $this->app->singleton(ManageConfigRepositoryInterface::class,ManageConfigRepo::class);
        $this->app->singleton(ReportRepositoryInterface::class,ReportRepository::class);
        $this->app->singleton(StaffOverViewRepositoryInterface::class,StaffOverViewRepository::class);
        $this->app->singleton(ManagerWorkSupportInterface::class, ManagerWorkSupportRepo::class);
        $this->app->singleton(ManagerWorkTagInterface::class, ManagerWorkTagRepo::class);
        $this->app->singleton(DepartmentsInterface::class, DepartmentsRepo::class);
        $this->app->singleton(MemberRepositoryInterface::class, MemberRepository::class);

    }
}