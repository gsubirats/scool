<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantSubjectsTable.
 *
 */
class CreateTenantSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('shortname')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('number')->nullable();
            $table->integer('group_id')->unsigned()->nullable();
            $table->integer('study_id')->unsigned()->nullable();
            $table->integer('course_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned()->default(1);
            $table->unsignedInteger('hours')->nullable();
            $table->unsignedInteger('week_hours')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('subjects');
    }
}
