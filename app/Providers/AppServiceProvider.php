<?php

namespace App\Providers;

use App\Services\Lexoffice\Endpoints\Contacts;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Contacts::class, function() {
            return new Contacts();
        });
    }
}
