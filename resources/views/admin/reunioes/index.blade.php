@extends('layouts.admin')

@section('title', 'Reuniões - ' . $conselho->descricao_completa)

@section('header', 'Reuniões do Conselho')

@section('header-actions')
    <a href="{{ route('admin.conselhos.show', $conselho) }}" class="btn btn-outline-success">
        <i class="bi bi-arrow-left"></i> Voltar ao Conselho
    </a>
@endsection

@section('content')
{{-- Informações do Conselho --}}
<div class="card mb-4">
    <div class="card-header">
        <strong>{{ $conselho->descricao_completa }}</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <p><strong>Curso:</strong> {{ $conselho->curso->nome ?? '-' }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Período:</strong> {{ $conselho->periodo }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Tipo:</strong> 
                    <span class="badge {{ $conselho->tipo === 'anual' ? 'badge-info' : 'badge-primary' }}">
                        {{ ucfirst($conselho->tipo) }}
                    </span>
                </p>
            </div>
            <div class="col-md-2">
                <p><strong>Estudantes:</strong> 
                    <span class="badge badge-secondary">{{ $conselho->estudantes->count() }}</span>
                </p>
            </div>
            <div class="col-md-2">
                <p><strong>Progresso:</strong> 
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $conselho->progresso }}%">
                            {{ $conselho->progresso }}%
                        </div>
                    </div>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Reuniões --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Reuniões</strong>
        @if($conselho->reunioes->isEmpty() && $conselho->estudantes->count() > 0)
            <form action="{{ route('admin.reunioes.gerar', $conselho) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-lg"></i> Gerar Reuniões
                </button>
            </form>
        @endif
    </div>
    <div class="card-body">
        @if($conselho->estudantes->isEmpty())
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                Este conselho não possui estudantes. Adicione estudantes antes de gerar as reuniões.
                <a href="{{ route('admin.conselhos.edit', $conselho) }}" class="alert-link">Editar Conselho</a>
            </div>
        @elseif($conselho->reunioes->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Nenhuma reunião cadastrada. Clique em "Gerar Reuniões" para criar as reuniões automaticamente.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Título</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Avaliações</th>
                            <th>Encaminhamentos</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conselho->reunioes as $reuniao)
                        <tr>
                            <td>{{ $reuniao->numero }}</td>
                            <td>{{ $reuniao->titulo }}</td>
                            <td>{{ $reuniao->data_reuniao ? $reuniao->data_reuniao->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="badge {{ $reuniao->status_badge_class }}">
                                    {{ $reuniao->status_label }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ $reuniao->estudantes_avaliados_count }}/{{ $conselho->estudantes->count() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $reuniao->total_encaminhamentos }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.reunioes.show', [$conselho, $reuniao]) }}" 
                                   class="btn btn-sm btn-outline-success" title="Gerenciar">
                                    <i class="bi bi-list-check"></i>
                                </a>
                                <a href="{{ route('admin.reunioes.edit', [$conselho, $reuniao]) }}" 
                                   class="btn btn-sm btn-outline-success" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(!$reuniao->isFinalizada())
                                <form action="{{ route('admin.reunioes.finalizar', [$conselho, $reuniao]) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja finalizar esta reunião?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Finalizar">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
