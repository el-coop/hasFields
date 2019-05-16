<?php

namespace ElCoop\HasFields;

use Illuminate\Support\ServiceProvider;

class HasFieldsServiceProvider extends ServiceProvider {
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->loadMigrationsFrom(__DIR__.'\\migrations\\2018_11_06_213626_create_fields_table.php');
	}
}
