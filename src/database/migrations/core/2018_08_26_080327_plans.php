<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Plans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('plans')) {
            Schema::create('plans', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('name');
                $table->text('description')->nullable()->default(null);
                $table->json('lang')->nullable()->default(null);
                $table->tinyInteger('value')->unsigned();
                $table->integer('price')->unsigned();
                $table->smallInteger('trial_period')->unsigned();
                $table->smallInteger('trial_limit')->unsigned();
                $table->smallInteger('period')->unsigned();
                $table->enum('interval', ['day', 'week', 'month', 'year'])->default('day');
                $table->smallInteger('grace_period')->unsigned();
                $table->enum('grace_interval', ['day', 'week', 'month', 'year'])->default('day');
                $table->smallInteger('prorate_period')->unsigned();
                $table->enum('prorate_interval', ['day', 'week', 'month', 'year'])->default('day');
                $table->smallInteger('canceled_period')->unsigned();
                $table->enum('canceled_interval', ['day', 'week', 'month', 'year'])->default('day');
                $table->enum('type', ['primary', 'addon']);
                $table->boolean('is_active')->default(false);
                $table->boolean('is_premium')->default(false);
                $table->boolean('is_visible')->default(false);
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
        Schema::dropIfExists('plans');
    }
}
