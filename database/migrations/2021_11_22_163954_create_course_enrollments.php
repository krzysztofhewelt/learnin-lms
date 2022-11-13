<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseEnrollments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("course_enrollments", function (Blueprint $table) {
            $table
                ->id()
                ->autoIncrement()
                ->index();
            $table->unsignedBigInteger("user_ID");
            $table->unsignedBigInteger("course_ID");
            $table
                ->foreign("user_ID")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table
                ->foreign("course_ID")
                ->references("id")
                ->on("courses")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("course_enrollments");
    }
}
