@extends('layouts.admin')

@section('title', 'Detalhes do Conselho')

@section('header', 'Detalhes do Conselho')

@section('header-actions')
    <a href="{{ route('admin.conselhos.index') }}" class="btn btn-outline-success">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    @if(auth()->user()->isAdmin() || auth()->user()->can('conselhos.edit'))
    <a href="{{ route('admin.conselhos.edit', $conselho) }}" class="btn btn-primary">
        <i class="bi bi-pencil"></i> Editar
    </a>
    @endif
@endsection

@section('content')
{{-- Dados do Conselho --}}
<div class="card mb-4">
    <div class="card-header">
        <strong>Informações do Conselho</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Curso:</strong> {{ $conselho->curso->nome ?? '-' }}</p>
            </div>
            <div class="col-md-3">
                <p><strong>Período:</strong> {{ $conselho->periodo }}</p>
            </div>
            <div class="col-md-3">
                <p><strong>Tipo:</strong>
                    @if($conselho->tipo === 'anual')
                        <span class="badge badge-info">Anual</span>
                    @else
                        <span class="badge badge-primary">Semestral</span>
                    @endif
                </p>
            </div>
        </div>
        @if($conselho->observacoes)
        <div class="row">
            <div class="col-md-12">
                <p><strong>Observações:</strong> {{ $conselho->observacoes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Estudantes --}}
<div class="card">
    <div class="card-header">
        <strong>Estudantes do Conselho</strong>
        <span class="badge badge-secondary ml-2">{{ $conselho->estudantes->count() }}</span>
    </div>
    <div class="card-body">
        @if($conselho->estudantes->isEmpty())
            <p class="text-center mb-0">Nenhum estudante vinculado a este conselho.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conselho->estudantes as $index => $estudante)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $estudante->nome }}</td>
                            <td>{{ $estudante->cpf }}</td>
                            <td>{{ $estudante->email ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
