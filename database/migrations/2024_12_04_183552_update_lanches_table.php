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
        Schema::table('lanches', function (Blueprint $table) {
            $table->string('name')->change(); // Permitir nulo no campo 'name'
            $table->text('description')->change(); // Alterar para 'text' e permitir nulo
            $table->json('type')->change(); // Alterar para JSON
            $table->string('promotion')->change(); // Alterar para boolean com valor padrão
            $table->decimal('discount', 8, 2)->change(); // Alterar para decimal com precisão
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lanches', function (Blueprint $table) {
            $table->string('name')->change(); // Reverter para o tipo original
            $table->string('description')->change();
            $table->string('type')->change();
            $table->string('promotion')->change();
            $table->string('discount')->change();
           
        });
    }
};
