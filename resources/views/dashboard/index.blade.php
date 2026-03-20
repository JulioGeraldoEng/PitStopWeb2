@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <p>Bem-vindo, {{ Auth::user()->name }}!</p>
        
        @if(Auth::user()->is_admin)
            <div class="alert alert-info">
                <i class="fas fa-shield-alt"></i> Você é administrador!
            </div>
        @endif
    </div>
</div>

<!-- Cards de Resumo com Ícones -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><i class="fas fa-users"></i> Clientes</h5>
                        <p class="card-text display-6">{{ $totalClientes }}</p>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('clientes.index') }}" class="text-white text-decoration-none">
                    <small><i class="fas fa-arrow-right"></i> Ver todos</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><i class="fas fa-box"></i> Produtos</h5>
                        <p class="card-text display-6">{{ $totalProdutos }}</p>
                    </div>
                    <i class="fas fa-box fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('produtos.index') }}" class="text-white text-decoration-none">
                    <small><i class="fas fa-arrow-right"></i> Ver todos</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Vendas Hoje</h5>
                        <p class="card-text display-6">{{ $vendasHoje }}</p>
                        <small>R$ {{ number_format($faturamentoHoje, 2, ',', '.') }}</small>
                    </div>
                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('vendas.index') }}" class="text-white text-decoration-none">
                    <small><i class="fas fa-arrow-right"></i> Ver vendas</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><i class="fas fa-chart-line"></i> Faturamento Mês</h5>
                        <p class="card-text display-6">R$ {{ number_format($faturamentoMes, 2, ',', '.') }}</p>
                        <small>{{ $vendasMes }} vendas</small>
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('relatorios.index') }}" class="text-white text-decoration-none">
                    <small><i class="fas fa-arrow-right"></i> Ver relatórios</small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Cards Adicionais -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Estoque Baixo (menos de 5)</h5>
            </div>
            <div class="card-body">
                @if($estoqueBaixo > 0)
                    <div class="text-center">
                        <i class="fas fa-box-open fa-3x text-danger mb-3"></i>
                        <p class="display-6">{{ $estoqueBaixo }}</p>
                        <p>produtos com estoque crítico</p>
                        <a href="{{ route('produtos.index') }}" class="btn btn-danger">
                            <i class="fas fa-eye"></i> Ver Produtos
                        </a>
                    </div>
                @else
                    <div class="text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-success">✅ Todos os produtos com estoque adequado</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-history"></i> Últimas Vendas</h5>
            </div>
            <div class="card-body">
                @if($ultimasVendas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                
                                    <th><i class="fas fa-calendar"></i> Data</th>
                                    <th><i class="fas fa-user"></i> Cliente</th>
                                    <th><i class="fas fa-dollar-sign"></i> Total</th>
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
                        <a href="{{ route('vendas.index') }}" class="btn btn-sm btn-secondary w-100">
                            <i class="fas fa-list"></i> Ver Todas
                        </a>
                    @else
                        <div class="text-center">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p>Nenhuma venda registrada ainda.</p>
                            <a href="{{ route('vendas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Registrar Primeira Venda
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botão de Atualizar (opcional) -->
<div class="row mt-4">
    <div class="col-md-12 text-end">
        <small class="text-muted">
            <i class="fas fa-sync-alt"></i> Última atualização: {{ now()->format('d/m/Y H:i:s') }}
        </small>
    </div>
</div>
@endsection