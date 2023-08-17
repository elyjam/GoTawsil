<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view)
        {
            if (\Auth::check()) {
                $view->with('layout', \Auth::user()->role != 3 ? "layouts/back" : "layouts/client");
            }else {
                $view->with('layout', null);
            }
        });
    }
}
