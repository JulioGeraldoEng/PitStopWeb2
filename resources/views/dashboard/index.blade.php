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
        
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text display-6">{{ $totalClientes ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Produtos</h5>
                        <p class="card-text display-6">{{ $totalProdutos ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Vendas Hoje</h5>
                        <p class="card-text display-6">{{ $vendasHoje ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Faturamento</h5>
                        <p class="card-text display-6">R$ {{ number_format($faturamentoHoje ?? 0, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection