@extends('layouts.admin')

@section('title', 'Conselhos')

@section('header', 'Conselhos de Classe')

@section('header-actions')
    @if(auth()->user()->isAdmin() || auth()->user()->can('conselhos.create'))
    <a href="{{ route('admin.conselhos.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> Novo Conselho
    </a>
    @endif
@endsection

@section('content')
{{-- Filtros --}}
<form action="{{ route('admin.conselhos.index') }}" method="GET" class="mb-4">
    <div class="row">
        <div class="form-group col-md-3">
            <label for="curso">Curso:</label>
            <select name="curso" id="curso" class="custom-select">
                <option value="-1">--- Todos os Cursos ---</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" {{ request('curso') == $curso->id ? 'selected' : '' }}>
                        {{ $curso->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="ano">Ano:</label>
            <input type="number" name="ano" id="ano" class="form-control" value="{{ request('ano') }}" placeholder="Ex: 2026">
        </div>
        <div class="form-group col-md-2">
            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" class="custom-select">
                <option value="-1">--- Todos ---</option>
                <option value="anual" {{ request('tipo') == 'anual' ? 'selected' : '' }}>Anual</option>
                <option value="semestral" {{ request('tipo') == 'semestral' ? 'selected' : '' }}>Semestral</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-outline-success btn-block">
                <i class="bi bi-search"></i> Filtrar
            </button>
        </div>
        <div class="form-group col-md-2">
            <label>&nbsp;</label>
            <a href="{{ route('admin.conselhos.index') }}" class="btn btn-outline-secondary btn-block">
                <i class="bi bi-x-lg"></i> Limpar
            </a>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table id="tabela-conselhos" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Período</th>
                <th>Tipo</th>
                <th>Estudantes</th>
                <th width="220">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($conselhos as $conselho)
            <tr>
                <td>{{ $conselho->curso->nome ?? '-' }}</td>
                <td>{{ $conselho->periodo }}</td>
                <td>
                    @if($conselho->tipo === 'anual')
                        <span class="badge badge-info">Anual</span>
                    @else
                        <span class="badge badge-primary">Semestral</span>
                    @endif
                </td>
                <td>
                    <span class="badge badge-secondary">{{ $conselho->estudantes->count() }}</span>
                </td>
                <td>
                    @if(auth()->user()->isAdmin() || auth()->user()->can('conselhos.show'))
                    <a href="{{ route('admin.conselhos.show', $conselho) }}" class="btn btn-sm btn-outline-success" title="Visualizar">
                        <i class="bi bi-eye text-success"></i>
                    </a>
                    @endif

                    {{-- Botão Reuniões --}}
                    <a href="{{ route('admin.reunioes.index', $conselho) }}" class="btn btn-sm btn-outline-success" title="Reuniões">
                        <i class="bi bi-calendar-event"></i>
                    </a>

                    @if(auth()->user()->isAdmin() || auth()->user()->can('conselhos.edit'))
                    <a href="{{ route('admin.conselhos.edit', $conselho) }}" class="btn btn-sm btn-outline-success" title="Editar">
                        <i class="bi bi-pencil text-success"></i>
                    </a>
                    @endif

                    @if(auth()->user()->isAdmin() || auth()->user()->can('conselhos.delete'))
                    <form action="{{ route('admin.conselhos.destroy', $conselho) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este conselho?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-success" title="Excluir">
                            <i class="bi bi-trash text-success"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Nenhum conselho cadastrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginação --}}
<div class="d-flex justify-content-center mt-3">
    {{ $conselhos->appends(request()->query())->links() }}
</div>
@endsection
