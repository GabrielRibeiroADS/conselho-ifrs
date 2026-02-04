<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Avaliação de cada estudante em cada reunião
     * Contém a avaliação (texto livre) e pode ter múltiplos encaminhamentos
     */
    public function up(): void
    {
        Schema::create('reuniao_estudante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reuniao_id');
            $table->integer('estudante_id'); // INT para compatibilidade com estudantesv2
            $table->text('avaliacao')->nullable(); // Parecer/avaliação do estudante
            $table->boolean('presente')->default(true); // Se o estudante estava presente no período
            $table->timestamps();

            $table->foreign('reuniao_id')->references('id')->on('reunioes')->onDelete('cascade');
            $table->unique(['reuniao_id', 'estudante_id']);
            $table->index('estudante_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reuniao_estudante');
    }
};
