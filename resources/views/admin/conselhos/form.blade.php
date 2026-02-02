@extends('layouts.admin')

@section('title', $titulo)

@section('header', $titulo)

@section('content')
<p>* Campos obrigatórios.</p>

@php
    $estudantesSelecionados = old('estudantes', $conselho->estudantes->pluck('id')->all());
@endphp

<form action="{{ $conselho->exists ? route('admin.conselhos.update', $conselho) : route('admin.conselhos.store') }}" method="POST">
    @csrf
    @if($conselho->exists)
        @method('PUT')
    @endif

    {{-- Dados do Conselho --}}
    <div class="form-group">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Dados do Conselho</legend>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_curso">* Curso:</label>
                    <select name="id_curso" id="id_curso" class="custom-select @error('id_curso') is-invalid @enderror" required>
                        <option value="">Selecione o Curso</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('id_curso', $conselho->id_curso) == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_curso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-2">
                    <label for="ano">* Ano:</label>
                    <input type="number"
                           name="ano"
                           id="ano"
                           value="{{ old('ano', $conselho->ano ?? date('Y')) }}"
                           min="2000"
                           max="2100"
                           class="form-control @error('ano') is-invalid @enderror"
                           required>
                    @error('ano')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-2">
                    <label for="tipo">* Tipo:</label>
                    <select name="tipo" id="tipo" class="custom-select @error('tipo') is-invalid @enderror" required>
                        <option value="anual" {{ old('tipo', $conselho->tipo) == 'anual' ? 'selected' : '' }}>Anual</option>
                        <option value="semestral" {{ old('tipo', $conselho->tipo) == 'semestral' ? 'selected' : '' }}>Semestral</option>
                    </select>
                    @error('tipo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-2" id="semestre-group" @if(old('tipo', $conselho->tipo) != 'semestral') style="display: none;" @endif>
                    <label for="semestre">* Semestre:</label>
                    <select name="semestre" id="semestre" class="custom-select @error('semestre') is-invalid @enderror">
                        <option value="">Selecione</option>
                        <option value="1" {{ old('semestre', $conselho->semestre) == 1 ? 'selected' : '' }}>1º Semestre</option>
                        <option value="2" {{ old('semestre', $conselho->semestre) == 2 ? 'selected' : '' }}>2º Semestre</option>
                    </select>
                    @error('semestre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="observacoes">Observações:</label>
                    <textarea name="observacoes"
                              id="observacoes"
                              rows="3"
                              class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes', $conselho->observacoes) }}</textarea>
                    @error('observacoes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </fieldset>
    </div>

    {{-- Estudantes --}}
    <div class="form-group">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Estudantes do Conselho</legend>

            @if($estudantes->isEmpty())
                <p class="mb-0 text-center">Nenhum estudante cadastrado.</p>
            @else
                <div class="mb-2">
                    <input type="text" id="busca-estudante" class="form-control" placeholder="Buscar estudante por nome ou CPF...">
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-hover" id="tabela-estudantes">
                        <thead class="thead-light">
                            <tr>
                                <th width="50">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="selecionar-todos">
                                        <label class="custom-control-label" for="selecionar-todos"></label>
                                    </div>
                                </th>
                                <th>Nome</th>
                                <th>CPF</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estudantes as $estudante)
                                <tr class="estudante-row">
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input estudante-check"
                                                   id="est_{{ $estudante->id }}"
                                                   name="estudantes[]"
                                                   value="{{ $estudante->id }}"
                                                   {{ in_array($estudante->id, $estudantesSelecionados) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="est_{{ $estudante->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $estudante->nome }}</td>
                                    <td>{{ $estudante->cpf }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">
                    <span id="contador-selecionados">{{ count($estudantesSelecionados) }}</span> estudante(s) selecionado(s)
                </small>
            @endif
        </fieldset>
    </div>

    {{-- Botões --}}
    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Salvar Conselho
        </button>
        <a href="{{ route('admin.conselhos.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left"></i> Voltar para Lista de Conselhos
        </a>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle semestre field
    $('#tipo').change(function() {
        if ($(this).val() === 'semestral') {
            $('#semestre-group').show();
        } else {
            $('#semestre-group').hide();
            $('#semestre').val('');
        }
    });

    // Busca de estudantes
    $('#busca-estudante').on('keyup', function() {
        var busca = $(this).val().toLowerCase();
        $('.estudante-row').each(function() {
            var nome = $(this).find('td:eq(1)').text().toLowerCase();
            var cpf = $(this).find('td:eq(2)').text().toLowerCase();
            if (nome.indexOf(busca) > -1 || cpf.indexOf(busca) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Selecionar todos
    $('#selecionar-todos').change(function() {
        $('.estudante-row:visible .estudante-check').prop('checked', $(this).is(':checked'));
        atualizarContador();
    });

    // Atualizar contador
    $('.estudante-check').change(function() {
        atualizarContador();
    });

    function atualizarContador() {
        var count = $('.estudante-check:checked').length;
        $('#contador-selecionados').text(count);
    }
});
</script>
@endpush
