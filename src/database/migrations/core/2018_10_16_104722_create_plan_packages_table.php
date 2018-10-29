<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->foreign('plan_id')->references('id')->on('plans');
            $table->integer('module_id')->unsigned();
            $table->foreign('module_id')->references('id')->on('modules');
            $table->integer('feature_id')->unsigned();
            $table->foreign('feature_id')->references('id')->on('features');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_packages');
    }
}
