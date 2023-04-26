<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Contract\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Contract\Repositories\Browse\BrowseRepo;
use Modules\Contract\Repositories\Browse\BrowseRepoInterface;
use Modules\Contract\Repositories\ContractAnnex\ContractAnnexRepo;
use Modules\Contract\Repositories\ContractAnnex\ContractAnnexRepoInterface;
use Modules\Contract\Repositories\ContractCare\ContractCareRepo;
use Modules\Contract\Repositories\ContractCare\ContractCareRepoInterface;
use Modules\Contract\Repositories\ContractCategories\ContractCategoryRepo;
use Modules\Contract\Repositories\ContractCategories\ContractCategoryRepoInterface;
use Modules\Contract\Repositories\Contract\ContractRepo;
use Modules\Contract\Repositories\Contract\ContractRepoInterface;
use Modules\Contract\Repositories\ContractFile\ContractFileRepo;
use Modules\Contract\Repositories\ContractFile\ContractFileRepoInterface;
use Modules\Contract\Repositories\ContractGoods\ContractGoodsRepo;
use Modules\Contract\Repositories\ContractGoods\ContractGoodsRepoInterface;
use Modules\Contract\Repositories\ContractReceipt\ContractReceiptRepo;
use Modules\Contract\Repositories\ContractReceipt\ContractReceiptRepoInterface;
use Modules\Contract\Repositories\ContractSpend\ContractSpendRepo;
use Modules\Contract\Repositories\ContractSpend\ContractSpendRepoInterface;
use Modules\Contract\Repositories\ExpectedRevenue\ExpectedRevenueRepo;
use Modules\Contract\Repositories\ExpectedRevenue\ExpectedRevenueRepoInterface;
use Modules\Contract\Repositories\ReportContractCare\ReportContractCareRepo;
use Modules\Contract\Repositories\ReportContractCare\ReportContractCareRepoInterface;
use Modules\Contract\Repositories\ReportContractDetail\ReportContractDetailRepo;
use Modules\Contract\Repositories\ReportContractDetail\ReportContractDetailRepoInterface;
use Modules\Contract\Repositories\ReportContractOverview\ReportContractOverviewRepo;
use Modules\Contract\Repositories\ReportContractOverview\ReportContractOverViewRepoInterface;
use Modules\Contract\Repositories\ReportContractRevenue\ReportContractRevenueRepo;
use Modules\Contract\Repositories\ReportContractRevenue\ReportContractRevenueRepoInterface;
use Modules\Contract\Repositories\RoleData\ContractRoleDataRepo;
use Modules\Contract\Repositories\RoleData\ContractRoleDataRepoInterface;
use Modules\Contract\Repositories\Vat\VatRepo;
use Modules\Contract\Repositories\Vat\VatRepoInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ContractCategoryRepoInterface::class, ContractCategoryRepo::class);
        $this->app->singleton(ContractRepoInterface::class, ContractRepo::class);
        $this->app->singleton(ExpectedRevenueRepoInterface::class, ExpectedRevenueRepo::class);
        $this->app->singleton(ContractReceiptRepoInterface::class, ContractReceiptRepo::class);
        $this->app->singleton(ContractSpendRepoInterface::class, ContractSpendRepo::class);
        $this->app->singleton(ContractFileRepoInterface::class, ContractFileRepo::class);
        $this->app->singleton(ContractGoodsRepoInterface::class, ContractGoodsRepo::class);
        $this->app->singleton(ContractRoleDataRepoInterface::class, ContractRoleDataRepo::class);
        $this->app->singleton(ContractAnnexRepoInterface::class, ContractAnnexRepo::class);
        $this->app->singleton(ContractCareRepoInterface::class, ContractCareRepo::class);
        $this->app->singleton(ReportContractCareRepoInterface::class, ReportContractCareRepo::class);
        $this->app->singleton(BrowseRepoInterface::class, BrowseRepo::class);
        $this->app->singleton(ReportContractOverViewRepoInterface::class, ReportContractOverviewRepo::class);
        $this->app->singleton(ReportContractDetailRepoInterface::class, ReportContractDetailRepo::class);
        $this->app->singleton(ReportContractRevenueRepoInterface::class, ReportContractRevenueRepo::class);
        $this->app->singleton(VatRepoInterface::class, VatRepo::class);
    }
}