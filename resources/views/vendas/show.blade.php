@extends('layouts.app')

@section('title', 'Detalhes da Venda')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Venda #{{ $venda->id }}</h3>
        <a href="{{ route('vendas.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Informações da Venda</h5>
                <p><strong>Data:</strong> {{ date('d/m/Y', strtotime($venda->data)) }}</p>
                <p><strong>Data Vencimento:</strong> {{ $venda->data_vencimento ? date('d/m/Y', strtotime($venda->data_vencimento)) : '-' }}</p>
                <p><strong>Status:</strong> 
                    @if($venda->status == 'pendente')
                        <span class="badge bg-warning">Pendente</span>
                    @elseif($venda->status == 'pago')
                        <span class="badge bg-success">Pago</span>
                    @else
                        <span class="badge bg-danger">Cancelado</span>
                    @endif
                </p>
            </div>
            <div class="col-md-6">
                <h5>Cliente</h5>
                <p><strong>Nome:</strong> {{ $venda->cliente->nome }}</p>
                <p><strong>Telefone:</strong> {{ $venda->cliente->telefone ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $venda->cliente->email ?? '-' }}</p>
            </div>
        </div>

        <h5>Produtos</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                        <th width="80">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venda->itens as $item)
                    <tr>
                        <td>{{ $item->nome_produto }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('item-venda.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remover este item? O estoque será restaurado.')">Remover</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>R$ {{ number_format($venda->total, 2, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection