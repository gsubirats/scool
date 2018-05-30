<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantPendingTeachersTable
 */
class CreateTenantPendingTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('sn1');
            $table->string('sn2')->nullable();
            $table->string('identifier'); //DNI
            $table->date('birthdate');
            $table->string('street');
            $table->string('number');
            $table->string('floor')->nullable();
            $table->string('floor_number')->nullable();
            $table->string('postal_code');
            $table->string('locality');
            $table->string('province');
            $table->string('email');
            $table->string('other_emails')->nullable();
            $table->string('telephone');
            $table->string('other_telephones')->nullable();
            $table->string('degree');
            $table->string('other_degrees')->nullable();
            $table->string('languages')->nullable();
            $table->string('profiles')->nullable();
            $table->string('other_training')->nullable();
            $table->string('photo')->nullable();
            $table->string('identifier_photocopy')->nullable();
            $table->unsignedInteger('force_id'); //Cos
            $table->unsignedInteger('specialty_id'); // Especialitat
            $table->year('teacher_start_date')->nullable();
            $table->date('start_date');
            $table->string('opositions_date')->nullable();
            $table->unsignedInteger('administrative_status_id');
            $table->string('destination_place')->nullable();
            $table->unsignedInteger('teacher_id')->nullable(); // Subtitut de:
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
        Schema::dropIfExists('pending_teachers');
    }
}
