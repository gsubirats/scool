<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantJobTable
 */
class CreateTenantJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('specialty_id')->nullable();
            $table->unsignedInteger('family_id')->nullable();
            $table->integer('order');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        //job_user -> staff
        Schema::create('staff', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('job_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('staff');
    }
}
