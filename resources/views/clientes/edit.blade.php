@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Editar Cliente: {{ $cliente->nome }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.update', $cliente->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nome" class="form-label">Nome *</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $cliente->nome) }}" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone', $cliente->telefone) }}">
                <small class="text-muted">Ex: (11) 99999-9999</small>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $cliente->email) }}">
            </div>

            <div class="mb-3">
                <label for="observacao" class="form-label">Observação</label>
                <textarea class="form-control" id="observacao" name="observacao" rows="3">{{ old('observacao', $cliente->observacao) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection