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
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->string('name');
            $table->string('identity_card', 8)->unique();
            $table->integer('bank_id')->unsigned();
            $table->string('number_account', 20);
            $table->float('balance', 10, 2);
            $table->float('block_balance', 10, 2);
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->integer('level');
            $table->string('password_temp')->nullable();
            $table->dateTime('password_temp_expiration')->nullable();
            $table->rememberToken();
            $table->timestamps();
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
