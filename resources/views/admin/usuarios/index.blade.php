@extends('layouts.admin')

@section('title', 'Usuários')

@section('header', 'Usuários do Sistema')

@push('styles')
<style>
    .btn-outline-success:hover .text-success {
        color: #fff !important;
    }
</style>
@endpush

@section('header-actions')
    @if($usuarioLogado->isAdmin() || auth()->user()->can('usuarios.create'))
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> Novo Usuário
    </a>
    @endif
@endsection

@section('content')
{{-- Filtros --}}
@if($usuarioLogado->unidade && $usuarioLogado->unidade->isGestora())
<form action="{{ route('admin.usuarios.index') }}" method="GET" class="mb-4">
    <div class="row">
        <div class="form-group col-md-6">
            <label for="unidade">Unidade:</label>
            <select name="unidade" id="unidade" class="custom-select">
                <option value="-1">--- Todas as Unidades ---</option>
                @foreach($unidades as $unidade)
                    <option value="{{ $unidade->id }}" {{ request('unidade') == $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-outline-success btn-block">
                <i class="bi bi-search"></i> Filtrar
            </button>
        </div>
    </div>
</form>
@endif

<div class="table-responsive">
    <table id="tabela-usuarios" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Unidade</th>
                <th>Nome do Usuário</th>
                <th>E-mail</th>
                <th>Papéis/Profissões</th>
                <th width="180">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->unidade->nome ?? '-' }}</td>
                <td>{{ $usuario->nome }}</td>
                <td>{{ $usuario->email }}</td>
                <td>
                    @if($usuario->roles->isEmpty())
                        <span class="badge badge-secondary">Sem papel</span>
                    @else
                        {{ $usuario->roles->pluck('name')->implode(', ') }}
                    @endif
                </td>
                <td>
                    @if($usuarioLogado->isAdmin() || auth()->user()->can('usuarios.edit'))
                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-success" title="Editar">
                        <i class="bi bi-pencil text-success"></i>
                    </a>
                    
                    <form action="{{ route('admin.usuarios.reset-password', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja resetar a senha deste usuário?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success" title="Resetar Senha">
                            <i class="bi bi-key text-success"></i>
                        </button>
                    </form>
                    @endif
                    
                    @if($usuarioLogado->isAdmin() || auth()->user()->can('usuarios.delete'))
                    @if($usuario->id != auth()->id())
                    <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Nenhum usuário encontrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tabela-usuarios').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
        },
        order: [[1, 'asc']],
        pageLength: 25
    });
});
</script>
@endpush
