<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\CustomerLead\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\CustomerLead\Repositories\Tag\TagRepo;
use Modules\CustomerLead\Repositories\Report\ReportRepo;
use Modules\CustomerLead\Repositories\Tag\TagRepoInterface;
use Modules\CustomerLead\Repositories\Pipeline\PipelineRepo;
use Modules\CustomerLead\Repositories\Report\ReportRepoInterface;
use Modules\CustomerLead\Repositories\CustomerLog\CustomerLogRepo;
use Modules\CustomerLead\Repositories\CustomerDeal\CustomerDealRepo;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepo;
use Modules\CustomerLead\Repositories\Pipeline\PipelineRepoInterface;
use Modules\CustomerLead\Repositories\CustomerLog\CustomerLogRepoInterface;
use Modules\CustomerLead\Repositories\ConfigSourceLead\ConfigSourceLeadRepo;
use Modules\CustomerLead\Repositories\PipelineCategory\PipelineCategoryRepo;
use Modules\CustomerLead\Repositories\CustomerDeal\CustomerDealRepoInterface;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepoInterface;
use Modules\CustomerLead\Repositories\ConfigSourceLead\ConfigSourceLeadRepoInterface;
use Modules\CustomerLead\Repositories\PipelineCategory\PipelineCategoryRepoInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PipelineCategoryRepoInterface::class, PipelineCategoryRepo::class);
        $this->app->singleton(CustomerLeadRepoInterface::class, CustomerLeadRepo::class);
        $this->app->singleton(PipelineRepoInterface::class, PipelineRepo::class);
        $this->app->singleton(TagRepoInterface::class, TagRepo::class);
        $this->app->singleton(CustomerDealRepoInterface::class, CustomerDealRepo::class);
        $this->app->singleton(ReportRepoInterface::class, ReportRepo::class);
        $this->app->singleton(CustomerLogRepoInterface::class, CustomerLogRepo::class);
        $this->app->singleton(ConfigSourceLeadRepoInterface::class,ConfigSourceLeadRepo::class);

    }
}