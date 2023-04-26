<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Notification\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Notification\Repositories\Config\ConfigRepo;
use Modules\Notification\Repositories\Config\ConfigRepoInterface;
use Modules\Notification\Repositories\ConfigStaff\ConfigStaffRepo;
use Modules\Notification\Repositories\ConfigStaff\ConfigStaffRepoInterface;
use Modules\Notification\Repositories\Notification\NotificationRepository;
use Modules\Notification\Repositories\Notification\NotificationRepositoryInterface;
use Modules\Notification\Repositories\StaffNotification\StaffNotificationRepo;
use Modules\Notification\Repositories\StaffNotification\StaffNotificationRepoInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ConfigRepoInterface::class, ConfigRepo::class);
        $this->app->singleton(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->singleton(StaffNotificationRepoInterface::class, StaffNotificationRepo::class);
        $this->app->singleton(ConfigStaffRepoInterface::class, ConfigStaffRepo::class);
    }
}