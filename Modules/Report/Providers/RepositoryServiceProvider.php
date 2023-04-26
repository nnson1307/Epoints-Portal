<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Report\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Report\Repository\BaseOnPostcode\BaseOnPostcodeRepo;
use Modules\Report\Repository\BaseOnPostcode\BaseOnPostcodeRepoInterface;
use Modules\Report\Repository\CampaignOverviewReport\CampaignOverviewReportRepo;
use Modules\Report\Repository\CampaignOverviewReport\CampaignOverviewReportRepoInterface;
use Modules\Report\Repository\CustomerByViewPurchase\CustomerByViewPurchaseRepo;
use Modules\Report\Repository\CustomerByViewPurchase\CustomerByViewPurchaseRepoInterface;
use Modules\Report\Repository\DealCommission\DealCommissionRepo;
use Modules\Report\Repository\DealCommission\DealCommissionRepoInterface;
use Modules\Report\Repository\DebtByBranch\DebtByBranchRepo;
use Modules\Report\Repository\DebtByBranch\DebtByBranchRepoInterface;
use Modules\Report\Repository\PerformanceReport\PerformanceReportRepo;
use Modules\Report\Repository\PerformanceReport\PerformanceReportRepoInterface;
use Modules\Report\Repository\Product\ReportProductRepo;
use Modules\Report\Repository\Product\ReportProductRepoInterface;
use Modules\Report\Repository\ProductCategory\ReportProductCategoryRepo;
use Modules\Report\Repository\ProductCategory\ReportProductCategoryRepoInterface;
use Modules\Report\Repository\ProductInventory\ProductInventoryRepo;
use Modules\Report\Repository\ProductInventory\ProductInventoryRepoInterface;
use Modules\Report\Repository\PurchaseByHour\PurchaseByHourRepo;
use Modules\Report\Repository\PurchaseByHour\PurchaseByHourRepoInterface;
use Modules\Report\Repository\RevenueByBranch\RevenueByBranchRepo;
use Modules\Report\Repository\RevenueByBranch\RevenueByBranchRepoInterface;
use Modules\Report\Repository\RevenueByCustomer\RevenueByCustomerRepo;
use Modules\Report\Repository\RevenueByCustomer\RevenueByCustomerRepoInterface;
use Modules\Report\Repository\RevenueByProduct\RevenueByProductRepo;
use Modules\Report\Repository\RevenueByProduct\RevenueByProductRepoInterface;
use Modules\Report\Repository\RevenueByService\RevenueByServiceRepo;
use Modules\Report\Repository\RevenueByService\RevenueByServiceRepoInterface;
use Modules\Report\Repository\RevenueByServiceCard\RevenueByServiceCardRepo;
use Modules\Report\Repository\RevenueByServiceCard\RevenueByServiceCardRepoInterface;
use Modules\Report\Repository\RevenueByStaff\RevenueByStaffRepo;
use Modules\Report\Repository\RevenueByStaff\RevenueByStaffRepoInterface;
use Modules\Report\Repository\RevenueBySurchargeService\RevenueBySurchargeServiceRepo;
use Modules\Report\Repository\RevenueBySurchargeService\RevenueBySurchargeServiceRepoInterface;
use Modules\Report\Repository\ServiceStaff\ServiceStaffRepo;
use Modules\Report\Repository\ServiceStaff\ServiceStaffRepoInterface;
use Modules\Report\Repository\StaffCommission\StaffCommissionRepo;
use Modules\Report\Repository\StaffCommission\StaffCommissionRepoInterface;
use Modules\Report\Repository\StatisticBranch\StatisticBranchRepo;
use Modules\Report\Repository\StatisticBranch\StatisticBranchRepoInterface;
use Modules\Report\Repository\StatisticCustomer\StatisticCustomerRepo;
use Modules\Report\Repository\StatisticCustomer\StatisticCustomerRepoInterface;
use Modules\Report\Repository\StatisticCustomerAppointment\StatisticCustomerAppointmentRepo;
use Modules\Report\Repository\StatisticCustomerAppointment\StatisticCustomerAppointmentRepoInterface;
use Modules\Report\Repository\StatisticOrder\StatisticOrderRepo;
use Modules\Report\Repository\StatisticOrder\StatisticOrderRepoInterface;
use Modules\Report\Repository\StatisticService\StatisticServiceRepo;
use Modules\Report\Repository\StatisticService\StatisticServiceRepoInterface;
use Modules\Report\Repository\StatisticServiceCard\StatisticServiceCardRepo;
use Modules\Report\Repository\StatisticServiceCard\StatisticServiceCardRepoInterface;
use Modules\Report\Repository\VehicleRegistration\VehicleRegistrationRepo;
use Modules\Report\Repository\VehicleRegistration\VehicleRegistrationRepoInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ReportProductRepoInterface::class, ReportProductRepo::class);
        $this->app->singleton(ReportProductCategoryRepoInterface::class, ReportProductCategoryRepo::class);
        $this->app->singleton(PurchaseByHourRepoInterface::class, PurchaseByHourRepo::class);
        $this->app->singleton(CustomerByViewPurchaseRepoInterface::class, CustomerByViewPurchaseRepo::class);
        $this->app->singleton(BaseOnPostcodeRepoInterface::class, BaseOnPostcodeRepo::class);
        // Report revenue
        $this->app->singleton(RevenueByBranchRepoInterface::class, RevenueByBranchRepo::class);
        $this->app->singleton(RevenueByCustomerRepoInterface::class, RevenueByCustomerRepo::class);
        $this->app->singleton(RevenueByStaffRepoInterface::class, RevenueByStaffRepo::class);
        $this->app->singleton(RevenueByProductRepoInterface::class, RevenueByProductRepo::class);
        $this->app->singleton(RevenueByServiceRepoInterface::class, RevenueByServiceRepo::class);
        $this->app->singleton(RevenueByServiceCardRepoInterface::class, RevenueByServiceCardRepo::class);
        $this->app->singleton(ServiceStaffRepoInterface::class, ServiceStaffRepo::class);
        $this->app->singleton(RevenueBySurchargeServiceRepoInterface::class,RevenueBySurchargeServiceRepo::class);
        // Report debt
        $this->app->singleton(DebtByBranchRepoInterface::class, DebtByBranchRepo::class);
        // Report staff commission
        $this->app->singleton(StaffCommissionRepoInterface::class, StaffCommissionRepo::class);
        // Statistic
        $this->app->singleton(StatisticBranchRepoInterface::class, StatisticBranchRepo::class);
        $this->app->singleton(StatisticServiceCardRepoInterface::class, StatisticServiceCardRepo::class);
        $this->app->singleton(StatisticServiceRepoInterface::class, StatisticServiceRepo::class);
        $this->app->singleton(StatisticCustomerAppointmentRepoInterface::class, StatisticCustomerAppointmentRepo::class);
        $this->app->singleton(StatisticOrderRepoInterface::class, StatisticOrderRepo::class);
        $this->app->singleton(StatisticCustomerRepoInterface::class, StatisticCustomerRepo::class);

        $this->app->singleton(VehicleRegistrationRepoInterface::class, VehicleRegistrationRepo::class);
        $this->app->singleton(DealCommissionRepoInterface::class, DealCommissionRepo::class);
        $this->app->singleton(ProductInventoryRepoInterface::class, ProductInventoryRepo::class);
        $this->app->singleton(CampaignOverviewReportRepoInterface::class, CampaignOverviewReportRepo::class);
        $this->app->singleton(PerformanceReportRepoInterface::class, PerformanceReportRepo::class);
    }
}