<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->autoIncrement()->index();
            $table->string('name', 100);
            $table->text('description');
            $table->dateTime('available_from');
            $table->dateTime('available_to')->nullable();
            $table->float('max_points');
            $table->unsignedBigInteger('course_ID');
            $table->unsignedBigInteger('course_category');
            $table->foreign('course_ID')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('course_category')->references('id')->on('course_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
