<?php namespace Lavary\Menu;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		 $this->mergeConfigFrom(__DIR__ . '/../../config/settings.php', 'laravel-menu.settings');
		 $this->mergeConfigFrom(__DIR__ . '/../../config/views.php'   , 'laravel-menu.views');
		 
		 $this->app->singleton('menu', function($app) {
		 	return new Menu;
		 });            
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Extending Blade engine
		require_once('blade/lm-attrs.php');

		$this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-menu');

		$this->publishes([
        	__DIR__ . '/resources/views'           => base_path('resources/views/vendor/laravel-menu'),
        	__DIR__ . '/../../config/settings.php' => config_path('laravel-menu/settings.php'),
        	__DIR__ . '/../../config/views.php'    => config_path('laravel-menu/views.php'),
		]);

		$this->registerMenus();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('menu');
	}

        // This method can be overridden in a child class
        public function registerMenus()
        {
            // Load the app menus if they're in routes/menus.php (Laravel 5.3)
            if (file_exists($file = $this->app['path.base'] . '/routes/menus.php')) {
                require $file;
            }

            // Load the app menus if they're in app/Http/menus.php (Laravel 5.0-5.2)
            elseif (file_exists($file = $this->app['path'] . '/Http/menus.php')) {
                require $file;
            }
        }

}
