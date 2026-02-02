<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'matriculas';

    /**
     * Chave primária
     */
    protected $primaryKey = 'no_matricula';

    /**
     * Tipo da chave primária
     */
    protected $keyType = 'string';

    /**
     * Auto-incremento desabilitado
     */
    public $incrementing = false;

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'no_matricula',
        'id_estudante',
        'id_curso',
        'cota_ingresso',
        'modo_ingresso',
        'ano_ingresso',
        'semestre_ingresso',
        'situacao',
        'sistema_academico',
        'numero_aulas',
        'numero_presencas',
        'frequencia',
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
            'ano_ingresso' => 'integer',
            'semestre_ingresso' => 'integer',
            'numero_aulas' => 'integer',
            'numero_presencas' => 'integer',
            'frequencia' => 'decimal:2',
            'data_atualizacao' => 'datetime',
        ];
    }

    /**
     * Situações disponíveis
     */
    public static function situacoes(): array
    {
        return [
            'ATIVO' => 'Ativo',
            'CANCELADO' => 'Cancelado',
            'FORMADO' => 'Formado',
            'TRANCADO' => 'Trancado',
            'INATIVO' => 'Inativo',
        ];
    }

    /**
     * Sistemas acadêmicos disponíveis
     */
    public static function sistemasAcademicos(): array
    {
        return [
            'SIGAA' => 'SIGAA',
            'SIA' => 'SIA',
            'QUALIDATA' => 'Qualidata',
            'CDIGITAL' => 'Campus Digital',
        ];
    }

    /**
     * Relacionamento com Estudante
     */
    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'id_estudante');
    }

    /**
     * Relacionamento com Curso
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    /**
     * Relacionamento com Cota
     */
    public function cota()
    {
        return $this->belongsTo(Cota::class, 'cota_ingresso');
    }

    /**
     * Verificar se está ativo
     */
    public function isAtivo(): bool
    {
        return $this->situacao === 'ATIVO';
    }

    /**
     * Obter nome da situação
     */
    public function getNomeSituacaoAttribute(): string
    {
        return self::situacoes()[$this->situacao] ?? $this->situacao;
    }
}
