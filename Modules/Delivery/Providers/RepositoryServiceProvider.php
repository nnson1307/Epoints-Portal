<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Delivery\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Delivery\Repositories\Delivery\DeliveryRepo;
use Modules\Delivery\Repositories\Delivery\DeliveryRepoInterface;
use Modules\Delivery\Repositories\DeliveryCost\DeliveryCostRepo;
use Modules\Delivery\Repositories\DeliveryCost\DeliveryCostRepoInterface;
use Modules\Delivery\Repositories\DeliveryHistory\DeliveryHistoryRepo;
use Modules\Delivery\Repositories\DeliveryHistory\DeliveryHistoryRepoInterface;
use Modules\Delivery\Repositories\UserCarrier\UserCarrierRepo;
use Modules\Delivery\Repositories\UserCarrier\UserCarrierRepoInterface;
use Modules\Delivery\Repositories\PickupAddress\PickupAddressRepo;
use Modules\Delivery\Repositories\PickupAddress\PickupAddressRepoInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DeliveryRepoInterface::class, DeliveryRepo::class);
        $this->app->singleton(UserCarrierRepoInterface::class, UserCarrierRepo::class);
        $this->app->singleton(DeliveryHistoryRepoInterface::class, DeliveryHistoryRepo::class);
        $this->app->singleton(PickupAddressRepoInterface::class, PickupAddressRepo::class);
        $this->app->singleton(DeliveryCostRepoInterface::class, DeliveryCostRepo::class);
    }
}