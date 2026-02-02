@extends('layouts.admin')

@section('title', 'Estudantes')

@section('header', 'Estudantes')

@push('styles')
<style>
    .btn-outline-success:hover .text-success {
        color: #fff !important;
    }
</style>
@endpush

@section('header-actions')
    @can('estudantes.create')
    <a href="{{ route('admin.estudantes.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> Novo Estudante
    </a>
    @endcan
@endsection

@section('content')
{{-- Filtros --}}
<form action="{{ route('admin.estudantes.index') }}" method="GET" class="mb-4">
    <div class="row">
        <div class="form-group col-md-4">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ request('nome') }}" placeholder="Buscar por nome...">
        </div>
        <div class="form-group col-md-3">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" class="form-control" value="{{ request('cpf') }}" placeholder="Buscar por CPF...">
        </div>
        <div class="form-group col-md-3">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-outline-success btn-block">
                <i class="bi bi-search"></i> Buscar
            </button>
        </div>
        <div class="form-group col-md-2">
            <label>&nbsp;</label>
            <a href="{{ route('admin.estudantes.index') }}" class="btn btn-outline-secondary btn-block">
                <i class="bi bi-x-lg"></i> Limpar
            </a>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table id="tabela-estudantes" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th width="150">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudantes as $estudante)
            <tr>
                <td>{{ $estudante->nome }}</td>
                <td>{{ $estudante->cpf }}</td>
                <td>{{ $estudante->email ?? '-' }}</td>
                <td>{{ $estudante->telefone ?? '-' }}</td>
                <td>
                    @can('estudantes.show')
                    <a href="{{ route('admin.estudantes.show', $estudante) }}" class="btn btn-sm btn-outline-success" title="Visualizar">
                        <i class="bi bi-eye text-success"></i>
                    </a>
                    @endcan
                    
                    @can('estudantes.edit')
                    <a href="{{ route('admin.estudantes.edit', $estudante) }}" class="btn btn-sm btn-outline-success" title="Editar">
                        <i class="bi bi-pencil text-success"></i>
                    </a>
                    @endcan
                    
                    @can('estudantes.delete')
                    <form action="{{ route('admin.estudantes.destroy', $estudante) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este estudante?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-success" title="Excluir">
                            <i class="bi bi-trash text-success"></i>
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Nenhum estudante encontrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginação --}}
<div class="d-flex justify-content-center mt-3">
    {{ $estudantes->appends(request()->query())->links() }}
</div>
@endsection
