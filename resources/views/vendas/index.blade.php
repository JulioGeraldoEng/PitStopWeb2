@extends('layouts.app')

@section('title', 'Vendas')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Vendas</h3>
        <a href="{{ route('vendas.create') }}" class="btn btn-primary">Nova Venda</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendas as $venda)
                    <tr>
                        <td>{{ $venda->id }}</td>
                        <td>{{ date('d/m/Y', strtotime($venda->data)) }}</td>
                        <td>{{ $venda->cliente->nome }}</td>
                        <td>R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                        <td>
                            @if($venda->status == 'pendente')
                                <span class="badge bg-warning">Pendente</span>
                            @elseif($venda->status == 'pago')
                                <span class="badge bg-success">Pago</span>
                            @else
                                <span class="badge bg-danger">Cancelado</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('vendas.show', $venda->id) }}" class="btn btn-sm btn-info">Ver</a>
                            <a href="{{ route('vendas.recibo', $venda->id) }}" class="btn btn-sm btn-info" target="_blank">🖨️</a>
                            <form action="{{ route('vendas.destroy', $venda->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancelar esta venda? O estoque será restaurado.')">Cancelar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhuma venda registrada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection