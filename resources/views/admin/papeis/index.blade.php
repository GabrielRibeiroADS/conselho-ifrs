@extends('layouts.admin')

@section('title', 'Papéis/Profissões')

@section('header', 'Papéis/Profissões')

@section('header-actions')
    @can('papeis.create')
    <a href="{{ route('admin.papeis.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> Novo Papel
    </a>
    @endcan
@endsection

@section('content')
<div class="table-responsive">
    <table id="tabela-papeis" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Papel/Profissão</th>
                <th>Permissões</th>
                <th width="150">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($papeis as $papel)
            <tr>
                <td>{{ $papel->name }}</td>
                <td>{{ $papel->permissions_count }}</td>
                <td>
                    @can('papeis.edit')
                    <a href="{{ route('admin.papeis.edit', $papel) }}" class="btn btn-sm btn-outline-success" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @endcan

                    @can('papeis.delete')
                    <form action="{{ route('admin.papeis.destroy', $papel) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este papel?');">
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
                <td colspan="3" class="text-center">Nenhum papel cadastrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tabela-papeis').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
        },
        order: [[0, 'asc']],
        pageLength: 25
    });
});
</script>
@endpush
