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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->time('hora');
            $table->string('pa', 15)->nullable();
            $table->integer('pam')->nullable();
            $table->integer('fc')->nullable();
            $table->integer('sao2')->nullable();
            $table->integer('uf_hora')->nullable();
            $table->integer('sodio')->nullable();
            $table->integer('qb')->nullable();
            $table->integer('ra')->nullable(); // Presión Venosa / Arterial
            $table->integer('rv')->nullable();
            $table->integer('ptm')->comment('Presión Transmembrana');
            $table->text('observaciones')->nullable();
            $table->string('laboratorio_control', 100)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
