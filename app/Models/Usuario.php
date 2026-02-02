<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Tabela associada ao model
     */
    protected $table = 'usuarios_admin';

    /**
     * Chave primária
     */
    protected $primaryKey = 'id';

    /**
     * Campos preenchíveis em massa
     */
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'trocar_senha',
        'id_unidade',
        'habilita_analisesocio',
        'habilita_comp_analisesocio',
        'habilita_rec_analisesocio',
        'habilita_analisecenso',
        'habilita_coord_unidade',
        'habilita_admin',
    ];

    /**
     * Campos ocultos na serialização
     */
    protected $hidden = [
        'senha',
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
     * Campo usado para autenticação (senha)
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }

    /**
     * Relacionamento com Unidade
     */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'id_unidade');
    }

    /**
     * Verificar se é administrador
     */
    public function isAdmin(): bool
    {
        return (bool) $this->habilita_admin;
    }

    /**
     * Verificar se precisa trocar senha
     */
    public function precisaTrocarSenha(): bool
    {
        return (bool) $this->trocar_senha;
    }

    /**
     * Verificar se tem permissão de análise socioeconômica
     */
    public function podeAnaliseSocio(): bool
    {
        return (bool) $this->habilita_analisesocio;
    }

    /**
     * Verificar se tem permissão de complemento de análise
     */
    public function podeComplementoAnaliseSocio(): bool
    {
        return (bool) $this->habilita_comp_analisesocio;
    }

    /**
     * Verificar se tem permissão de recurso de análise
     */
    public function podeRecursoAnaliseSocio(): bool
    {
        return (bool) $this->habilita_rec_analisesocio;
    }

    /**
     * Verificar se tem permissão de análise de censo
     */
    public function podeAnaliseCenso(): bool
    {
        return (bool) $this->habilita_analisecenso;
    }

    /**
     * Verificar se é coordenador de unidade
     */
    public function isCoordUnidade(): bool
    {
        return (bool) $this->habilita_coord_unidade;
    }
}
