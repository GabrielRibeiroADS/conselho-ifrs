@extends('layouts.admin')

@section('title', $reuniao->titulo . ' - ' . $conselho->descricao_completa)

@section('header', $reuniao->titulo)

@section('header-actions')
    <a href="{{ route('admin.reunioes.index', $conselho) }}" class="btn btn-outline-success">
        <i class="bi bi-arrow-left"></i> Voltar às Reuniões
    </a>
    <a href="{{ route('admin.reunioes.edit', [$conselho, $reuniao]) }}" class="btn btn-outline-success">
        <i class="bi bi-pencil"></i> Editar Reunião
    </a>
    @if(!$reuniao->isFinalizada())
    <form action="{{ route('admin.reunioes.finalizar', [$conselho, $reuniao]) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success" onclick="return confirm('Finalizar esta reunião?')">
            <i class="bi bi-check-circle"></i> Finalizar Reunião
        </button>
    </form>
    @endif
@endsection

@section('content')
{{-- Informações da Reunião --}}
<div class="card mb-4">
    <div class="card-header">
        <strong>Informações da Reunião</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <p><strong>Conselho:</strong> {{ $conselho->descricao_completa }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Data:</strong> {{ $reuniao->data_reuniao ? $reuniao->data_reuniao->format('d/m/Y') : 'Não definida' }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Status:</strong> 
                    <span class="badge {{ $reuniao->status_badge_class }}">{{ $reuniao->status_label }}</span>
                </p>
            </div>
            <div class="col-md-5">
                @if($reuniao->observacoes)
                <p><strong>Observações:</strong> {{ $reuniao->observacoes }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Avaliações dos Estudantes --}}
<div class="card">
    <div class="card-header">
        <strong>Avaliações dos Estudantes</strong>
        <span class="badge badge-secondary ml-2">{{ $reuniao->avaliacoes->count() }} estudantes</span>
    </div>
    <div class="card-body p-0">
        @if($reuniao->avaliacoes->isEmpty())
            <div class="alert alert-info m-3">
                <i class="bi bi-info-circle"></i>
                Nenhum estudante para avaliar nesta reunião.
            </div>
        @else
            <div class="accordion" id="accordionAvaliacoes">
                @foreach($reuniao->avaliacoes->sortBy('estudante.nome') as $index => $avaliacao)
                <div class="card mb-0 border-left-0 border-right-0 {{ $loop->first ? 'border-top-0' : '' }}">
                    <div class="card-header p-0" id="heading{{ $avaliacao->id }}">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center p-3 {{ $index > 0 ? 'collapsed' : '' }}" 
                                    type="button" 
                                    data-toggle="collapse" 
                                    data-target="#collapse{{ $avaliacao->id }}" 
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                                    aria-controls="collapse{{ $avaliacao->id }}">
                                <span>
                                    <i class="bi bi-person"></i>
                                    {{ $avaliacao->estudante->nome ?? 'Estudante não encontrado' }}
                                    @if(!$avaliacao->presente)
                                        <span class="badge badge-warning ml-2">Ausente</span>
                                    @endif
                                </span>
                                <span>
                                    @if($avaliacao->hasAvaliacao())
                                        <span class="badge badge-success">Avaliado</span>
                                    @else
                                        <span class="badge badge-secondary">Pendente</span>
                                    @endif
                                    @if($avaliacao->encaminhamentos->count() > 0)
                                        <span class="badge badge-info">{{ $avaliacao->encaminhamentos->count() }} encaminhamento(s)</span>
                                    @endif
                                </span>
                            </button>
                        </h2>
                    </div>

                    <div id="collapse{{ $avaliacao->id }}" 
                         class="collapse {{ $index === 0 ? 'show' : '' }}" 
                         aria-labelledby="heading{{ $avaliacao->id }}" 
                         data-parent="#accordionAvaliacoes">
                        <div class="card-body">
                            {{-- Formulário de Avaliação --}}
                            <form action="{{ route('admin.reunioes.avaliacoes.salvar', [$conselho, $reuniao, $avaliacao]) }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-10">
                                        <label for="avaliacao_{{ $avaliacao->id }}">Avaliação / Parecer:</label>
                                        <textarea name="avaliacao" 
                                                  id="avaliacao_{{ $avaliacao->id }}" 
                                                  rows="3" 
                                                  class="form-control"
                                                  placeholder="Digite a avaliação do estudante..."
                                                  {{ $reuniao->isFinalizada() ? 'readonly' : '' }}>{{ $avaliacao->avaliacao }}</textarea>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Presença:</label>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="hidden" name="presente" value="0">
                                            <input type="checkbox" 
                                                   class="custom-control-input" 
                                                   id="presente_{{ $avaliacao->id }}" 
                                                   name="presente" 
                                                   value="1"
                                                   {{ $avaliacao->presente ? 'checked' : '' }}
                                                   {{ $reuniao->isFinalizada() ? 'disabled' : '' }}>
                                            <label class="custom-control-label" for="presente_{{ $avaliacao->id }}">Presente</label>
                                        </div>
                                    </div>
                                </div>
                                @if(!$reuniao->isFinalizada())
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check"></i> Salvar Avaliação
                                </button>
                                @endif
                            </form>

                            <hr>

                            {{-- Encaminhamentos --}}
                            <div class="mt-3">
                                <h6><i class="bi bi-signpost-split"></i> Encaminhamentos</h6>
                                
                                @if($avaliacao->encaminhamentos->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Descrição</th>
                                                <th width="200" class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($avaliacao->encaminhamentos as $encaminhamento)
                                            <tr>
                                                <td>
                                                    {{ $encaminhamento->descricao }}
                                                    @if($encaminhamento->observacoes)
                                                        <br><small class="text-muted">{{ $encaminhamento->observacoes }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center" style="gap: 8px;">
                                                        {{-- Alterar Status --}}
                                                        <form action="{{ route('admin.reunioes.encaminhamentos.atualizar', [$conselho, $reuniao, $encaminhamento]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <select name="status" class="custom-select custom-select-sm" style="width: auto;" onchange="this.form.submit()">
                                                                @foreach(\App\Models\Encaminhamento::statusList() as $statusKey => $statusLabel)
                                                                    <option value="{{ $statusKey }}" {{ $encaminhamento->status === $statusKey ? 'selected' : '' }}>
                                                                        {{ $statusLabel }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </form>
                                                        {{-- Excluir --}}
                                                        <form action="{{ route('admin.reunioes.encaminhamentos.excluir', [$conselho, $reuniao, $encaminhamento]) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                                    onclick="return confirm('Excluir este encaminhamento?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <p class="text-muted">Nenhum encaminhamento registrado.</p>
                                @endif

                                {{-- Adicionar Encaminhamento --}}
                                @if(!$reuniao->isFinalizada())
                                <form action="{{ route('admin.reunioes.encaminhamentos.adicionar', [$conselho, $reuniao, $avaliacao]) }}" 
                                      method="POST" class="mt-2">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" 
                                               name="descricao" 
                                               class="form-control" 
                                               placeholder="Novo encaminhamento (ex: Encaminhar para atendimento psicológico, Elogio por bom desempenho...)"
                                               required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-plus"></i> Adicionar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
