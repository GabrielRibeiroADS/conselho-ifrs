<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Reuniões de um Conselho de Classe
     * - Conselho Anual: 3 reuniões (1º semestre, 2º semestre, final)
     * - Conselho Semestral: 2 reuniões (intermediária, final)
     */
    public function up(): void
    {
        Schema::create('reunioes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conselho_id');
            $table->string('titulo', 100); // Ex: "1ª Reunião - 1º Semestre", "Reunião Final"
            $table->tinyInteger('numero')->unsigned(); // 1, 2 ou 3
            $table->date('data_reuniao')->nullable();
            $table->enum('status', ['pendente', 'em_andamento', 'finalizada'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->foreign('conselho_id')->references('id')->on('conselhos')->onDelete('cascade');
            $table->index(['conselho_id', 'numero']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reunioes');
    }
};
