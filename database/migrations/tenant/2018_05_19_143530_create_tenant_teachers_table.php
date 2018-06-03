<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTenantTeachersTable
 */
class CreateTenantTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('code')->unique();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('administrative_status_id')->nullable();
            $table->unsignedInteger('specialty_id')->nullable();
            $table->string('titulacio_acces')->nullable();
            $table->string('altres_titulacions')->nullable();
            $table->string('idiomes')->nullable();
            $table->string('altres_formacions')->nullable();
            $table->string('perfil_professional')->nullable();
            $table->string('data_inici_treball')->nullable();
            $table->date('data_incorporacio_centre')->nullable();
            $table->string('data_superacio_oposicions')->nullable();
            $table->string('lloc_destinacio_definitiva')->nullable();
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
        Schema::dropIfExists('teachers');
    }
}
