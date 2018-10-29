<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Modules extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('modules')) {
			Schema::create('modules', function (Blueprint $table) {
				$table->increments('id')->unsigned();
				$table->string('name');
				$table->text('description')->nullable()->default(null);
				$table->json('lang')->nullable()->default(null);
				$table->tinyInteger('value')->unsigned();
				$table->integer('price')->unsigned();
				$table->smallInteger('period')->unsigned();
				$table->enum('interval', ['day', 'week', 'month', 'year'])->default('day');
				$table->tinyInteger('sort')->unsigned();
				$table->boolean('is_premium')->default(false);
				$table->boolean('is_visible')->default(false);
				$table->boolean('is_active')->default(false);
				$table->timestamps();
				$table->softDeletes();

			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('modules');

	}
}
