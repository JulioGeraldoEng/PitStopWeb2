@extends('layouts.app')

@section('title', 'Nova Venda')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Nova Venda</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('vendas.store') }}" id="vendaForm">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cliente_id" class="form-label">Cliente *</label>
                        <select class="form-select" id="cliente_id" name="cliente_id" required>
                            <option value="">Selecione um cliente...</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nome }} - {{ $cliente->telefone ?? 'sem telefone' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="data" class="form-label">Data da Venda *</label>
                        <input type="date" class="form-control" id="data" name="data" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="data_vencimento" class="form-label">Data Vencimento</label>
                        <input type="date" class="form-control" id="data_vencimento" name="data_vencimento">
                    </div>
                </div>
            </div>

            <h4 class="mt-4">Produtos</h4>
            <div class="table-responsive">
                <table class="table table-bordered" id="tabela-produtos">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th width="120">Quantidade</th>
                            <th>Subtotal</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="produtos-body">
                        <tr id="linha-modelo" style="display:none;">
                            <td>
                                <select class="form-select produto-select">
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}" data-preco="{{ $produto->preco }}" data-estoque="{{ $produto->estoque }}">{{ $produto->nome }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="preco">R$ 0,00</td>
                            <td class="estoque">0</td>
                            <td><input type="number" class="form-control quantidade" min="1" value="1"></td>
                            <td class="subtotal">R$ 0,00</td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger remover-linha">X</button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td colspan="2"><strong id="total-geral">R$ 0,00</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="adicionar-produto">+ Adicionar Produto</button>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Finalizar Venda</button>
                <a href="{{ route('vendas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    let contador = 1;
    
    function formatarMoeda(valor) {
        return 'R$ ' + valor.toFixed(2).replace('.', ',');
    }
    
    function atualizarLinha(linha) {
        let select = linha.querySelector('.produto-select');
        let preco = 0;
        let estoque = 0;
        
        if (select.selectedIndex > 0) {  // > 0 para ignorar o placeholder
            preco = parseFloat(select.options[select.selectedIndex].dataset.preco) || 0;
            estoque = parseInt(select.options[select.selectedIndex].dataset.estoque) || 0;
        }
        
        let quantidade = parseInt(linha.querySelector('.quantidade').value) || 0;
        let subtotal = preco * quantidade;
        
        linha.querySelector('.preco').innerText = formatarMoeda(preco);
        linha.querySelector('.estoque').innerText = estoque;
        linha.querySelector('.subtotal').innerText = formatarMoeda(subtotal);
        
        return subtotal;
    }
    
    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('#produtos-body > tr:not([style*="display: none"])').forEach(linha => {
            let subtotalText = linha.querySelector('.subtotal').innerText;
            let subtotal = parseFloat(subtotalText.replace('R$ ', '').replace(',', '.'));
            total += subtotal;
        });
        document.getElementById('total-geral').innerText = formatarMoeda(total);
    }
    
    // Criar uma nova linha vazia
    function criarLinhaVazia() {
        let novaLinha = document.createElement('tr');
        novaLinha.innerHTML = `
            <td>
                <select class="form-select produto-select" name="produtos[${contador}][id]" required>
                    <option value="">Selecione um produto...</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}" data-preco="{{ $produto->preco }}" data-estoque="{{ $produto->estoque }}">{{ $produto->nome }}</option>
                    @endforeach
                </select>
            </td>
            <td class="preco">R$ 0,00</td>
            <td class="estoque">0</td>
            <td><input type="number" class="form-control quantidade" name="produtos[${contador}][quantidade]" min="1" value="1" required></td>
            <td class="subtotal">R$ 0,00</td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger remover-linha">X</button></td>
        `;
        return novaLinha;
    }
    
    // Adicionar produto
    document.getElementById('adicionar-produto').addEventListener('click', function() {
        let novaLinha = criarLinhaVazia();
        document.getElementById('produtos-body').appendChild(novaLinha);
        contador++;
    });
    
    // Quando selecionar produto
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('produto-select')) {
            let linha = e.target.closest('tr');
            atualizarLinha(linha);
            calcularTotal();
        }
    });
    
    // Quando mudar quantidade
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantidade')) {
            let linha = e.target.closest('tr');
            let select = linha.querySelector('.produto-select');
            let estoque = 0;
            
            if (select.selectedIndex > 0) {
                estoque = parseInt(select.options[select.selectedIndex].dataset.estoque) || 0;
            }
            
            let quantidade = parseInt(e.target.value) || 0;
            if (quantidade > estoque && estoque > 0) {
                alert('Quantidade maior que estoque disponível!');
                e.target.value = estoque;
                quantidade = estoque;
            }
            
            atualizarLinha(linha);
            calcularTotal();
        }
    });
    
    // Remover linha
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remover-linha')) {
            e.target.closest('tr').remove();
            calcularTotal();
        }
    });
    
    // Remover a linha modelo do HTML
    let linhaModelo = document.getElementById('linha-modelo');
    if (linhaModelo) {
        linhaModelo.remove();
    }
});
</script>