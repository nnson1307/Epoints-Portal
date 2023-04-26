<?php

namespace Modules\Ticket\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Ticket\Repositories\Queue\QueueRepository;
use Modules\Ticket\Repositories\Queue\QueueRepositoryInterface;
use Modules\Ticket\Repositories\Staff\StaffRepository;
use Modules\Ticket\Repositories\Staff\StaffRepositoryInterface;
use Modules\Ticket\Repositories\QueueStaff\QueueStaffRepository;
use Modules\Ticket\Repositories\QueueStaff\QueueStaffRepositoryInterface;
use Modules\Ticket\Repositories\RequestGroup\RequestGroupRepository;
use Modules\Ticket\Repositories\RequestGroup\RequestGroupRepositoryInterface;
use Modules\Ticket\Repositories\Request\RequestRepository;
use Modules\Ticket\Repositories\Request\RequestRepositoryInterface;
use Modules\Ticket\Repositories\RoleQueue\RoleQueueRepository;
use Modules\Ticket\Repositories\RoleQueue\RoleQueueRepositoryInterface;
use Modules\Ticket\Repositories\Role\RoleRepository;
use Modules\Ticket\Repositories\Role\RoleRepositoryInterface;
use Modules\Ticket\Repositories\TicketStatus\TicketStatusRepository;
use Modules\Ticket\Repositories\TicketStatus\TicketStatusRepositoryInterface;
use Modules\Ticket\Repositories\TicketAction\TicketActionRepository;
use Modules\Ticket\Repositories\TicketAction\TicketActionRepositoryInterface;
use Modules\Ticket\Repositories\TicketRoleActionMap\TicketRoleActionMapRepository;
use Modules\Ticket\Repositories\TicketRoleActionMap\TicketRoleActionMapRepositoryInterface;
use Modules\Ticket\Repositories\TicketRoleStatusMap\TicketRoleStatusMapRepository;
use Modules\Ticket\Repositories\TicketRoleStatusMap\TicketRoleStatusMapRepositoryInterface;
use Modules\Ticket\Repositories\Ticket\TicketRepository;
use Modules\Ticket\Repositories\Ticket\TicketRepositoryInterface;
use Modules\Ticket\Repositories\Province\ProvinceRepository;
use Modules\Ticket\Repositories\Province\ProvinceRepositoryInterface;
use Modules\Ticket\Repositories\Action\ActionRepository;
use Modules\Ticket\Repositories\Action\ActionRepositoryInterface;
use Modules\Ticket\Repositories\Alert\AlertRepository;
use Modules\Ticket\Repositories\Alert\AlertRepositoryInterface;
use Modules\Ticket\Repositories\Upload\UploadRepository;
use Modules\Ticket\Repositories\Upload\UploadRepositoryInterface;
use Modules\Ticket\Repositories\TicketFile\TicketFileRepository;
use Modules\Ticket\Repositories\TicketFile\TicketFileRepositoryInterface;
use Modules\Ticket\Repositories\TicketProcessor\TicketProcessorRepository;
use Modules\Ticket\Repositories\TicketProcessor\TicketProcessorRepositoryInterface;
use Modules\Ticket\Repositories\TicketOperater\TicketOperaterRepository;
use Modules\Ticket\Repositories\TicketOperater\TicketOperaterRepositoryInterface;
use Modules\Ticket\Repositories\TicketQueueMap\TicketQueueMapRepository;
use Modules\Ticket\Repositories\TicketQueueMap\TicketQueueMapRepositoryInterface;
use Modules\Ticket\Repositories\StaffQueueMap\StaffQueueMapRepository;
use Modules\Ticket\Repositories\StaffQueueMap\StaffQueueMapRepositoryInterface;
use Modules\Ticket\Repositories\TicketRating\TicketRatingRepository;
use Modules\Ticket\Repositories\TicketRating\TicketRatingRepositoryInterface;
use Modules\Ticket\Repositories\Material\MaterialRepository;
use Modules\Ticket\Repositories\Material\MaterialRepositoryInterface;
use Modules\Ticket\Repositories\MaterialDetail\MaterialDetailRepository;
use Modules\Ticket\Repositories\MaterialDetail\MaterialDetailRepositoryInterface;
use Modules\Ticket\Repositories\Acceptance\AcceptanceRepository;
use Modules\Ticket\Repositories\Acceptance\AcceptanceRepositoryInterface;
use Modules\Ticket\Repositories\RoleGroup\RoleGroupRepository;
use Modules\Ticket\Repositories\RoleGroup\RoleGroupRepositoryInterface;
use Modules\Ticket\Repositories\Refund\RefundRepository;
use Modules\Ticket\Repositories\Refund\RefundRepositoryInterface;
// use Modules\Ticket\Repositories\AcceptanceDetail\AcceptanceDetailRepository;
// use Modules\Ticket\Repositories\AcceptanceDetail\AcceptanceDetailRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(CodeGeneratorRepositoryInterface::class, CodeGeneratorRepository::class);
        $this->app->singleton(QueueRepositoryInterface::class, QueueRepository::class);
        $this->app->singleton(QueueStaffRepositoryInterface::class, QueueStaffRepository::class);
        $this->app->singleton(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->singleton(RequestGroupRepositoryInterface::class, RequestGroupRepository::class);
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->singleton(RoleQueueRepositoryInterface::class, RoleQueueRepository::class);
        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(TicketStatusRepositoryInterface::class, TicketStatusRepository::class);
        $this->app->singleton(TicketActionRepositoryInterface::class, TicketActionRepository::class);
        $this->app->singleton(TicketRoleActionMapRepositoryInterface::class, TicketRoleActionMapRepository::class);
        $this->app->singleton(TicketRoleStatusMapRepositoryInterface::class, TicketRoleStatusMapRepository::class);
        $this->app->singleton(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->singleton(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->singleton(ActionRepositoryInterface::class, ActionRepository::class);
        $this->app->singleton(AlertRepositoryInterface::class, AlertRepository::class);
        $this->app->singleton(UploadRepositoryInterface::class, UploadRepository::class);
        $this->app->singleton(TicketFileRepositoryInterface::class, TicketFileRepository::class);
        $this->app->singleton(TicketProcessorRepositoryInterface::class, TicketProcessorRepository::class);
        $this->app->singleton(TicketOperaterRepositoryInterface::class, TicketOperaterRepository::class);
        $this->app->singleton(TicketQueueMapRepositoryInterface::class, TicketQueueMapRepository::class);
        $this->app->singleton(StaffQueueMapRepositoryInterface::class, StaffQueueMapRepository::class);
        $this->app->singleton(TicketRatingRepositoryInterface::class, TicketRatingRepository::class);
        $this->app->singleton(MaterialRepositoryInterface::class, MaterialRepository::class);
        $this->app->singleton(MaterialDetailRepositoryInterface::class, MaterialDetailRepository::class);
        $this->app->singleton(AcceptanceRepositoryInterface::class, AcceptanceRepository::class);
        $this->app->singleton(RoleGroupRepositoryInterface::class, RoleGroupRepository::class);
        $this->app->singleton(RefundRepositoryInterface::class, RefundRepository::class);
        // $this->app->singleton(AcceptanceDetailRepositoryInterface::class, AcceptanceDetailRepository::class);
    }
}