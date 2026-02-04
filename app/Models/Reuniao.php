<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reuniao extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'reunioes';

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'conselho_id',
        'titulo',
        'numero',
        'data_reuniao',
        'status',
        'observacoes',
    ];

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'numero' => 'integer',
            'data_reuniao' => 'date',
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
            'finalizada' => 'Finalizada',
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
            'finalizada' => 'badge-success',
            default => 'badge-secondary',
        };
    }

    /**
     * Relacionamento com Conselho
     */
    public function conselho()
    {
        return $this->belongsTo(Conselho::class);
    }

    /**
     * Relacionamento com Estudantes através da tabela pivot
     */
    public function estudantes()
    {
        return $this->belongsToMany(Estudante::class, 'reuniao_estudante', 'reuniao_id', 'estudante_id')
            ->withPivot(['id', 'avaliacao', 'presente'])
            ->withTimestamps();
    }

    /**
     * Relacionamento com avaliações (tabela pivot como model)
     */
    public function avaliacoes()
    {
        return $this->hasMany(ReuniaoEstudante::class, 'reuniao_id');
    }

    /**
     * Gerar reuniões padrão para um conselho
     */
    public static function gerarParaConselho(Conselho $conselho): array
    {
        $reunioes = [];

        if ($conselho->tipo === 'anual') {
            // 3 reuniões para conselho anual
            $titulos = [
                1 => '1ª Reunião - 1º Semestre',
                2 => '2ª Reunião - 2º Semestre',
                3 => 'Reunião Final',
            ];
        } else {
            // 2 reuniões para conselho semestral
            $titulos = [
                1 => '1ª Reunião - Intermediária',
                2 => 'Reunião Final',
            ];
        }

        foreach ($titulos as $numero => $titulo) {
            $reunioes[] = self::create([
                'conselho_id' => $conselho->id,
                'titulo' => $titulo,
                'numero' => $numero,
                'status' => 'pendente',
            ]);
        }

        return $reunioes;
    }

    /**
     * Verificar se a reunião está finalizada
     */
    public function isFinalizada(): bool
    {
        return $this->status === 'finalizada';
    }

    /**
     * Verificar se a reunião está pendente
     */
    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    /**
     * Contar estudantes avaliados
     */
    public function getEstudantesAvaliadosCountAttribute(): int
    {
        return $this->avaliacoes()->whereNotNull('avaliacao')->count();
    }

    /**
     * Contar total de encaminhamentos
     */
    public function getTotalEncaminhamentosAttribute(): int
    {
        return Encaminhamento::whereHas('reuniaoEstudante', function ($query) {
            $query->where('reuniao_id', $this->id);
        })->count();
    }
}
