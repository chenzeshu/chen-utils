<?php

namespace Chenzeshu\ChenUtils\Providers;

use Chenzeshu\ChenUtils\WechatUtils;
use Illuminate\Support\ServiceProvider;

class UitlsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__."/../config/chen.php" => config_path('chen.php')
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
