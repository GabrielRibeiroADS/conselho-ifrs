<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conselho extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'conselhos';

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'id_curso',
        'ano',
        'tipo',
        'semestre',
        'observacoes',
    ];

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'ano' => 'integer',
            'semestre' => 'integer',
        ];
    }

    /**
     * Opções de tipo
     */
    public static function tipos(): array
    {
        return [
            'anual' => 'Anual',
            'semestral' => 'Semestral',
        ];
    }

    /**
     * Opções de semestre
     */
    public static function semestres(): array
    {
        return [
            1 => '1º Semestre',
            2 => '2º Semestre',
        ];
    }

    /**
     * Relacionamento com Curso
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    /**
     * Relacionamento com Estudantes (many-to-many)
     */
    public function estudantes()
    {
        return $this->belongsToMany(Estudante::class, 'conselho_estudante', 'conselho_id', 'estudante_id')
            ->withPivot('observacoes')
            ->withTimestamps();
    }

    /**
     * Obter descrição do período
     */
    public function getPeriodoAttribute(): string
    {
        if ($this->tipo === 'semestral' && $this->semestre) {
            return $this->ano . '/' . $this->semestre;
        }
        return (string) $this->ano;
    }

    /**
     * Obter descrição completa
     */
    public function getDescricaoCompletaAttribute(): string
    {
        $curso = $this->curso ? $this->curso->nome : 'Curso não definido';
        $tipo = self::tipos()[$this->tipo] ?? $this->tipo;
        return "{$curso} - {$tipo} ({$this->periodo})";
    }
}
