<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\SiteSetting;

class SiteSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Share site settings with all views
        View::composer('*', function ($view) {
            if (Schema::hasTable('site_settings')) {
                $settings = SiteSetting::getAllCached();
                $view->with('siteSettings', $settings);
            }
        });
    }
}
