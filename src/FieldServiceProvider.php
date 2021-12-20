<?php

namespace Norgeit\VideoPlay;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('Easy-Player', asset('/EasyPlayer-lib.min.js'));
            Nova::script('video-play', __DIR__.'/../dist/js/field.js');
            Nova::style('element', 'https://unpkg.com/element-ui/lib/theme-chalk/index.css');
            Nova::style('video-play', __DIR__.'/../dist/css/field.css');

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
