@extends('layouts.admin')

@section('title', 'Home')

@section('header', 'Bem-Vinda/o, ' . auth()->user()->nome)

@section('content')
<div class="row">
    <div class="col">
        <p>Nesse sistema você terá acesso as informações dos auxílios estudantis do Instituto Federal do Rio Grande do Sul.</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">
                <i class="bi bi-people"></i> Estudantes
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $totalEstudantes ?? 0 }}</h5>
                <p class="card-text">Estudantes cadastrados</p>
                @can('estudantes.index')
                <a href="{{ route('admin.estudantes.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-eye"></i> Visualizar
                </a>
                @endcan
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">
                <i class="bi bi-building"></i> Unidades
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $totalUnidades ?? 0 }}</h5>
                <p class="card-text">Campus cadastrados</p>
                @can('unidades.index')
                <a href="{{ route('admin.unidades.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-eye"></i> Visualizar
                </a>
                @endcan
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">
                <i class="bi bi-book"></i> Cursos
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $totalCursos ?? 0 }}</h5>
                <p class="card-text">Cursos cadastrados</p>
                @can('cursos.index')
                <a href="{{ route('admin.cursos.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-eye"></i> Visualizar
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
