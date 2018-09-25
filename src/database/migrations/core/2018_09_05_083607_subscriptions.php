<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Subscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('plan_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->date('cancel_limit_at')->nullable(true)->default(null);
                $table->date('canceled_at')->nullable(true)->default(null);
                $table->date('trial_end_at')->nullable(true)->default(null);
                $table->date('start_at')->nullable(true)->default(null);
                $table->date('end_at')->nullable(true)->default(null);
                $table->date('frozen_at')->nullable(true)->default(null);
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('plan_id')->references('id')->on('plans');
                $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('subscriptions');

    }
}
