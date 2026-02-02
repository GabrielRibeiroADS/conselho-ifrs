<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'unidades';

    /**
     * Chave primária
     */
    protected $primaryKey = 'id';

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'nome',
        'pasta_base',
        'usar_drive',
        'conta_drive',
        'acesso_drive',
        'email_suporte',
        'unidade_gestora',
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
            'usar_drive' => 'boolean',
            'unidade_gestora' => 'boolean',
            'data_atualizacao' => 'datetime',
        ];
    }

    /**
     * Relacionamento com Cursos
     */
    public function cursos()
    {
        return $this->hasMany(Curso::class, 'id_unidade');
    }

    /**
     * Relacionamento com Usuários
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_unidade');
    }

    /**
     * Verificar se é unidade gestora
     */
    public function isGestora(): bool
    {
        return (bool) $this->unidade_gestora;
    }
}
