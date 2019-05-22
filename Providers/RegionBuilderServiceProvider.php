<?php

namespace Modules\RegionBuilder\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

use Modules\RegionBuilder\Entities\Cities;
use Modules\RegionBuilder\Observers\CitiesObserver;
use Modules\RegionBuilder\Entities\Areas;
use Modules\RegionBuilder\Observers\AreasObserver;
use Modules\RegionBuilder\Entities\Streets;
use Modules\RegionBuilder\Observers\StreetsObserver;

class RegionBuilderServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->commands([
            \Modules\RegionBuilder\Console\RegionBuilderCommand::class,
        ]);
        Cities::observe(CitiesObserver::class);
        Areas::observe(AreasObserver::class);
        Streets::observe(StreetsObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('regionbuilder.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'regionbuilder'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/regionbuilder');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/regionbuilder';
        }, \Config::get('view.paths')), [$sourcePath]), 'regionbuilder');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/regionbuilder');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'regionbuilder');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'regionbuilder');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
