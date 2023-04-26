<?php

namespace Modules\ChatHub\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ChatHub\Repositories\Response\ResponseRepository;
use Modules\ChatHub\Repositories\Response\ResponseRepositoryInterface;
use Modules\ChatHub\Repositories\ResponseContent\ResponseContentRepository;
use Modules\ChatHub\Repositories\ResponseContent\ResponseContentRepositoryInterface;
use Modules\ChatHub\Repositories\Setting\SettingRepository;
use Modules\ChatHub\Repositories\Setting\SettingRepositoryInterface;
use Modules\ChatHub\Repositories\Message\MessageRepository;
use Modules\ChatHub\Repositories\Message\MessageRepositoryInterface;
use Modules\ChatHub\Repositories\Brand\BrandRepositoryInterface;
use Modules\ChatHub\Repositories\Brand\BrandRepository;
use Modules\ChatHub\Repositories\Attribute\AttributeRepositoryInterface;
use Modules\ChatHub\Repositories\Attribute\AttributeRepository;
use Modules\ChatHub\Repositories\Sku\SkuRepositoryInterface;
use Modules\ChatHub\Repositories\Sku\SkuRepository;
use Modules\ChatHub\Repositories\SubBrand\SubBrandRepositoryInterface;
use Modules\ChatHub\Repositories\SubBrand\SubBrandRepository;
use Modules\ChatHub\Repositories\Post\PostRepositoryInterface;
use Modules\ChatHub\Repositories\Post\PostRepository;
use Modules\ChatHub\Repositories\Comment\CommentRepositoryInterface;
use Modules\ChatHub\Repositories\Comment\CommentRepository;
use Modules\ChatHub\Repositories\ResponseButton\ResponseButtonRepositoryInterface;
use Modules\ChatHub\Repositories\ResponseButton\ResponseButtonRepository;
use Modules\ChatHub\Repositories\ResponseElement\ResponseElementRepositoryInterface;
use Modules\ChatHub\Repositories\ResponseElement\ResponseElementRepository;
use Modules\ChatHub\Repositories\ResponseDetail\ResponseDetailRepositoryInterface;
use Modules\ChatHub\Repositories\ResponseDetail\ResponseDetailRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            SettingRepositoryInterface::class,
            SettingRepository::class
        );
        $this->app->singleton(
            MessageRepositoryInterface::class,
            MessageRepository::class
        );
        $this->app->singleton(
            BrandRepositoryInterface::class,
            BrandRepository::class
        );
        $this->app->singleton(
            AttributeRepositoryInterface::class,
            AttributeRepository::class
        );
        $this->app->singleton(
            SkuRepositoryInterface::class,
            SkuRepository::class
        );
        $this->app->singleton(
            SubBrandRepositoryInterface::class,
            SubBrandRepository::class
        );
        $this->app->singleton(
            PostRepositoryInterface::class,
            PostRepository::class
        );
        $this->app->singleton(
            CommentRepositoryInterface::class,
            CommentRepository::class
        );
        $this->app->singleton(
            ResponseButtonRepositoryInterface::class,
            ResponseButtonRepository::class
        );
        $this->app->singleton(
            ResponseElementRepositoryInterface::class,
            ResponseElementRepository::class
        );
        $this->app->singleton(
            ResponseDetailRepositoryInterface::class,
            ResponseDetailRepository::class
        );
        $this->app->singleton(
            ResponseContentRepositoryInterface::class,
            ResponseContentRepository::class
        );
        $this->app->singleton(
            ResponseRepositoryInterface::class,
            ResponseRepository::class
        );
    }
}