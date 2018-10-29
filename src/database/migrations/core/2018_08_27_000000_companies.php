<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Companies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('companies')) {
            // create the plan_subscription_usages table
            Schema::create('companies', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('name');
                $table->text('db_name')->nullable(true)->default(null);
                $table->text('db_host')->nullable(true)->default(null);
                $table->string('biscuit')->nullable(true)->default(null);
                $table->text('address');
                $table->string('country');
                $table->string('city');
                $table->integer('zip');
                $table->string('business_field');
                $table->integer('total_employee')->nullable(true)->default(null);
                $table->string('phone1');
                $table->string('phone2')->nullable(true)->default(null);
                $table->integer('balance')->unsigned()->default(0);
                $table->enum('status', ['active', 'fereeze', 'deleted']);
                $table->datetime('register_at')->nullable(true)->default(null);
                $table->datetime('verify_at')->nullable(true)->default(null);
                $table->softDeletes();
                $table->timestamps();

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
        Schema::dropIfExists('companies');
    }
}
