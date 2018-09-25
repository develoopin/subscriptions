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
                $table->integer('module_id')->unsigned();
                $table->json('name');
                $table->tinyInteger('sort')->unsigned();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('module_id')->references('id')->on('modules');

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
