<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

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
            $table->string('name');
            $table->string('cpf')->unique();
            $table->string('email');
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->integer('status_user_id')->unsigned()->index();
            $table->timestamps();
            $table->rememberToken();
            $table->foreign('status_user_id')->references('id')->on('status_users')->onDelete('restrict');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
