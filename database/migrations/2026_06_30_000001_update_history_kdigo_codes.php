<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->string('enf_cronica', 10)->nullable()->change();
            $table->string('descrip1', 255)->nullable()->change();
            $table->string('enf_aguda', 10)->nullable()->change();
            $table->string('descrip2', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->enum('enf_cronica', ['G', 'A'])->nullable()->change();
            $table->string('descrip1', 50)->nullable()->change();
            $table->enum('enf_aguda', ['1', '2', '3'])->nullable()->change();
            $table->string('descrip2', 50)->nullable()->change();
        });
    }
};
