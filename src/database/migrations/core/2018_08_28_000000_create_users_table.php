<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('role_id')->unsigned();
            $table->string('first_name');
            $table->string('last_name')->nullable(true);
            $table->string('username')->nullable(true)->default(null);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('mobile_token')->nullable()->default(null);
            $table->dateTime('deactivated_at')->nullable(true)->default(null);
            $table->dateTime('registered_at')->nullable(true)->default(null);
            $table->timestamp('email_verified_at')->nullable();
            $table->dateTime('disabled_at')->nullable(true)->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('role_id')->references('id')->on('user_roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
