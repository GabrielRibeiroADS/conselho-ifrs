@extends('layouts.admin')

@section('title', $titulo)

@section('header', $titulo)

@section('content')
<p>* Campos obrigatórios.</p>

@php
    $permissoesSelecionadas = old('permissions', $papel->permissions->pluck('id')->all());
@endphp

<form action="{{ $papel->exists ? route('admin.papeis.update', $papel) : route('admin.papeis.store') }}" method="POST">
    @csrf
    @if($papel->exists)
        @method('PUT')
    @endif

    <div class="form-group">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Dados do Papel/Profissão</legend>

            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="name">* Nome:</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $papel->name) }}"
                           maxlength="255"
                           class="form-control @error('name') is-invalid @enderror"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </fieldset>
    </div>

    <div class="form-group">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Permissões</legend>

            @if($permissoes->isEmpty())
                <p class="mb-0">Nenhuma permissão cadastrada.</p>
            @else
                @php
                    // Agrupar permissões por módulo (prefixo antes do ponto)
                    $grupos = $permissoes->groupBy(function($permissao) {
                        $partes = explode('.', $permissao->name);
                        return $partes[0];
                    })->sortKeys();
                    
                    // Tradução dos nomes dos módulos
                    $traducoes = [
                        'analisesocio' => 'Análise Socioeconômica',
                        'complementos' => 'Complementos',
                        'conselhos' => 'Conselhos',
                        'cursos' => 'Cursos',
                        'estudantes' => 'Estudantes',
                        'mapeamentos' => 'Mapeamentos',
                        'papeis' => 'Papéis/Profissões',
                        'recursos' => 'Recursos',
                        'relatorios' => 'Relatórios',
                        'unidades' => 'Unidades',
                        'usuarios' => 'Usuários',
                    ];
                    
                    // Tradução das ações
                    $acoes = [
                        'index' => 'Listar',
                        'show' => 'Visualizar',
                        'create' => 'Criar',
                        'edit' => 'Editar',
                        'delete' => 'Excluir',
                        'export' => 'Exportar',
                    ];
                @endphp

                <div class="row">
                    @foreach($grupos as $modulo => $permissoesDoGrupo)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light py-2">
                                    <strong>{{ $traducoes[$modulo] ?? ucfirst($modulo) }}</strong>
                                    <div class="float-right">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" 
                                                   class="custom-control-input selecionar-todos-grupo" 
                                                   id="grupo_{{ $modulo }}"
                                                   data-grupo="{{ $modulo }}">
                                            <label class="custom-control-label small" for="grupo_{{ $modulo }}">Todos</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-2">
                                    @foreach($permissoesDoGrupo as $permissao)
                                        @php
                                            $partes = explode('.', $permissao->name);
                                            $acao = $partes[1] ?? $permissao->name;
                                        @endphp
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input perm-{{ $modulo }}"
                                                   id="perm_{{ $permissao->id }}"
                                                   name="permissions[]"
                                                   value="{{ $permissao->id }}"
                                                   {{ in_array($permissao->id, $permissoesSelecionadas, true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="perm_{{ $permissao->id }}">
                                                {{ $acoes[$acao] ?? ucfirst($acao) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </fieldset>
    </div>

    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Salvar Papel
        </button>
        <a href="{{ route('admin.papeis.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left"></i> Voltar para Lista de Papéis
        </a>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Selecionar/desmarcar todas as permissões de um grupo
    $('.selecionar-todos-grupo').change(function() {
        var grupo = $(this).data('grupo');
        $('.perm-' + grupo).prop('checked', $(this).is(':checked'));
    });

    // Atualizar checkbox "Todos" quando permissões individuais mudam
    $('.custom-control-input[id^="perm_"]').change(function() {
        var classes = $(this).attr('class').split(' ');
        var grupoClass = classes.find(c => c.startsWith('perm-'));
        if (grupoClass) {
            var grupo = grupoClass.replace('perm-', '');
            var total = $('.perm-' + grupo).length;
            var checked = $('.perm-' + grupo + ':checked').length;
            $('#grupo_' + grupo).prop('checked', total === checked);
        }
    });

    // Inicializar estado dos checkboxes "Todos"
    $('.selecionar-todos-grupo').each(function() {
        var grupo = $(this).data('grupo');
        var total = $('.perm-' + grupo).length;
        var checked = $('.perm-' + grupo + ':checked').length;
        $(this).prop('checked', total === checked && total > 0);
    });
});
</script>
@endpush
