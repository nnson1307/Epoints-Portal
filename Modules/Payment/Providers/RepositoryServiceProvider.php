<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 10:37
 */

namespace Modules\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Payment\Repositories\Payment\PaymentRepository;
use Modules\Payment\Repositories\Payment\PaymentRepositoryInterface;
use Modules\Payment\Repositories\PaymentMethod\PaymentMethodRepository;
use Modules\Payment\Repositories\PaymentMethod\PaymentMethodRepositoryInterface;
use Modules\Payment\Repositories\PaymentType\PaymentTypeRepository;
use Modules\Payment\Repositories\PaymentType\PaymentTypeRepositoryInterface;

use Modules\Payment\Repositories\PaymentUnit\PaymentUnitRepository;
use Modules\Payment\Repositories\PaymentUnit\PaymentUnitRepositoryInterface;
use Modules\Payment\Repositories\Receipt\ReceiptRepo;
use Modules\Payment\Repositories\Receipt\ReceiptRepoInterface;
use Modules\Payment\Repositories\ReceiptOnline\ReceiptOnlineRepo;
use Modules\Payment\Repositories\ReceiptOnline\ReceiptOnlineRepoInterface;
use Modules\Payment\Repositories\ReceiptType\ReceiptTypeRepo;
use Modules\Payment\Repositories\ReceiptType\ReceiptTypeRepoInterface;
use Modules\Payment\Repositories\ReportSynthesis\ReportSynthesisRepo;
use Modules\Payment\Repositories\ReportSynthesis\ReportSynthesisRepoInterface;
use Modules\Payment\Repositories\DiscountCauses\DiscountCausesRepo;
use Modules\Payment\Repositories\DiscountCauses\DiscountCausesRepoInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->singleton(PaymentTypeRepositoryInterface::class, PaymentTypeRepository::class);
        $this->app->singleton(PaymentMethodRepositoryInterface::class, PaymentMethodRepository::class);

        // Phiếu thu
        $this->app->singleton(ReceiptRepoInterface::class, ReceiptRepo::class);
        $this->app->singleton(ReportSynthesisRepoInterface::class, ReportSynthesisRepo::class);
        $this->app->singleton(ReceiptTypeRepoInterface::class, ReceiptTypeRepo::class);
        $this->app->singleton(DiscountCausesRepoInterface::class, DiscountCausesRepo::class);
        $this->app->singleton(PaymentUnitRepositoryInterface::class, PaymentUnitRepository::class);
        $this->app->singleton(ReceiptOnlineRepoInterface::class, ReceiptOnlineRepo::class);
    }
}