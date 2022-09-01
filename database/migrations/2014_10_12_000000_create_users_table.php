<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id()->autoIncrement()->index();
            $table->string('name', 45);
            $table->string('surname', 45);
            $table->string('identification_number', 15);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('account_role', 20);
            $table->boolean('active');
            $table->dateTime('last_success_login')->nullable();
            $table->dateTime('last_wrong_login')->nullable();
            $table->string('locale', 5)->default(env('DEFAULT_LOCALE'));
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
