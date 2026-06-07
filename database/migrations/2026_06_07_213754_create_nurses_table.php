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
        Schema::create('nurses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->comment('Nefrólogo que prescribe');
    
            // Desglose metodológico SOAPIE
            $table->time('hora1');
            $table->text('s_subjetivo')->comment('Datos que refiere el paciente');

            $table->time('hora2');
            $table->text('o_objetivo')->comment('Datos del examen físico y monitores');

            $table->time('hora3');
            $table->text('a_analisis')->comment('Diagnóstico de enfermería');

            $table->time('hora4');
            $table->text('p_planificacion')->comment('Plan de acción inmediato');

            $table->time('hora5');
            $table->text('i_intervencion')->comment('Ejecución de los cuidados');

            $table->time('hora6');
            $table->text('e_evaluacion')->comment('Resultados de las intervenciones');

            $table->string('uf_efectivo', 100)->nullable();
            $table->string('asp_filtro')->nullable();
            $table->string('epo')->nullable();
            $table->string('hierro')->nullable();
            $table->string('vitb12')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurses');
    }
};
