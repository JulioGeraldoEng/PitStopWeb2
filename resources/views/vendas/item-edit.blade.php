@extends('layouts.app')

@section('title', 'Editar Item')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Editar Item da Venda #{{ $item->venda_id }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('item-venda.update', $item->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="produto_id" class="form-label">Produto</label>
                        <select class="form-select" id="produto_id" name="produto_id" required>
                            <option value="">Selecione um produto...</option>
                            @foreach($produtos as $produto)
                                <option value="{{ $produto->id }}" 
                                    data-preco="{{ $produto->preco }}"
                                    {{ $item->produto_id == $produto->id ? 'selected' : '' }}>
                                    {{ $produto->nome }} - R$ {{ number_format($produto->preco, 2, ',', '.') }} (Estoque: {{ $produto->estoque }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" 
                            value="{{ $item->quantidade }}" min="1" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Subtotal</label>
                        <input type="text" class="form-control" id="subtotal" 
                            value="R$ {{ number_format($item->subtotal, 2, ',', '.') }}" readonly disabled>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('vendas.show', $item->venda_id) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectProduto = document.getElementById('produto_id');
    const inputQuantidade = document.getElementById('quantidade');
    const spanSubtotal = document.getElementById('subtotal');
    
    function calcularSubtotal() {
        const selected = selectProduto.options[selectProduto.selectedIndex];
        const preco = parseFloat(selected.dataset.preco) || 0;
        const quantidade = parseInt(inputQuantidade.value) || 0;
        const subtotal = preco * quantidade;
        spanSubtotal.value = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
    }
    
    selectProduto.addEventListener('change', calcularSubtotal);
    inputQuantidade.addEventListener('input', calcularSubtotal);
    
    calcularSubtotal();
});
</script>
@endpush
@endsection