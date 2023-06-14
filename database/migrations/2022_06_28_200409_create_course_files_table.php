<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('course_files', function (Blueprint $table) {
			$table
				->id()
				->autoIncrement()
				->index();
			$table->unsignedBigInteger('course_ID');
			$table->string('filename', 255);
			$table->string('filename_original', 255);
			$table->float('file_size');
			$table->string('file_size_unit', 2);
			$table->timestamps();

			$table
				->foreign('course_ID')
				->references('id')
				->on('courses')
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
		Schema::dropIfExists('course_files');
	}
};
