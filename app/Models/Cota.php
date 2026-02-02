<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cota extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'cotas';

    /**
     * Chave primária
     */
    protected $primaryKey = 'id';

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'desc_cota',
    ];

    /**
     * Desabilitar timestamps
     */
    public $timestamps = false;

    /**
     * Relacionamento com Matrículas
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'cota_ingresso');
    }
}
