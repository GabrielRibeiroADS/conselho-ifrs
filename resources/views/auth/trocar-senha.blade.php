@extends('layouts.guest')

@section('title', 'Trocar Senha')

@section('content')
<div class="container">
    <div class="row align-items-center">
        <img src="{{ asset('assets/img/marca-ifrs-vertical.jpg') }}" class="rounded mx-auto d-block" height="160px" alt="IFRS" />
    </div>
    <br>
    <div class="row">
        <form action="{{ route('password.update') }}" method="post" class="form-signin card shadow-lg p-3 mb-5 bg-white rounded">
            @csrf
            <h1 class="h3 mb-3 font-weight-normal">Trocar Senha</h1>
            
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Atenção!</strong> Por motivos de segurança, você precisa definir uma nova senha para continuar.
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <div class="form-group">
                <label for="senha_atual">Senha Atual</label>
                <input type="password" 
                       id="senha_atual" 
                       name="senha_atual" 
                       class="form-control @error('senha_atual') is-invalid @enderror" 
                       placeholder="Digite sua senha atual"
                       required>
                @error('senha_atual')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" 
                       id="nova_senha" 
                       name="nova_senha" 
                       class="form-control @error('nova_senha') is-invalid @enderror" 
                       placeholder="Digite a nova senha (mínimo 8 caracteres)"
                       required>
                @error('nova_senha')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="nova_senha_confirmation">Confirmar Nova Senha</label>
                <input type="password" 
                       id="nova_senha_confirmation" 
                       name="nova_senha_confirmation" 
                       class="form-control" 
                       placeholder="Confirme a nova senha"
                       required>
            </div>
            
            <button type="submit" class="btn btn-outline-success btn-block">
                <i class="bi bi-check-lg"></i> Salvar Nova Senha
            </button>
            
            <div>
                <p class="mt-4 mb-3">
                    IFRS - Diretoria de Assuntos Estudantis
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
