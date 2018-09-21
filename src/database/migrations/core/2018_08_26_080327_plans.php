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
                $table->json('name');
                $table->json('description');
                $table->tinyInteger('value')->unsigned();
                $table->integer('price')->unsigned();
                $table->boolean('is_active');
                $table->smallInteger('trial_period')->unsigned();
                $table->smallInteger('trial_limit')->unsigned();
                $table->smallInteger('period')->unsigned();
                $table->smallInteger('interval')->unsigned();
                $table->smallInteger('grace_period')->unsigned();
                $table->smallInteger('grace_interval')->unsigned();
                $table->smallInteger('prorate_period')->unsigned();
                $table->smallInteger('prorate_interval')->unsigned();
                $table->smallInteger('canceled_period')->unsigned();
                $table->smallInteger('canceled_interval')->unsigned();
                $table->enum('type', ['primary', 'addon']);
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
