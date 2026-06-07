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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 150);
            $table->string('dni', 15)->unique();
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['M', 'F']);
            $table->string('telefono', 20)->nullable();
            $table->string('procedencia', 100)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('instruccion', 255)->nullable();
            $table->string('civil', 255)->nullable();
            
            // Aseguramiento (SIS, EsSalud, etc.)
            $table->string('financiador', 50)->nullable(); // SIS, EsSalud, Particular, etc.
            $table->string('codigo_seguro', 50)->nullable();
            
            // Contacto de Emergencia
            $table->string('contacto_emergencia_nombre', 150)->nullable();
            $table->string('contacto_emergencia_dni', 15)->nullable();
            $table->string('contacto_emergencia_parentesco', 50)->nullable();
            $table->string('contacto_emergencia_telefono', 20)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
