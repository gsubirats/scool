<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantWeekLessonsTable
 */
class CreateTenantWeekLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week_lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->enum('day', [1,2,3,4,5,6,7])->nullable();
            $table->time('start_at')->nullable();
            $table->time('end_at')->nullable();
            $table->unsignedInteger('job_id')->unsigned()->nullable();
            $table->unsignedInteger('subject_group_id')->unsigned()->nullable();
            $table->unsignedInteger('classroom_id')->unsigned()->nullable();
            $table->unsignedInteger('location_id')->unsigned()->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('week_lessons');
    }
}
