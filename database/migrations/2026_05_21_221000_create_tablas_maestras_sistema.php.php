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
        // 1. Tabla TIPOS_VEHICULOS
        Schema::create('TIPOS_VEHICULOS', function (Blueprint $table) {
            $table->id('id');
            $table->text('NombreVehiculos');
            $table->integer('CupoMaximo');
            $table->timestamps();
        });

        // 2. Tabla ORIGENES
        Schema::create('ORIGENES', function (Blueprint $table) {
            $table->id('id');
            $table->string('NombreOrigenes', 255);
            $table->timestamps();
        });

        // 3. Tabla NOMBRE_CONDUCTORES
        Schema::create('NOMBRE_CONDUCTORES', function (Blueprint $table) {
            $table->id('id');
            $table->string('DNI', 50)->unique();
            $table->text('NombreConductor');
            $table->text('ApellidoConductor');
            $table->timestamps();
        });

        // 4. Tabla NOMBRE_PRODUCTORES
        Schema::create('NOMBRE_PRODUCTORES', function (Blueprint $table) {
            $table->id('id');
            $table->string('NombreProductores', 255);
            $table->timestamps();
        });

        // 5. Tabla USUARIOS
        Schema::create('USUARIOS', function (Blueprint $table) {
            $table->id('id');
            $table->text('Nombre');
            $table->string('Usuario', 100)->unique();
            $table->text('Contrasena');
            $table->text('Rol');
            $table->timestamps();
        });

        // 6. Tabla COLA_ESPERA (Ya existen las maestras arriba)
        Schema::create('COLA_ESPERA', function (Blueprint $table) {
            $table->id('id');
            $table->datetime('fecha_registro');
            $table->text('Placa');
            $table->text('Estado');

            $table->unsignedBigInteger('ID_TipoVehiculo');
            $table->unsignedBigInteger('ID_NombreConductor');
            $table->unsignedBigInteger('ID_NombreProductor');
            $table->unsignedBigInteger('ID_Origen');
            $table->unsignedBigInteger('Usuario_Registro');

            // Definición manual de relaciones
            $table->foreign('ID_TipoVehiculo')->references('id')->on('TIPOS_VEHICULOS')->onDelete('cascade');
            $table->foreign('ID_NombreConductor')->references('id')->on('NOMBRE_CONDUCTORES')->onDelete('cascade');
            $table->foreign('ID_NombreProductor')->references('id')->on('NOMBRE_PRODUCTORES')->onDelete('cascade');
            $table->foreign('ID_Origen')->references('id')->on('ORIGENES')->onDelete('cascade');
            $table->foreign('Usuario_Registro')->references('id')->on('USUARIOS')->onDelete('cascade');

            $table->timestamps();
        });

        // 7. Tabla MOVIMIENTOS
        Schema::create('MOVIMIENTOS', function (Blueprint $table) {
            $table->id('id');
            $table->datetime('HoraEntrada');
            $table->mediumText('Placa');
            $table->boolean('ISCC')->default(false);

            $table->unsignedBigInteger('ID_TipoVehiculo');
            $table->unsignedBigInteger('ID_NombreConductor');
            $table->unsignedBigInteger('ID_NombreProductor');
            $table->unsignedBigInteger('ID_Origen');
            $table->unsignedBigInteger('Usuario_Autoriza');

            // Definición manual de relaciones
            $table->foreign('ID_TipoVehiculo')->references('id')->on('TIPOS_VEHICULOS')->onDelete('cascade');
            $table->foreign('ID_NombreConductor')->references('id')->on('NOMBRE_CONDUCTORES')->onDelete('cascade');
            $table->foreign('ID_NombreProductor')->references('id')->on('NOMBRE_PRODUCTORES')->onDelete('cascade');
            $table->foreign('ID_Origen')->references('id')->on('ORIGENES')->onDelete('cascade');
            $table->foreign('Usuario_Autoriza')->references('id')->on('USUARIOS')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MOVIMIENTOS');
        Schema::dropIfExists('COLA_ESPERA');
        Schema::dropIfExists('USUARIOS');
        Schema::dropIfExists('NOMBRE_PRODUCTORES');
        Schema::dropIfExists('NOMBRE_CONDUCTORES');
        Schema::dropIfExists('ORIGENES');
        Schema::dropIfExists('TIPOS_VEHICULOS');
    }
};
