<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Encaminhamentos feitos para cada estudante em uma reunião
     * Um estudante pode ter múltiplos encaminhamentos na mesma reunião
     */
    public function up(): void
    {
        Schema::create('encaminhamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reuniao_estudante_id');
            $table->text('descricao'); // Texto livre: "Encaminhar para atendimento psicológico", "Elogio por bom desempenho", etc.
            $table->enum('status', ['pendente', 'em_andamento', 'concluido', 'cancelado'])->default('pendente');
            $table->text('observacoes')->nullable(); // Observações adicionais ou feedback do encaminhamento
            $table->date('data_conclusao')->nullable();
            $table->timestamps();

            $table->foreign('reuniao_estudante_id')->references('id')->on('reuniao_estudante')->onDelete('cascade');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encaminhamentos');
    }
};
