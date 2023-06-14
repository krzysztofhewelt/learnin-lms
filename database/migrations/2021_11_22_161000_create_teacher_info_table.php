<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherInfoTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('teacher_info', function (Blueprint $table) {
			$table->unsignedBigInteger('user_ID')->primary();
			$table->string('scien_degree', 50);
			$table->string('business_email', 255);
			$table->string('contact_number', 20)->nullable();
			$table->string('room', 20)->nullable();
			$table->string('consultation_hours', 255)->nullable();
			$table
				->foreign('user_ID')
				->references('id')
				->on('users')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('teacher_info');
	}
}
