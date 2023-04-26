<?php

namespace Modules\Warranty\Providers;

use Modules\Warranty\Repository\Maintenance\MaintenanceRepo;
use Modules\Warranty\Repository\Maintenance\MaintenanceRepoInterface;
use Modules\Warranty\Repository\MaintenanceCostType\MaintenanceCostTypeRepo;
use Modules\Warranty\Repository\MaintenanceCostType\MaintenanceCostTypeRepoInterface;
use Modules\Warranty\Repository\Repair\RepairRepo;
use Modules\Warranty\Repository\Repair\RepairRepoInterface;
use Modules\Warranty\Repository\ReportRepairCost\ReportRepairCostRepo;
use Modules\Warranty\Repository\ReportRepairCost\ReportRepairCostRepoInterface;
use Modules\Warranty\Repository\WarrantyCard\WarrantyCardRepo;
use Modules\Warranty\Repository\WarrantyCard\WarrantyCardRepoInterface;
use Modules\Warranty\Repository\WarrantyPackage\WarrantyPackageRepo;
use Modules\Warranty\Repository\WarrantyPackage\WarrantyPackageRepoInterface;

class RepositoryServiceProvider extends WarrantyServiceProvider
{
    public function register()
    {
        $this->app->singleton(WarrantyPackageRepoInterface::class, WarrantyPackageRepo::class);
        $this->app->singleton(MaintenanceRepoInterface::class, MaintenanceRepo::class);
        $this->app->singleton(WarrantyCardRepoInterface::class, WarrantyCardRepo::class);
        $this->app->singleton(MaintenanceCostTypeRepoInterface::class, MaintenanceCostTypeRepo::class);
        $this->app->singleton(RepairRepoInterface::class, RepairRepo::class);
        $this->app->singleton(ReportRepairCostRepoInterface::class, ReportRepairCostRepo::class);
    }
}