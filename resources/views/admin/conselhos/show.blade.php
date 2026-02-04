@extends('layouts.admin')

@section('title', 'Detalhes do Conselho')

@section('header', 'Detalhes do Conselho')

@section('header-actions')
    <a href="{{ route('admin.conselhos.index') }}" class="btn btn-outline-success">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <a href="{{ route('admin.reunioes.index', $conselho) }}" class="btn btn-success">
        <i class="bi bi-calendar-event"></i> Reuniões
    </a>
    @if(auth()->user()->isAdmin() || auth()->user()->can('conselhos.edit'))
    <a href="{{ route('admin.conselhos.edit', $conselho) }}" class="btn btn-outline-success">
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

{{-- Reuniões --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Reuniões</strong>
        <a href="{{ route('admin.reunioes.index', $conselho) }}" class="btn btn-sm btn-success">
            <i class="bi bi-calendar-event"></i> Gerenciar Reuniões
        </a>
    </div>
    <div class="card-body">
        @if($conselho->reunioes->isEmpty())
            <p class="text-center mb-0">
                Nenhuma reunião cadastrada. 
                <a href="{{ route('admin.reunioes.index', $conselho) }}">Clique aqui para gerar as reuniões.</a>
            </p>
        @else
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Total de Reuniões:</strong> {{ $conselho->reunioes->count() }} de {{ $conselho->numero_reunioes_esperado }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Progresso:</strong></p>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $conselho->progresso }}%">
                            {{ $conselho->progresso }}%
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                @foreach($conselho->reunioes as $reuniao)
                <div class="col-md-4 mb-2">
                    <div class="card h-100">
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1">{{ $reuniao->titulo }}</h6>
                            <p class="card-text mb-1">
                                <span class="badge {{ $reuniao->status_badge_class }}">{{ $reuniao->status_label }}</span>
                            </p>
                            <a href="{{ route('admin.reunioes.show', [$conselho, $reuniao]) }}" class="btn btn-sm btn-outline-success">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
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
