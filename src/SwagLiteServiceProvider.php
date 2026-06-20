<?php

namespace GuljahanG\SwagLite;

use Illuminate\Support\ServiceProvider;

class SwagLiteServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(
            __DIR__.'/../routes/web.php'
        );

        $this->loadViewsFrom(
            __DIR__.'/../resources/views',
            'swaglite'
        );

        $this->publishes([
            __DIR__.'/../resources/assets' =>
            public_path('vendor/swaglite'),
        ], 'swaglite-assets');
    }
}