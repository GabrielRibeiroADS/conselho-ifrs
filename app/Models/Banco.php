<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'bancos';

    /**
     * Chave primária
     */
    protected $primaryKey = 'numero';

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
        'numero',
        'nome',
    ];

    /**
     * Desabilitar timestamps
     */
    public $timestamps = false;

    /**
     * Relacionamento com Estudantes
     */
    public function estudantes()
    {
        return $this->hasMany(Estudante::class, 'numero_banco', 'numero');
    }
}
