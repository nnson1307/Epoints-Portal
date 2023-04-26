<?php

namespace App\Providers;

use App\Helpers\Translator;             // <= Your own class
use Illuminate\Translation\FileLoader;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLoader();

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            $domain = request()->getHost();

            $arrConfigConnStr = config('epoint-connstr', []);

            if (!empty($arrConfigConnStr[$domain])) {

                $conStr = $arrConfigConnStr[$domain];

                // Kiểm tra connect string không đủ thông tin bắt buộc thì trả về lỗi 404
                $arrParams = $this->parseConnStr($conStr);


                if(isset($arrParams['brand_code'])){
                    $brandCode = $arrParams['brand_code'];


                    $langPath = resource_path('lang/'.$brandCode);

                    if(is_dir($langPath)){
                        return new FileLoader($app['files'], $langPath);
                    } else {
                        return new FileLoader($app['files'], $app['path.lang']);
                    }
                } else {
                    return new FileLoader($app['files'], $app['path.lang']);
                }

            } else {
                return new FileLoader($app['files'], $app['path.lang']);
            }

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['translator', 'translation.loader'];
    }

    /**
     * Parse connect string to array
     *
     * @param $str
     * @return array
     */
    protected function parseConnStr($str)
    {
        $arrPart = explode(';', $str);
        $arrParams = [];
        foreach ($arrPart as $item) {
            list($key, $val) = explode('=', $item, 2);
            $key = strtolower($key);

            $arrParams[$key] = $val;
        }

        return $arrParams;
    }
}