<?php

namespace Chenzeshu\ChenUtils\Providers;

use Chenzeshu\ChenUtils\WechatUtils;
use Illuminate\Support\ServiceProvider;

class ChenUtilsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__."/../config/chen.php" => config_path('chen.php'),
            __DIR__."/../Exceptions/BaseException.php" => app_path('Exceptions/BaseException.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('chenwechat', function (){
            return new WechatUtils;
        });
    }
}
