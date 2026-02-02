<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conselho_estudante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conselho_id');
            // Usar integer simples para compatibilidade com tabela legada 'estudantesv2'
            $table->integer('estudante_id');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            // FK para conselhos (tabela nova, tipo compatível)
            $table->foreign('conselho_id')->references('id')->on('conselhos')->onDelete('cascade');

            $table->unique(['conselho_id', 'estudante_id']);
            $table->index('estudante_id');
        });

        // Adicionar FK para estudantesv2 manualmente (tabela legada)
        if (Schema::hasTable('estudantesv2')) {
            try {
                DB::statement('ALTER TABLE conselho_estudante ADD CONSTRAINT conselho_estudante_estudante_id_foreign FOREIGN KEY (estudante_id) REFERENCES estudantesv2(id) ON DELETE CASCADE');
            } catch (\Exception $e) {
                Log::warning('Não foi possível criar FK conselho_estudante_estudante_id_foreign: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conselho_estudante');
    }
};
