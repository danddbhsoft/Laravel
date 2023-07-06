<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_person', function (Blueprint $table) {
            $table->bigInteger('person_id')->unsigned();
            $table->bigInteger('course_id')->unsigned();
            $table->primary(['person_id', 'course_id']);
            $table->foreign("person_id")->references("id")->on("person");
            $table->foreign("course_id")->references("id")->on("courses");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_person');
    }
};
