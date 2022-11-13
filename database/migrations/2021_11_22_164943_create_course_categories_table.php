<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("course_categories", function (Blueprint $table) {
            $table
                ->id()
                ->autoIncrement()
                ->index();
            $table->unsignedBigInteger("course_ID");
            $table->string("name", 45);
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
        Schema::dropIfExists("course_categories");
    }
}
