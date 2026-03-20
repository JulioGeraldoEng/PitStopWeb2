@extends('layouts.app')

@section('title', 'Relatórios')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Relatórios</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📅 Vendas por Período</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('relatorios.vendas.periodo') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ date('Y-m-01') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="data_fim" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Gerar Relatório</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">🏆 Produtos Mais Vendidos</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('relatorios.produtos.ranking') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="periodo" class="form-label">Período</label>
                        <select class="form-select" id="periodo" name="periodo">
                            <option value="semana">Última Semana</option>
                            <option value="mes" selected>Último Mês</option>
                            <option value="ano">Último Ano</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Gerar Ranking</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">💰 Faturamento Mensal</h5>
            </div>
            <div class="card-body">
                <p class="text-center">Visualize o faturamento dos últimos 12 meses</p>
                <a href="{{ route('relatorios.faturamento.mensal') }}" class="btn btn-info w-100 text-white">Ver Gráfico</a>
            </div>
        </div>
    </div>
</div>
@endsection