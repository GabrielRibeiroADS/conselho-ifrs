@extends('layouts.admin')

@section('title', 'Unidades')

@section('header', 'Unidades do Sistema')

@section('header-actions')
    @can('unidades.create')
    <a href="{{ route('admin.unidades.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> Nova Unidade
    </a>
    @endcan
@endsection

@section('content')
<div class="table-responsive">
    <table id="tabela-unidades" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Nome da Unidade</th>
                <th>E-mail de Suporte</th>
                <th>Unidade Gestora?</th>
                <th>Usa o Drive?</th>
                <th width="150">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unidades as $unidade)
            <tr>
                <td>{{ $unidade->nome }}</td>
                <td>{{ $unidade->email_suporte }}</td>
                <td>
                    @if($unidade->unidade_gestora)
                        <span class="badge badge-success">Sim</span>
                    @else
                        <span class="badge badge-secondary">Não</span>
                    @endif
                </td>
                <td>
                    @if($unidade->usar_drive)
                        <span class="badge badge-success">Sim</span>
                    @else
                        <span class="badge badge-secondary">Não</span>
                    @endif
                </td>
                <td>
                    @can('unidades.edit')
                    <a href="{{ route('admin.unidades.edit', $unidade) }}" class="btn btn-sm btn-outline-success" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @endcan
                    
                    @can('unidades.delete')
                    <form action="{{ route('admin.unidades.destroy', $unidade) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta unidade?');">
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
                <td colspan="5" class="text-center">Nenhuma unidade cadastrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tabela-unidades').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
        },
        order: [[0, 'asc']],
        pageLength: 25
    });
});
</script>
@endpush
