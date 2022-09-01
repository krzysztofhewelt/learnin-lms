<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskReferentialFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_referential_files', function (Blueprint $table) {
            $table->id()->autoIncrement()->index();
            $table->unsignedBigInteger('task_ID');
            $table->string('filename', 255);
            $table->string('filename_original', 255);
            $table->float('file_size');
            $table->string('file_size_unit', '2');
            $table->timestamps();

            $table->foreign('task_ID')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_referential_files');
    }
}
