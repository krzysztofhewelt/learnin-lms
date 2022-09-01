<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksMarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_marks', function (Blueprint $table) {
            $table->id()->autoIncrement()->index();
            $table->unsignedBigInteger('user_ID');
            $table->unsignedBigInteger('task_ID');
            $table->float('points');
            $table->float('mark');
            $table->text('description')->nullable();
            $table->date('updated_at');
            $table->foreign('user_ID')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('tasks_marks');
    }
}
