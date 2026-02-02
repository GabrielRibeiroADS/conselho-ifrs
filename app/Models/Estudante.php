<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudante extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model
     */
    protected $table = 'estudantesv2';

    /**
     * Chave primária
     */
    protected $primaryKey = 'id';

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'cpf',
        'nome',
        'nome_social',
        'data_nascimento',
        'rg',
        'rg_orgao_expedidor',
        'rg_uf_expedidor',
        'sexo',
        'etnia',
        'estado_civil',
        'nacionalidade',
        'deficiencia',
        // Responsáveis
        'nome_mae',
        'nome_pai',
        'celular_mae',
        'celular_pai',
        // Contato
        'ddd_fone_fixo',
        'fone_fixo',
        'ddd_fone_recado',
        'fone_recado',
        'ddd_fone_celular',
        'fone_celular',
        'whats',
        'email',
        // Endereço
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        // Dados Bancários
        'numero_banco',
        'numero_agencia',
        'numero_tipo_conta',
        'numero_conta_corrente',
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
            'data_nascimento' => 'date',
            'whats' => 'boolean',
            'data_atualizacao' => 'datetime',
        ];
    }

    /**
     * Opções de Sexo
     */
    public static function sexos(): array
    {
        return [
            'MASCULINO' => 'Masculino',
            'FEMININO' => 'Feminino',
        ];
    }

    /**
     * Opções de Etnia
     */
    public static function etnias(): array
    {
        return [
            'BRANCA' => 'Branca',
            'PARDA' => 'Parda',
            'PRETA' => 'Preta',
            'INDIGENA' => 'Indígena',
            'AMARELA' => 'Amarela',
        ];
    }

    /**
     * Opções de Estado Civil
     */
    public static function estadosCivis(): array
    {
        return [
            'SOLTEIRO' => 'Solteiro(a)',
            'CASADO' => 'Casado(a)',
            'DIVORCIADO' => 'Divorciado(a)',
            'VIUVO' => 'Viúvo(a)',
            'UNIAO_ESTAVEL' => 'União Estável',
        ];
    }

    /**
     * Relacionamento com Matrículas
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_estudante');
    }

    /**
     * Relacionamento com Conselhos (many-to-many)
     */
    public function conselhos()
    {
        return $this->belongsToMany(Conselho::class, 'conselho_estudante', 'estudante_id', 'conselho_id')
            ->withPivot('observacoes')
            ->withTimestamps();
    }

    /**
     * Relacionamento com Banco
     */
    public function banco()
    {
        return $this->belongsTo(Banco::class, 'numero_banco', 'numero');
    }

    /**
     * Verificar se é menor de idade
     */
    public function isMenor(): bool
    {
        if (!$this->data_nascimento) {
            return false;
        }
        return $this->data_nascimento->age < 18;
    }

    /**
     * Obter nome de exibição (nome social ou nome)
     */
    public function getNomeExibicaoAttribute(): string
    {
        return $this->nome_social ?: $this->nome;
    }

    /**
     * Obter CPF formatado
     */
    public function getCpfFormatadoAttribute(): string
    {
        $cpf = preg_replace('/[^0-9]/', '', $this->cpf);
        if (strlen($cpf) === 11) {
            return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        }
        return $this->cpf;
    }

    /**
     * Obter celular completo
     */
    public function getCelularCompletoAttribute(): string
    {
        if ($this->ddd_fone_celular && $this->fone_celular) {
            return '(' . $this->ddd_fone_celular . ') ' . $this->fone_celular;
        }
        return $this->fone_celular ?? '';
    }

    /**
     * Obter endereço completo
     */
    public function getEnderecoCompletoAttribute(): string
    {
        $partes = array_filter([
            $this->logradouro,
            $this->numero ? 'nº ' . $this->numero : null,
            $this->complemento,
            $this->bairro,
            $this->cidade,
            $this->estado,
            $this->cep,
        ]);
        return implode(', ', $partes);
    }
}
