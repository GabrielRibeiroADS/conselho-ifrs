<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encaminhamento extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'encaminhamentos';

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'reuniao_estudante_id',
        'descricao',
        'status',
        'observacoes',
        'data_conclusao',
    ];

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'data_conclusao' => 'date',
        ];
    }

    /**
     * Status disponíveis
     */
    public static function statusList(): array
    {
        return [
            'pendente' => 'Pendente',
            'em_andamento' => 'Em Andamento',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
        ];
    }

    /**
     * Obter label do status
     */
    public function getStatusLabelAttribute(): string
    {
        return self::statusList()[$this->status] ?? $this->status;
    }

    /**
     * Obter classe CSS do badge de status
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'badge-secondary',
            'em_andamento' => 'badge-warning',
            'concluido' => 'badge-success',
            'cancelado' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Relacionamento com ReuniaoEstudante
     */
    public function reuniaoEstudante()
    {
        return $this->belongsTo(ReuniaoEstudante::class, 'reuniao_estudante_id');
    }

    /**
     * Obter estudante através do relacionamento
     */
    public function getEstudanteAttribute()
    {
        return $this->reuniaoEstudante?->estudante;
    }

    /**
     * Obter reunião através do relacionamento
     */
    public function getReuniaoAttribute()
    {
        return $this->reuniaoEstudante?->reuniao;
    }

    /**
     * Verificar se está pendente
     */
    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    /**
     * Verificar se está concluído
     */
    public function isConcluido(): bool
    {
        return $this->status === 'concluido';
    }

    /**
     * Marcar como concluído
     */
    public function marcarConcluido(?string $observacoes = null): bool
    {
        $this->status = 'concluido';
        $this->data_conclusao = now();
        
        if ($observacoes) {
            $this->observacoes = $observacoes;
        }

        return $this->save();
    }
}
