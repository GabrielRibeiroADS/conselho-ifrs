<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'cursos';

    /**
     * Chave primária
     */
    protected $primaryKey = 'id';

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'nome',
        'modalidade',
        'turno',
        'ead',
        'id_unidade',
    ];

    /**
     * Desabilitar timestamps padrão do Laravel
     */
    public $timestamps = false;

    /**
     * Campo de data de atualização customizado
     */
    const UPDATED_AT = 'data_atualizacao';

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'ead' => 'boolean',
            'data_atualizacao' => 'datetime',
        ];
    }

    /**
     * Modalidades disponíveis
     */
    public static function modalidades(): array
    {
        return [
            'EDUCAÇÃO DE JOVENS E ADULTOS' => 'Educação Profissional de Jovens e Adultos',
            'CONCOMITANTE' => 'Técnico Concomitante ao Ensino Médio',
            'CONCOMITANTE E/OU SUBSEQUENTE' => 'Técnico Concomitante e/ou Subsequente ao Ensino Médio',
            'FIC' => 'Formação Inicial e Continuada',
            'INTEGRADO' => 'Técnico Integrado ao Ensino Médio',
            'SUBSEQUENTE' => 'Técnico Subsequente ao Ensino Médio',
            'SUPERIOR' => 'Ensino Superior',
            'FORMACAO_PEDAGOGICA' => 'Formação Pedagógica',
            'PÓS-GRADUAÇÃO' => 'Pós-Graduação',
        ];
    }

    /**
     * Relacionamento com Unidade
     */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'id_unidade');
    }

    /**
     * Relacionamento com Matrículas
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_curso');
    }

    /**
     * Verificar se é EAD
     */
    public function isEad(): bool
    {
        return (bool) $this->ead;
    }

    /**
     * Obter nome da modalidade
     */
    public function getNomeModalidadeAttribute(): string
    {
        return self::modalidades()[$this->modalidade] ?? $this->modalidade;
    }
}
