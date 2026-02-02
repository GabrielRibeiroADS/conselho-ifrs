@extends('layouts.admin')

@section('title', $titulo)

@section('header', $titulo)

@section('content')
<p>* Campos obrigatórios.</p>

<form action="{{ $curso->exists ? route('admin.cursos.update', $curso) : route('admin.cursos.store') }}" method="POST">
    @csrf
    @if($curso->exists)
        @method('PUT')
    @endif

    {{-- Dados do Curso --}}
    <div class="form-group">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Dados do Curso</legend>
            
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="nome">* Nome:</label>
                    <input type="text" 
                           name="nome" 
                           id="nome" 
                           value="{{ old('nome', $curso->nome) }}" 
                           maxlength="200" 
                           class="form-control @error('nome') is-invalid @enderror"
                           required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="id_unidade">* Unidade:</label>
                    <select name="id_unidade" id="id_unidade" class="custom-select @error('id_unidade') is-invalid @enderror" required>
                        <option value="">Selecione a Unidade</option>
                        @foreach($unidades as $unidade)
                            <option value="{{ $unidade->id }}" {{ old('id_unidade', $curso->id_unidade) == $unidade->id ? 'selected' : '' }}>
                                {{ $unidade->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_unidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="modalidade">* Modalidade:</label>
                    <select name="modalidade" id="modalidade" class="custom-select @error('modalidade') is-invalid @enderror" required>
                        <option value="">Selecione a Modalidade</option>
                        @foreach($modalidades as $key => $label)
                            <option value="{{ $key }}" {{ old('modalidade', $curso->modalidade) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('modalidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="turno">Turno:</label>
                    <select name="turno" id="turno" class="custom-select">
                        <option value="">Selecione o Turno</option>
                        <option value="Matutino" {{ old('turno', $curso->turno) == 'Matutino' ? 'selected' : '' }}>Matutino</option>
                        <option value="Vespertino" {{ old('turno', $curso->turno) == 'Vespertino' ? 'selected' : '' }}>Vespertino</option>
                        <option value="Noturno" {{ old('turno', $curso->turno) == 'Noturno' ? 'selected' : '' }}>Noturno</option>
                        <option value="Integral" {{ old('turno', $curso->turno) == 'Integral' ? 'selected' : '' }}>Integral</option>
                    </select>
                </div>
                
                <div class="form-group col-md-3">
                    <label for="ead">Curso EAD?</label>
                    <select name="ead" id="ead" class="custom-select">
                        <option value="0" {{ old('ead', $curso->ead) == 0 ? 'selected' : '' }}>Não</option>
                        <option value="1" {{ old('ead', $curso->ead) == 1 ? 'selected' : '' }}>Sim</option>
                    </select>
                </div>
            </div>
        </fieldset>
    </div>

    {{-- Botões --}}
    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Salvar Curso
        </button>
        <a href="{{ route('admin.cursos.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left"></i> Voltar para Lista de Cursos
        </a>
    </div>
</form>
@endsection
