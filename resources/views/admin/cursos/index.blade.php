@extends('layouts.admin')

@section('title', 'Cursos')

@section('header', 'Cursos')

@section('header-actions')
    @can('cursos.create')
    <a href="{{ route('admin.cursos.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> Novo Curso
    </a>
    @endcan
@endsection

@section('content')
{{-- Filtros --}}
<form action="{{ route('admin.cursos.index') }}" method="GET" class="mb-4">
    <div class="row">
        <div class="form-group col-md-4">
            <label for="modalidade">Modalidade:</label>
            <select id="modalidade" name="modalidade" class="form-control">
                <option value="%">--- Todas as Modalidades ---</option>
                @foreach($modalidades as $key => $label)
                    <option value="{{ $key }}" {{ request('modalidade') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="unidade">Unidade:</label>
            <select name="unidade" id="unidade" class="custom-select">
                <option value="-1">--- Todas as Unidades ---</option>
                @foreach($unidades as $unidade)
                    <option value="{{ $unidade->id }}" {{ request('unidade') == $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-outline-success btn-block">
                <i class="bi bi-search"></i> Filtrar
            </button>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table id="tabela-cursos" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Nome do Curso</th>
                <th>Modalidade</th>
                <th>EAD?</th>
                <th>Turno</th>
                <th>Unidade</th>
                <th width="150">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cursos as $curso)
            <tr>
                <td>{{ $curso->nome }}</td>
                <td>{{ $curso->nome_modalidade }}</td>
                <td>
                    @if($curso->ead)
                        <span class="badge badge-info">Sim</span>
                    @else
                        <span class="badge badge-secondary">Não</span>
                    @endif
                </td>
                <td>{{ $curso->turno }}</td>
                <td>{{ $curso->unidade->nome ?? '-' }}</td>
                <td>
                    @can('cursos.edit')
                    <a href="{{ route('admin.cursos.edit', $curso) }}" class="btn btn-sm btn-outline-success" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @endcan
                    
                    @can('cursos.delete')
                    <form action="{{ route('admin.cursos.destroy', $curso) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este curso?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-success" title="Excluir">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Nenhum curso encontrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tabela-cursos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
        },
        order: [[0, 'asc']],
        pageLength: 25
    });
});
</script>
@endpush
