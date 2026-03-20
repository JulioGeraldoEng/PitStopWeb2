@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Dashboard</h1>
        <p>Bem-vindo, {{ Auth::user()->name }}!</p>
        
        @if(Auth::user()->is_admin)
            <div class="alert alert-info">Você é administrador!</div>
        @endif
    </div>
</div>

<!-- Cards de Resumo -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Clientes</h5>
                <p class="card-text display-6">{{ $totalClientes }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Produtos</h5>
                <p class="card-text display-6">{{ $totalProdutos }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Vendas Hoje</h5>
                <p class="card-text display-6">{{ $vendasHoje }}</p>
                <small>R$ {{ number_format($faturamentoHoje, 2, ',', '.') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Faturamento Mês</h5>
                <p class="card-text display-6">R$ {{ number_format($faturamentoMes, 2, ',', '.') }}</p>
                <small>{{ $vendasMes }} vendas</small>
            </div>
        </div>
    </div>
</div>

<!-- Cards Adicionais -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">⚠️ Estoque Baixo (menos de 5)</h5>
            </div>
            <div class="card-body">
                @if($estoqueBaixo > 0)
                    <p class="display-6 text-center">{{ $estoqueBaixo }}</p>
                    <p class="text-center">produtos com estoque crítico</p>
                    <a href="{{ route('produtos.index') }}" class="btn btn-danger w-100">Ver Produtos</a>
                @else
                    <p class="text-center text-success">✅ Todos os produtos com estoque adequado</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">📊 Últimas Vendas</h5>
            </div>
            <div class="card-body">
                @if($ultimasVendas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasVendas as $venda)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($venda->data)) }}</td>
                                    <td>{{ $venda->cliente->nome }}</td>
                                    <td>R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('vendas.index') }}" class="btn btn-sm btn-secondary w-100">Ver Todas</a>
                @else
                    <p class="text-center">Nenhuma venda registrada ainda.</p>
                    <a href="{{ route('vendas.create') }}" class="btn btn-primary w-100">Registrar Primeira Venda</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection