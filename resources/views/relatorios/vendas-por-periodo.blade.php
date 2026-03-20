@extends('layouts.app')

@section('title', 'Vendas por Período')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Vendas de {{ date('d/m/Y', strtotime($dataInicio)) }} até {{ date('d/m/Y', strtotime($dataFim)) }}</h3>
        <a href="{{ route('relatorios.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
    <div class="card-body">
        <!-- Cards Resumo -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total de Vendas</h5>
                        <p class="card-text display-6">{{ $totalVendas }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Faturamento Total</h5>
                        <p class="card-text display-6">R$ {{ number_format($totalFaturamento, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Pendentes</h5>
                        <p class="card-text display-6">{{ $vendasPendentes }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Pagas</h5>
                        <p class="card-text display-6">{{ $vendasPagas }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabela de Vendas -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Ações</th>
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhuma venda no período.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection