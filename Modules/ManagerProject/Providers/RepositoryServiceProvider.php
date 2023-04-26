<?php

namespace Modules\ManagerProject\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\ManagerProject\Repositories\Contract\ContractRepository;
use Modules\ManagerProject\Repositories\Contract\ContractRepositoryInterface;
use Modules\ManagerProject\Repositories\ManageConfig\ManageConfigRepo;
use Modules\ManagerProject\Repositories\ManageConfig\ManageConfigRepositoryInterface;
use Modules\ManagerProject\Repositories\ManageHistory\ManageHistoryRepo;
use Modules\ManagerProject\Repositories\ManageHistory\ManageHistoryRepoInterface;
use Modules\ManagerProject\Repositories\ManagePhase\ManagePhaseInterfaceRepository;
use Modules\ManagerProject\Repositories\ManagePhase\ManagePhaseRepository;
use Modules\ManagerProject\Repositories\ManageProjectComment\ManageProjectCommentRepository;
use Modules\ManagerProject\Repositories\ManageProjectComment\ManageProjectCommentRepositoryInterface;
use Modules\ManagerProject\Repositories\ManageProjectContact\ManageProjectContactRepository;
use Modules\ManagerProject\Repositories\ManageProjectContact\ManageProjectContactRepositoryInterface;
use Modules\ManagerProject\Repositories\ManageProjectPhare\ManageProjectPhareRepository;
use Modules\ManagerProject\Repositories\ManageProjectPhare\ManageProjectPhareRepositoryInterface;
use Modules\ManagerProject\Repositories\ManageProjectStaff\ManageProjectStaffRepository;
use Modules\ManagerProject\Repositories\ManageProjectStaff\ManageProjectStaffRepositoryInterface;
use Modules\ManagerProject\Repositories\ManagerDocument\ManagerDocumentRepository;
use Modules\ManagerProject\Repositories\ManagerDocument\ManagerDocumentRepositoryInterface;
use Modules\ManagerProject\Repositories\ManagerWork\ManagerWorkRepository;
use Modules\ManagerProject\Repositories\ManagerWork\ManagerWorkRepositoryInterface;
use Modules\ManagerProject\Repositories\Member\MemberRepository;
use Modules\ManagerProject\Repositories\Member\MemberRepositoryInterface;
use Modules\ManagerProject\Repositories\Project\ProjectRepository;
use Modules\ManagerProject\Repositories\Project\ProjectRepositoryInterface;
use Modules\ManagerProject\Repositories\ProjectOverView\ProjectOverViewRepositoryInterface;
use Modules\ManagerProject\Repositories\ProjectOverView\ProjectOverViewRepository;
use Modules\ManagerProject\Repositories\Remind\RemindRepository;
use Modules\ManagerProject\Repositories\Remind\RemindRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProjectRepositoryInterface::class,ProjectRepository::class);
        $this->app->singleton(ManageConfigRepositoryInterface::class,ManageConfigRepo::class);
        $this->app->singleton(ManageHistoryRepoInterface::class,ManageHistoryRepo::class);
        $this->app->singleton(ManagerDocumentRepositoryInterface::class,ManagerDocumentRepository::class);
        $this->app->singleton(ManagerWorkRepositoryInterface::class,ManagerWorkRepository::class);
        $this->app->singleton(MemberRepositoryInterface::class,MemberRepository::class);
        $this->app->singleton(ProjectOverViewRepositoryInterface::class,ProjectOverViewRepository::class);
        $this->app->singleton(ContractRepositoryInterface::class,ContractRepository::class);
        $this->app->singleton(ManageProjectContactRepositoryInterface::class,ManageProjectContactRepository::class);
        $this->app->singleton(ManageProjectCommentRepositoryInterface::class,ManageProjectCommentRepository::class);
        $this->app->singleton(RemindRepositoryInterface::class,RemindRepository::class);
        $this->app->singleton(ManageProjectPhareRepositoryInterface::class,ManageProjectPhareRepository::class);
        $this->app->singleton(ManageProjectStaffRepositoryInterface::class,ManageProjectStaffRepository::class);
        $this->app->singleton(ManagePhaseInterfaceRepository::class,ManagePhaseRepository::class);
    }
}