<?php

namespace Nnjeim\World;

use Illuminate\Support\ServiceProvider;

class WorldServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register(): void
	{
		// Register the main class to use with the facade
		$this->app->singleton('world', fn () => new WorldHelper());
	}

	/**
	 * Boot services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		// Load routes
		$this->loadRoutesFrom(__DIR__ . '/Routes/index.php');
		// Load translations
		$this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'world');

		if ($this->app->runningInConsole()) {
			// Load the database migrations.
			$this->loadMigrations();
			// Publish the resources.
			$this->publishResources();
			// Load commands
			$this->loadCommands();
		}
	}

	/**
	 * method to load the migrations when php migrate is run in the console.
	 * @return void
	 */
	private function loadMigrations(): void
	{
		$this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
	}

	/**
	 * Method to publish the resource to the app resources folder
	 * @return void
	 */
	private function publishResources(): void {
		$this->publishes([
			__DIR__ . '/../config/world.php' => config_path('world.php'),
		], 'world');

		$this->publishes([
			__DIR__ . '/Database/Seeders/WorldSeeder.php' => database_path('seeders/WorldSeeder.php'),
		], 'world');

		$this->publishes([
			__DIR__ . '/../resources/lang' => resource_path('lang/vendor/world'),
		], 'world');

		$this->publishes([
			__DIR__ . '/Database/Migrations' => database_path('migrations'),
		], 'world-migrations');
	}


	/**
	 * Method to publish the resource to the app resources folder
	 * @return void
	 */
	private function loadCommands(): void
	{
		$this->commands([
			config('world.commands.install', Commands\InstallWorldData::class),
            config('world.commands.refresh', Commands\RefreshWorldData::class)
		]);
	}
}
