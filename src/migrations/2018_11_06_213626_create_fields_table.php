<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('fields', function (Blueprint $table) {
			$table->bigIncrements('id');
			foreach (config('app.locales',[config('app.locale', 'en')]) as $locale) {
				$table->string('name_' . $locale);
				$table->string('placeholder_' . $locale);
			}
			$table->string('type');
			$table->string('form');
			$table->string('status');
			$table->integer('order')->default(0);
			$table->json('options')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('fields');
	}
}
