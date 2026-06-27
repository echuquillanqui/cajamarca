<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->string('id_departamento', 2)->primary();
            $table->string('descripcion', 100);
            $table->timestamps();
        });

        Schema::create('provincias', function (Blueprint $table) {
            $table->id();
            $table->string('id_departamento', 2);
            $table->string('id_provincia', 2);
            $table->string('descripcion', 100);
            $table->timestamps();

            $table->unique(['id_departamento', 'id_provincia']);
            $table->foreign('id_departamento')
                ->references('id_departamento')
                ->on('departamentos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::create('distritos', function (Blueprint $table) {
            $table->id();
            $table->string('id_departamento', 2);
            $table->string('id_provincia', 2);
            $table->string('id_distrito', 2);
            $table->string('descripcion', 100);
            $table->timestamps();

            $table->unique(['id_departamento', 'id_provincia', 'id_distrito']);
            $table->foreign(['id_departamento', 'id_provincia'])
                ->references(['id_departamento', 'id_provincia'])
                ->on('provincias')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distritos');
        Schema::dropIfExists('provincias');
        Schema::dropIfExists('departamentos');
    }
};
