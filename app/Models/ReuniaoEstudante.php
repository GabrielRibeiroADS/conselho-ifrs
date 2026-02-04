<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReuniaoEstudante extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'reuniao_estudante';

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'reuniao_id',
        'estudante_id',
        'avaliacao',
        'presente',
    ];

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'presente' => 'boolean',
        ];
    }

    /**
     * Relacionamento com Reunião
     */
    public function reuniao()
    {
        return $this->belongsTo(Reuniao::class);
    }

    /**
     * Relacionamento com Estudante
     */
    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    /**
     * Relacionamento com Encaminhamentos
     */
    public function encaminhamentos()
    {
        return $this->hasMany(Encaminhamento::class, 'reuniao_estudante_id');
    }

    /**
     * Verificar se possui avaliação
     */
    public function hasAvaliacao(): bool
    {
        return !empty($this->avaliacao);
    }

    /**
     * Verificar se possui encaminhamentos
     */
    public function hasEncaminhamentos(): bool
    {
        return $this->encaminhamentos()->count() > 0;
    }

    /**
     * Contar encaminhamentos pendentes
     */
    public function getEncaminhamentosPendentesCountAttribute(): int
    {
        return $this->encaminhamentos()->where('status', 'pendente')->count();
    }
}
