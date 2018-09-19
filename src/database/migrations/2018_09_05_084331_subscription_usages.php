<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionUsages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('subscription_usages')) {
            Schema::create('subscription_usages', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('subs_id')->unsigned();
                $table->integer('usages')->unsigned();
                $table->integer('price')->unsigned();
                $table->date('used_at')->nullable(true)->default(null);
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('subs_id')->references('id')->on('subscriptions');
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
        Schema::dropIfExists('subscription_usages');
        
    }
}
