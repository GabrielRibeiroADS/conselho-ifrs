@extends('layouts.admin')

@section('title', 'Editar Reunião - ' . $reuniao->titulo)

@section('header', 'Editar Reunião')

@section('content')
<form action="{{ route('admin.reunioes.update', [$conselho, $reuniao]) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header">
            <strong>Dados da Reunião</strong>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="titulo">* Título:</label>
                    <input type="text" 
                           name="titulo" 
                           id="titulo" 
                           value="{{ old('titulo', $reuniao->titulo) }}"
                           class="form-control @error('titulo') is-invalid @enderror"
                           required>
                    @error('titulo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-3">
                    <label for="data_reuniao">Data da Reunião:</label>
                    <input type="date" 
                           name="data_reuniao" 
                           id="data_reuniao" 
                           value="{{ old('data_reuniao', $reuniao->data_reuniao?->format('Y-m-d')) }}"
                           class="form-control @error('data_reuniao') is-invalid @enderror">
                    @error('data_reuniao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-3">
                    <label for="status">* Status:</label>
                    <select name="status" id="status" class="custom-select @error('status') is-invalid @enderror" required>
                        @foreach(\App\Models\Reuniao::statusList() as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $reuniao->status) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
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
                              class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes', $reuniao->observacoes) }}</textarea>
                    @error('observacoes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg"></i> Salvar Alterações
            </button>
            <a href="{{ route('admin.reunioes.show', [$conselho, $reuniao]) }}" class="btn btn-outline-success">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</form>
@endsection
