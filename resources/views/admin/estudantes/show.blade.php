@extends('layouts.admin')

@section('title', 'Detalhes do Estudante')

@section('header', $estudante->nome)

@section('header-actions')
    @can('estudantes.edit')
    <a href="{{ route('admin.estudantes.edit', $estudante) }}" class="btn btn-primary">
        <i class="bi bi-pencil"></i> Editar
    </a>
    @endcan
    <a href="{{ route('admin.estudantes.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
@endsection

@section('content')
<div class="row">
    {{-- Dados Pessoais --}}
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person"></i> Dados Pessoais
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="30%">Nome:</th>
                        <td>{{ $estudante->nome }}</td>
                    </tr>
                    <tr>
                        <th>CPF:</th>
                        <td>{{ $estudante->cpf }}</td>
                    </tr>
                    <tr>
                        <th>E-mail:</th>
                        <td>{{ $estudante->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Telefone:</th>
                        <td>{{ $estudante->telefone ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Matrículas --}}
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-mortarboard"></i> Matrículas
            </div>
            <div class="card-body">
                @if($estudante->matriculas->count() > 0)
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Curso</th>
                            <th>Campus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudante->matriculas as $matricula)
                        <tr>
                            <td>{{ $matricula->no_matricula }}</td>
                            <td>{{ $matricula->curso->nome ?? '-' }}</td>
                            <td>{{ $matricula->curso->unidade->nome ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted mb-0">Nenhuma matrícula encontrada.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
