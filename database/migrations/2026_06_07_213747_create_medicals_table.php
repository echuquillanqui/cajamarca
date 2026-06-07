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
        Schema::create('medicals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->comment('Nefrólogo que prescribe');
            $table->string('numero_sesion', 20);
            $table->date('fecha_sesion');
            $table->string('servicio_procedencia', 50)->nullable();
            $table->string('cama', 10)->nullable();

            //EVALUACION CLINICA

            $table->string('pa', 15)->nullable();
            $table->string('fc', 10)->nullable();
            $table->string('fr', 10)->nullable();
            $table->string('sat', 10)->nullable();
            $table->text('evaluacion')->nullable();
            $table->decimal('peso_seco', 5, 2)->nullable();
            $table->string('diuresis', 50)->nullable();
            $table->boolean('alergias')->default(false)->nullable();
            $table->text('alergias_descripcion')->nullable();

            //PRESCRIPCION
            $table->string('tecnica', 50)->nullable();
            $table->string('frecuencia', 30)->nullable();
            $table->string('acceso', 50)->nullable();
            $table->string('heparina', 50)->nullable();
            $table->string('filtro', 30)->nullable();
            $table->string('membrana', 30)->nullable();
            $table->integer('qb')->comment('Flujo de bomba de sangre');
            $table->integer('qd')->comment('Flujo del dialisato');
            $table->integer('tiempo_horas')->comment('Tiempo programado');
            $table->integer('sodio_mEq')->nullable();
            $table->string('perfil_sodio', 30)->nullable();
            $table->string('tdld', 30)->nullable();
            $table->string('uft', 30)->nullable();
            $table->string('uf_asilada', 30)->nullable();
            $table->string('perfil_uf', 30)->nullable();
            $table->string('uf_efectivo', 30)->nullable();
            $table->text('otras_indicaciones')->nullable();
            $table->enum('grado_dep', ['I', 'II', 'III','IV'])->nullable();

            $table->string('grup_fact', 30)->nullable();
            $table->boolean('transfuciones')->default(false)->nullable();

            $table->string('t_inicial')->nullable();
            $table->string('t_final')->nullable();
            $table->string('p_inicial')->nullable();
            $table->string('p_final')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicals');
    }
};
