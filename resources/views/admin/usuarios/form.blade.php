@extends('layouts.admin')

@section('title', $titulo)

@section('header', $titulo)

@section('content')
<p>* Campos obrigatórios.</p>

@php
    $papeisSelecionados = old('roles', $usuario->roles->pluck('id')->all());
@endphp

<form action="{{ $usuario->exists ? route('admin.usuarios.update', $usuario) : route('admin.usuarios.store') }}" method="POST">
    @csrf
    @if($usuario->exists)
        @method('PUT')
    @endif

    {{-- Dados do Usuário --}}
    <div class="form-group">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Dados do Usuário</legend>
            
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="nome">* Nome:</label>
                    <input type="text" 
                           name="nome" 
                           id="nome" 
                           value="{{ old('nome', $usuario->nome) }}" 
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
                            <option value="{{ $unidade->id }}" {{ old('id_unidade', $usuario->id_unidade) == $unidade->id ? 'selected' : '' }}>
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
                <div class="form-group col-md-6">
                    <label for="email">* E-mail:</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $usuario->email) }}" 
                           maxlength="200" 
                           class="form-control @error('email') is-invalid @enderror"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group col-md-6">
                    <label for="senha">{{ $usuario->exists ? 'Nova Senha (deixe em branco para manter)' : '* Senha' }}:</label>
                    <input type="password" 
                           name="senha" 
                           id="senha" 
                           class="form-control @error('senha') is-invalid @enderror"
                           {{ $usuario->exists ? '' : 'required' }}>
                    @error('senha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if(!$usuario->exists)
                        <small class="form-text text-muted">O usuário será obrigado a trocar a senha no primeiro acesso.</small>
                    @endif
                </div>
            </div>
        </fieldset>
    </div>

    {{-- Papéis/Profissões --}}
    <div class="form-group">
        <fieldset class="field-block border rounded p-3">
            <legend class="font-weight-bold text-center">Papéis/Profissões</legend>

            @if($papeis->isEmpty())
                <p class="mb-0">Nenhum papel cadastrado.</p>
            @else
                <div class="form-row">
                    @foreach($papeis as $papel)
                        <div class="form-group col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="papel_{{ $papel->id }}"
                                       name="roles[]"
                                       value="{{ $papel->id }}"
                                       {{ in_array($papel->id, $papeisSelecionados, true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="papel_{{ $papel->id }}">
                                    {{ $papel->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </fieldset>
    </div>

    {{-- Botões --}}
    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Salvar Usuário
        </button>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left"></i> Voltar para Lista de Usuários
        </a>
    </div>
</form>
@endsection
