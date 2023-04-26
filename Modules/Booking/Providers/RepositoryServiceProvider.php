<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Booking\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\Booking\Repositories\BannerSlider\BannerSliderRepository;
use Modules\Booking\Repositories\BannerSlider\BannerSliderRepositoryInterface;
use Modules\Booking\Repositories\Branch\BranchRepository;
use Modules\Booking\Repositories\Branch\BranchRepositoryInterface;
use Modules\Booking\Repositories\Customer\CustomerRepository;
use Modules\Booking\Repositories\Customer\CustomerRepositoryInterface;
use Modules\Booking\Repositories\CustomerAppointment\CustomerAppointmentRepository;
use Modules\Booking\Repositories\CustomerAppointment\CustomerAppointmentRepositoryInterface;
use Modules\Booking\Repositories\CustomerAppointmentDetail\CustomerAppointmentDetailRepository;
use Modules\Booking\Repositories\CustomerAppointmentDetail\CustomerAppointmentDetailRepositoryInterface;
use Modules\Booking\Repositories\Loyalty\LoyaltyRepository;
use Modules\Booking\Repositories\Loyalty\LoyaltyRepositoryInterface;
use Modules\Booking\Repositories\Order\OrderRepository;
use Modules\Booking\Repositories\Order\OrderRepositoryInterface;
use Modules\Booking\Repositories\ProductCategory\ProductCategoryRepository;
use Modules\Booking\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Modules\Booking\Repositories\ProductChild\ProductChildRepository;
use Modules\Booking\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Booking\Repositories\SendSms\SendSmsRepository;
use Modules\Booking\Repositories\SendSms\SendSmsRepositoryInterface;
use Modules\Booking\Repositories\Service\ServiceRepository;
use Modules\Booking\Repositories\Service\ServiceRepositoryInterface;
use Modules\Booking\Repositories\ServiceCard\ServiceCardRepository;
use Modules\Booking\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Booking\Repositories\ServiceCategory\ServiceCategoryRepository;
use Modules\Booking\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;
use Modules\Booking\Repositories\SmsConfig\SmsConfigRepository;
use Modules\Booking\Repositories\SmsConfig\SmsConfigRepositoryInterface;
use Modules\Booking\Repositories\SmsLog\SmsLogRepository;
use Modules\Booking\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\Booking\Repositories\SmsProvider\SmsProviderRepository;
use Modules\Booking\Repositories\SmsProvider\SmsProviderRepositoryInterface;
use Modules\Booking\Repositories\SpaInfo\SpaInfoRepository;
use Modules\Booking\Repositories\SpaInfo\SpaInfoRepositoryInterface;
use Modules\Booking\Repositories\Staffs\StaffRepository;
use Modules\Booking\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Booking\Repositories\TimeWork\TimeWorkRepository;
use Modules\Booking\Repositories\TimeWork\TimeWorkRepositoryInterface;
use Modules\Booking\Repositories\Upload\UploadRepo;
use Modules\Booking\Repositories\Upload\UploadRepoInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SpaInfoRepositoryInterface::class, SpaInfoRepository::class);
        $this->app->singleton(TimeWorkRepositoryInterface::class, TimeWorkRepository::class);
        $this->app->singleton(ServiceCategoryRepositoryInterface::class, ServiceCategoryRepository::class);
        $this->app->singleton(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->singleton(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->singleton(ProductChildRepositoryInterface::class, ProductChildRepository::class);
        $this->app->singleton(BranchRepositoryInterface::class, BranchRepository::class);
        $this->app->singleton(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->singleton(BannerSliderRepositoryInterface::class, BannerSliderRepository::class);
        $this->app->singleton(LoyaltyRepositoryInterface::class, LoyaltyRepository::class);
        $this->app->singleton(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->singleton(CustomerAppointmentDetailRepositoryInterface::class, CustomerAppointmentDetailRepository::class);
        $this->app->singleton(CustomerAppointmentRepositoryInterface::class, CustomerAppointmentRepository::class);
        $this->app->singleton(SmsLogRepositoryInterface::class, SmsLogRepository::class);
        $this->app->singleton(SendSmsRepositoryInterface::class, SendSmsRepository::class);
        $this->app->singleton(SmsConfigRepositoryInterface::class, SmsConfigRepository::class);
        $this->app->singleton(SmsProviderRepositoryInterface::class, SmsProviderRepository::class);
        $this->app->singleton(ServiceCardRepositoryInterface::class, ServiceCardRepository::class);
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->singleton(UploadRepoInterface::class, UploadRepo::class);
    }
}