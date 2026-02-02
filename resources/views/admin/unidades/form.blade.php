@extends('layouts.admin')

@section('title', $titulo)

@section('header', $titulo)

@section('content')
<p>* Campos obrigatórios.</p>

<form action="{{ $unidade->exists ? route('admin.unidades.update', $unidade) : route('admin.unidades.store') }}" method="POST">
    @csrf
    @if($unidade->exists)
        @method('PUT')
    @endif

    {{-- Dados da Unidade --}}
    <div class="form-group" id="dados-unidade">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Dados da Unidade</legend>
            
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="nome">* Nome:</label>
                    <input type="text" 
                           name="nome" 
                           id="nome" 
                           value="{{ old('nome', $unidade->nome) }}" 
                           maxlength="100" 
                           class="form-control @error('nome') is-invalid @enderror"
                           required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group col-md-4">
                    <label for="unidade_gestora">Unidade Gestora:</label>
                    <select name="unidade_gestora" id="unidade_gestora" class="custom-select">
                        <option value="0" {{ old('unidade_gestora', $unidade->unidade_gestora) == 0 ? 'selected' : '' }}>Não</option>
                        <option value="1" {{ old('unidade_gestora', $unidade->unidade_gestora) == 1 ? 'selected' : '' }}>Sim</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email_suporte">* E-mail de Suporte:</label>
                    <input type="email" 
                           name="email_suporte" 
                           id="email_suporte" 
                           value="{{ old('email_suporte', $unidade->email_suporte) }}" 
                           maxlength="200" 
                           class="form-control @error('email_suporte') is-invalid @enderror"
                           required>
                    @error('email_suporte')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="pasta_base">* Pasta para Uploads:</label>
                    <input type="text" 
                           name="pasta_base" 
                           id="pasta_base" 
                           value="{{ old('pasta_base', $unidade->pasta_base) }}" 
                           maxlength="50" 
                           class="form-control @error('pasta_base') is-invalid @enderror"
                           required>
                    @error('pasta_base')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </fieldset>
    </div>

    {{-- Acesso ao Google Drive --}}
    <div class="form-group" id="dados-drive">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Acesso ao Google Drive</legend>
            
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="usar_drive">Habilitar Google Drive?</label>
                    <select name="usar_drive" id="usar_drive" class="custom-select">
                        <option value="0" {{ old('usar_drive', $unidade->usar_drive) == 0 ? 'selected' : '' }}>Não</option>
                        <option value="1" {{ old('usar_drive', $unidade->usar_drive) == 1 ? 'selected' : '' }}>Sim</option>
                    </select>
                </div>
                
                <div class="form-group col-md-4">
                    <label for="conta_drive">Conta do Drive:</label>
                    <input type="text" 
                           name="conta_drive" 
                           id="conta_drive" 
                           value="{{ old('conta_drive', $unidade->conta_drive) }}" 
                           maxlength="200" 
                           class="form-control">
                </div>
                
                <div class="form-group col-md-4">
                    <label for="acesso_drive">Pasta Credenciais:</label>
                    <input type="text" 
                           name="acesso_drive" 
                           id="acesso_drive" 
                           value="{{ old('acesso_drive', $unidade->acesso_drive) }}" 
                           maxlength="50" 
                           class="form-control">
                </div>
            </div>
        </fieldset>
    </div>

    {{-- Botões --}}
    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Salvar Unidade
        </button>
        <a href="{{ route('admin.unidades.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left"></i> Voltar para Lista de Unidades
        </a>
    </div>
</form>
@endsection
