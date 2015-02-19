<?php namespace GlassSteel\LaravelUncSso;

use Illuminate\Support\ServiceProvider;

class UncSsoServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('unc_sso.php'),
        ]);

        // Register commands
        $this->commands('command.unc_sso.migration');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerUncSso();

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('UncSso', 'GlassSteel\LaravelUncSso\Facades\UncSso');
        });

         $this->mergeConfig();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerUncSso()
    {
        $this->app->bind('unc_sso', function ($app) {
            return new UncSso();
        });
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->bindShared('command.unc_sso.migration', function ($app) {
            return new MigrationCommand();
        });
    }

    /**
     * Merges user's and unc_sso's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'unc_sso'
        );
    }

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'command.unc_sso.migration'
        );
    }

}//UncSsoServiceProvider()