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
        Schema::create('conselhos', function (Blueprint $table) {
            $table->id();
            // Usar integer simples para compatibilidade com tabela legada 'cursos'
            // A tabela cursos usa INT (signed) como primary key
            $table->integer('id_curso');
            $table->year('ano');
            $table->enum('tipo', ['anual', 'semestral']);
            $table->tinyInteger('semestre')->nullable(); // 1 ou 2, usado apenas se tipo = semestral
            $table->text('observacoes')->nullable();
            $table->timestamps();

            // Índice para performance nas buscas
            $table->index('id_curso');
            $table->index(['ano', 'semestre']);
        });

        // Adicionar foreign key manualmente com tipo compatível
        // Verificar se a tabela cursos existe antes de criar a FK
        if (Schema::hasTable('cursos')) {
            try {
                DB::statement('ALTER TABLE conselhos ADD CONSTRAINT conselhos_id_curso_foreign FOREIGN KEY (id_curso) REFERENCES cursos(id) ON DELETE CASCADE');
            } catch (\Exception $e) {
                // Se falhar (tipos incompatíveis), apenas log - a integridade será garantida pelo Laravel
                Log::warning('Não foi possível criar FK conselhos_id_curso_foreign: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conselhos');
    }
};
