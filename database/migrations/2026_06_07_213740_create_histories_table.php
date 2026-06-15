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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();

            // Relaciones Maestras
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('user_id')->comment('Médico que apertura')->constrained('users');
            $table->date('fecha_ingreso_hd');
            
            // Enfermedad Actual
            $table->string('serv_origen', 25)->nullable();
            $table->string('cama', 25)->nullable();
            $table->string('tiempo_enfermedad', 50)->nullable();
            $table->string('inicio_enfermedad', 50)->nullable(); // Súbito, insidioso
            $table->string('curso_enfermedad', 50)->nullable();  // Progresivo, estacionario
            $table->text('relato_cronologico')->nullable();

            // Funciones Biológicas
            $table->string('apetito', 30)->nullable();
            $table->string('sed', 30)->nullable();
            $table->string('heces', 30)->nullable();
            $table->string('sueno', 30)->nullable();
            $table->string('diuresis_ingreso', 50)->nullable();

            // Antecedentes Personales (Matriz bidimensional JSON para la grilla de 3 columnas)
            $table->json('antecedentes_personales')->nullable();
            $table->text('antecedentes_familiares')->nullable();
            $table->text('alergias')->nullable();
            
            // Biopsia Renal
            $table->boolean('biopsia_renal')->default(false);
            $table->string('biopsia_renal_anio', 4)->nullable();
            $table->string('biopsia_renal_resultado')->nullable();

            // Examen Físico Funcional / Signos Vitales
            $table->string('pa', 15)->nullable();
            $table->integer('fc')->nullable();
            $table->integer('fr')->nullable();
            $table->integer('sat_o2')->nullable();
            $table->decimal('peso_ingreso', 5, 2)->nullable();
            $table->decimal('talla_ingreso', 3, 2)->nullable();
            $table->decimal('fio', 5, 2)->nullable();
            
            // Textos de Revisión de Sistemas
            $table->text('aspecto_general')->nullable();
            $table->text('piel')->nullable();
            $table->text('tcsc')->nullable();
            $table->text('respiratorio')->nullable();
            $table->text('cardiovascular')->nullable();
            $table->string('abdomen', 100)->nullable();
            $table->string('g_urinario', 100)->nullable();
            $table->string('neurologico', 100)->nullable();
            $table->string('e_nutricional', 100)->nullable();

            // Acceso Vascular Principal
            $table->enum('tipo', ['CVC TUNELIZADO', 'CVC TEMPORAL', 'FAV', 'INJERTO'])->nullable();
            $table->enum('localizacion', ['RADIAL', 'HUMERAL', 'CERVICAL', 'FEMORAL', 'OTROS'])->nullable();
            $table->enum('lado', ['DERECHA', 'IZQUIERDA'])->nullable();
            $table->enum('estado', ['BUENO', 'MALO', 'REGULAR'])->nullable();

            // Otras Terapias Previas
            $table->boolean('d_peritoneal')->default(false)->nullable();
            $table->boolean('t_renal')->default(false)->nullable();

            // Causa de Pérdida / Historial de Accesos Viejos
            $table->string('o_tipos', 50)->nullable();
            $table->date('o_fecha')->nullable();
            $table->string('o_causa', 100)->nullable();

            // Serología Viral
            $table->boolean('hiv')->default(false)->nullable();
            $table->boolean('hbsag')->default(false)->nullable();
            $table->boolean('anti_hbc')->default(false)->nullable();
            $table->boolean('vhc')->default(false)->nullable();
            $table->boolean('anti_hbs')->default(false)->nullable();
            $table->boolean('rpr')->default(false)->nullable();
            $table->string('ningun_se')->default('NINGUNO')->nullable();

            // Esquema de Vacunación Hepatitis B
            $table->integer('vacuna_ingreso')->default(0)->nullable();
            $table->integer('vacuna_alta')->default(0)->nullable();
            $table->string('otras_vacunas', 200)->nullable();

            // Diagnósticos e Injurias Renales
            $table->enum('enf_cronica', ['G', 'A'])->nullable();
            $table->string('descrip1', 50)->nullable();
            $table->string('etiologia_cronica', 200)->nullable();

            $table->enum('enf_aguda', ['1', '2', '3'])->nullable();
            $table->string('descrip2', 50)->nullable();
            $table->string('etiologia_aguda', 200)->nullable();
            
            $table->text('motivo_hospt_act')->nullable();
            $table->json('diagnostico')->nullable(); // Campo flexible para agregados adicionales

            // Egreso, Destino y Cierre Clínico
            $table->date('f_alta')->nullable();
            $table->string('consideraciones_alta')->nullable();
            $table->text('motivo_fallece')->nullable();
            $table->decimal('peso_seco', 10, 2)->nullable();
            $table->string('diuresis_alta', 50)->nullable();

            // Pendientes al Cierre
            $table->text('pendientes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};