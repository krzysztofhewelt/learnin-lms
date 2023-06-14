<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTasksUploads extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_tasks_uploads', function (Blueprint $table) {
			$table
				->id()
				->autoIncrement()
				->index();
			$table->unsignedBigInteger('task_ID');
			$table->unsignedBigInteger('user_ID');
			$table->string('filename', 255);
			$table->string('filename_original', 255);
			$table->float('file_size');
			$table->string('file_size_unit', 2);
			$table->timestamps();

			$table
				->foreign('task_ID')
				->references('id')
				->on('tasks')
				->onDelete('cascade');
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
		Schema::dropIfExists('users_tasks_uploads');
	}
}
