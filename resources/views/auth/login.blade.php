@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row align-items-center">
        <img src="{{ asset('assets/img/marca-ifrs-vertical.jpg') }}" class="rounded mx-auto d-block" height="160px" alt="IFRS" />
    </div>
    <br>
    <div class="row">
        <form action="{{ route('login') }}" method="post" class="form-signin card shadow-lg p-3 mb-5 bg-white rounded">
            @csrf
            <h1 class="h3 mb-3 font-weight-normal">Assistência Estudantil / Servidores</h1>
            <p>Login</p>
            
            @if(session('error'))
                <p style="color:#FF0000">{{ session('error') }}</p>
            @endif
            
            @if($errors->any())
                <div style="color:#FF0000">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <label for="inputEmail" class="sr-only">E-mail</label>
            <input type="text" 
                   id="inputEmail" 
                   name="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   placeholder="Seu e-mail institucional" 
                   value="{{ old('email') }}"
                   required 
                   autofocus>
            <br>
            
            <label for="inputPassword" class="sr-only">Senha</label>
            <input type="password" 
                   id="inputPassword" 
                   name="senha" 
                   class="form-control @error('senha') is-invalid @enderror" 
                   placeholder="Senha"
                   required>
            <br>
            
            <input class="btn btn-outline-success btn-block" type="submit" value="Entrar">
            
            <div>
                <p class="mt-4 mb-3">
                    IFRS - Diretoria de Assuntos Estudantis
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
