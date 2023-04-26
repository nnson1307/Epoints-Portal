<?php
namespace Modules\Referral\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Referral\Repositories\PaymentMethod\PaymentMethodInterface;
use Modules\Referral\Repositories\PaymentMethod\PaymentMethodRepo;
use Modules\Referral\Repositories\ReferralMember\ReferralMemberInterface;
use Modules\Referral\Repositories\ReferralMember\ReferralMemberRepo;
use Modules\Referral\Repositories\ReferralPayment\ReferralPaymentInterface;
use Modules\Referral\Repositories\ReferralPayment\ReferralPaymentRepo;
use Modules\Referral\Repositories\ReferralPaymentMember\ReferralPaymentMemberInterface;
use Modules\Referral\Repositories\ReferralPaymentMember\ReferralPaymentMemberRepo;
use Modules\Referral\Repositories\ReferralProgram\ReferralProgramInterface;
use Modules\Referral\Repositories\ReferralProgram\ReferralProgramRepo;
use Modules\Referral\Repositories\ReferralProgramInvite\ReferralProgramInviteInterface;
use Modules\Referral\Repositories\ReferralProgramInvite\ReferralProgramInviteRepo;
use Modules\Referral\Repositories\ReferralRepository;
use Modules\Referral\Repositories\ReferralInterface;
use Modules\Referral\Repositories\Staffs\StaffsInterface;
use Modules\Referral\Repositories\Staffs\StaffsRepo;
use Modules\Referral\Repositories\Upload\UploadRepo;
use Modules\Referral\Repositories\Upload\UploadRepoInterface;



class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Khai báo cái repository ở đây
        $this->app->singleton(ReferralInterface::class, ReferralRepository::class);
        $this->app->singleton(UploadRepoInterface::class, UploadRepo::class);
        $this->app->singleton(ReferralMemberInterface::class,ReferralMemberRepo::class);
        $this->app->singleton(ReferralPaymentInterface::class,ReferralPaymentRepo::class);
        $this->app->singleton(ReferralPaymentMemberInterface::class,ReferralPaymentMemberRepo::class);
        $this->app->singleton(StaffsInterface::class,StaffsRepo::class);
        $this->app->singleton(ReferralProgramInviteInterface::class,ReferralProgramInviteRepo::class);
        $this->app->singleton(ReferralProgramInterface::class,ReferralProgramRepo::class);
        $this->app->singleton(PaymentMethodInterface::class,PaymentMethodRepo::class);
    }
}
