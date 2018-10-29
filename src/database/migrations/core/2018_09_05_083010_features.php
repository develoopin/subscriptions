<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Features extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('features')) {
			Schema::create('features', function (Blueprint $table) {
				$table->increments('id')->unsigned();
				$table->string('name');
				$table->text('description')->nullable()->default(null);
				$table->json('lang')->nullable()->default(null);
				$table->boolean('is_premium')->default(false);
				$table->boolean('is_visible')->default(false);
				$table->boolean('is_active')->default(false);
				$table->tinyInteger('sort')->unsigned();
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
		Schema::dropIfExists('features');

	}
}
