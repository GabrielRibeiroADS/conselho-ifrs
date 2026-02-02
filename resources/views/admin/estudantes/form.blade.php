@extends('layouts.admin')

@section('title', $titulo)

@section('header', $titulo)

@section('content')
<p>* Campos obrigatórios.</p>

<form action="{{ $estudante->exists ? route('admin.estudantes.update', $estudante) : route('admin.estudantes.store') }}" method="POST">
    @csrf
    @if($estudante->exists)
        @method('PUT')
    @endif

    {{-- Dados do Estudante --}}
    <div class="form-group">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Dados do Estudante</legend>
            
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="nome">* Nome:</label>
                    <input type="text" 
                           name="nome" 
                           id="nome" 
                           value="{{ old('nome', $estudante->nome) }}" 
                           maxlength="200" 
                           class="form-control @error('nome') is-invalid @enderror"
                           required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="cpf">* CPF:</label>
                    <input type="text" 
                           name="cpf" 
                           id="cpf" 
                           value="{{ old('cpf', $estudante->cpf) }}" 
                           maxlength="14" 
                           class="form-control cpf @error('cpf') is-invalid @enderror"
                           placeholder="000.000.000-00"
                           required>
                    @error('cpf')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">E-mail:</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $estudante->email) }}" 
                           maxlength="200" 
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="telefone">Telefone:</label>
                    <input type="text" 
                           name="telefone" 
                           id="telefone" 
                           value="{{ old('telefone', $estudante->telefone) }}" 
                           maxlength="20" 
                           class="form-control">
                </div>
            </div>
        </fieldset>
    </div>

    {{-- Botões --}}
    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Salvar Estudante
        </button>
        <a href="{{ route('admin.estudantes.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left"></i> Voltar para Lista de Estudantes
        </a>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    $('.cpf').mask('000.000.000-00', {reverse: true});
});
</script>
@endpush
