<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('id_departamento', 2)->nullable()->after('procedencia');
            $table->string('id_provincia', 2)->nullable()->after('id_departamento');
            $table->string('id_distrito', 2)->nullable()->after('id_provincia');

            $table->foreign('id_departamento')
                ->references('id_departamento')
                ->on('departamentos')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreign(['id_departamento', 'id_provincia'])
                ->references(['id_departamento', 'id_provincia'])
                ->on('provincias')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreign(['id_departamento', 'id_provincia', 'id_distrito'])
                ->references(['id_departamento', 'id_provincia', 'id_distrito'])
                ->on('distritos')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['id_departamento']);
            $table->dropForeign(['id_departamento', 'id_provincia']);
            $table->dropForeign(['id_departamento', 'id_provincia', 'id_distrito']);
            $table->dropColumn(['id_departamento', 'id_provincia', 'id_distrito']);
        });
    }
};
