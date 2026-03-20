@extends('layouts.app')

@section('title', 'Novo Produto')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Novo Produto</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('produtos.store') }}">
            @csrf

            <div class="mb-3">
                <label for="nome" class="form-label">Nome *</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço *</label>
                        <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="{{ old('preco') }}" required>
                        <small class="text-muted">Ex: 49.90</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="estoque" class="form-label">Estoque *</label>
                        <input type="number" class="form-control" id="estoque" name="estoque" value="{{ old('estoque', 0) }}" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection