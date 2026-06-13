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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->comment('Usuario responsable de la orden');
            $table->date('fecha')->nullable();
            $table->string('codigo', 50)->nullable()->unique();
            $table->enum('tipo', ['HISTORIA','HEMODIALISIS', 'LABORATORIO'])->default('HISTORIA');
            $table->enum('estado', ['PENDIENTE', 'EN_PROCESO', 'git FINALIZADA', 'ANULADA'])->default('PENDIENTE');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
