<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\BookingWeb\Providers;


use Illuminate\Support\ServiceProvider;
use Modules\BookingWeb\Repositories\Booking\BookingRepository;
use Modules\BookingWeb\Repositories\Booking\BookingRepositoryInterface;
use Modules\BookingWeb\Repositories\Brand\BrandRepository;
use Modules\BookingWeb\Repositories\Brand\BrandRepositoryInterface;
use Modules\BookingWeb\Repositories\District\DistrictRepository;
use Modules\BookingWeb\Repositories\District\DistrictRepositoryInterface;
use Modules\BookingWeb\Repositories\Introduction\IntroductionRepository;
use Modules\BookingWeb\Repositories\Introduction\IntroductionRepositoryInterface;
use Modules\BookingWeb\Repositories\News\NewsRepository;
use Modules\BookingWeb\Repositories\News\NewsRepositoryInterface;
use Modules\BookingWeb\Repositories\Product\ProductRepository;
use Modules\BookingWeb\Repositories\Product\ProductRepositoryInterface;
use Modules\BookingWeb\Repositories\Service\ServiceRepository;
use Modules\BookingWeb\Repositories\Service\ServiceRepositoryInterface;
use Modules\BookingWeb\Repositories\Province\ProvinceRepository;
use Modules\BookingWeb\Repositories\Province\ProvinceRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->singleton(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->singleton(DistrictRepositoryInterface::class, DistrictRepository::class);
        $this->app->singleton(ServiceRepositoryInterface::class,ServiceRepository::class);
        $this->app->singleton(ProductRepositoryInterface::class,ProductRepository::class);
        $this->app->singleton(BrandRepositoryInterface::class,BrandRepository::class);
        $this->app->singleton(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->singleton(IntroductionRepositoryInterface::class, IntroductionRepository::class);
    }
}