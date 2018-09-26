<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Moduls extends Migration
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
                $table->integer('plan_id')->unsigned();
                $table->json('name');
                $table->json('description')->nullable(true)->default(null);
                $table->tinyInteger('value')->unsigned();
                $table->integer('price')->unsigned();
                $table->tinyInteger('sort')->unsigned();
                $table->boolean('is_premium')->default(false);
                $table->boolean('is_active')->default(false);
                $table->smallInteger('interval')->unsigned();
                $table->smallInteger('period')->unsigned();
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('plan_id')->references('id')->on('plans');

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
        Schema::dropIfExists('moduls');

    }
}
