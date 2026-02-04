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
     * Relacionamento com Reuniões
     */
    public function reunioes()
    {
        return $this->hasMany(Reuniao::class)->orderBy('numero');
    }

    /**
     * Obter número esperado de reuniões
     */
    public function getNumeroReunioesEsperadoAttribute(): int
    {
        return $this->tipo === 'anual' ? 3 : 2;
    }

    /**
     * Verificar se todas as reuniões foram criadas
     */
    public function hasTodasReunioes(): bool
    {
        return $this->reunioes()->count() >= $this->numero_reunioes_esperado;
    }

    /**
     * Verificar se todas as reuniões estão finalizadas
     */
    public function isCompleto(): bool
    {
        if (!$this->hasTodasReunioes()) {
            return false;
        }

        return $this->reunioes()->where('status', '!=', 'finalizada')->count() === 0;
    }

    /**
     * Obter progresso das reuniões (porcentagem)
     */
    public function getProgressoAttribute(): int
    {
        $total = $this->numero_reunioes_esperado;
        $finalizadas = $this->reunioes()->where('status', 'finalizada')->count();

        return $total > 0 ? (int) round(($finalizadas / $total) * 100) : 0;
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
