<?php

namespace Modules\Survey\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Survey\Repositories\Branch\ApplyRepository;
use Modules\Survey\Repositories\Survey\SurveyRepository;
use Modules\Survey\Repositories\Branch\ApplyRepositoryInterface;
use Modules\Survey\Repositories\Survey\SurveyRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Mẫu
        $this->app->singleton(
            SurveyRepositoryInterface::class,
            SurveyRepository::class
        );
        // branch // 
        $this->app->singleton(
            ApplyRepositoryInterface::class,
            ApplyRepository::class
        );
    }
}
