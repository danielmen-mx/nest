<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomFacadesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('conversion', function () {  //Keep in mind this "check" must be return from facades accessor
            return new \App\Facades\Base\Conversion();
        });

        // app()->bind('string', function () {
            // return /path/facade
        // })
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
