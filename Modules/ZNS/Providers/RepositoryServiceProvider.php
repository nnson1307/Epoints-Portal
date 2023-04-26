<?php

namespace Modules\ZNS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ZNS\Repositories\Campaign\CampaignRepository;
use Modules\ZNS\Repositories\Campaign\CampaignRepositoryInterface;
use Modules\ZNS\Repositories\CampaignFollower\CampaignFollowerRepository;
use Modules\ZNS\Repositories\CampaignFollower\CampaignFollowerRepositoryInterface;
use Modules\ZNS\Repositories\Config\ConfigRepository;
use Modules\ZNS\Repositories\Config\ConfigRepositoryInterface;
use Modules\ZNS\Repositories\Template\TemplateRepository;
use Modules\ZNS\Repositories\Template\TemplateRepositoryInterface;
use Modules\ZNS\Repositories\Params\ParamsRepository;
use Modules\ZNS\Repositories\Params\ParamsRepositoryInterface;
use Modules\ZNS\Repositories\CustomerCare\CustomerCareRepository;
use Modules\ZNS\Repositories\CustomerCare\CustomerCareRepositoryInterface;




class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CampaignRepositoryInterface::class, CampaignRepository::class);
        $this->app->singleton(CampaignFollowerRepositoryInterface::class, CampaignFollowerRepository::class);
        $this->app->singleton(ConfigRepositoryInterface::class, ConfigRepository::class);
        $this->app->singleton(TemplateRepositoryInterface::class, TemplateRepository::class);
        $this->app->singleton(ParamsRepositoryInterface::class, ParamsRepository::class);
        $this->app->singleton(CustomerCareRepositoryInterface::class, CustomerCareRepository::class);
    }
}