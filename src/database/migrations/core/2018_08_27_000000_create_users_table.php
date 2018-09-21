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
            $table->integer('company_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->string('first_name');
            $table->string('last_name')->nullable(true);
            $table->string('username')->nullable(true)->default(null);
            $table->string('email')->unique();
            $table->string('password');
            $table->text('address');
            $table->string('country');
            $table->string('city');
            $table->integer('zip');
            $table->rememberToken();
            $table->string('mobile_token');
            $table->date('deactivation_at')->nullable(true)->default(null);
            $table->date('register_at')->nullable(true)->default(null);
            $table->date('verify_at')->nullable(true)->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('company_id')->references('id')->on('companies');
            // $table->foreign('role_id')->references('id')->on('roles');
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
