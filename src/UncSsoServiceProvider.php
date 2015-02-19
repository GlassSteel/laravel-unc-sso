<?php namespace GlassSteel\LaravelUncSso;

use Illuminate\Support\ServiceProvider;

class UncSsoServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register 'unc_sso' instance container to our UncSso object
        $this->app['unc_sso'] = $this->app->share(function($app)
        {
            return new GlassSteel\LaravelUncSso\UncSso;
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('UncSso', 'GlassSteel\LaravelUncSso\Facades\UncSso');
        });
    }
}//UncSsoServiceProvider()