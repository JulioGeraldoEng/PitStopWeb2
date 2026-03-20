@extends('layouts.app')

@section('title', 'Produtos Mais Vendidos')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Produtos Mais Vendidos</h3>
        <a href="{{ route('relatorios.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">Período: {{ date('d/m/Y', strtotime($dataInicio)) }} até {{ date('d/m/Y', strtotime($dataFim)) }}</p>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Posição</th>
                        <th>Produto</th>
                        <th>Quantidade Vendida</th>
                        <th>Faturamento</th>
                    </thead>
                <tbody>
                    @forelse($produtos as $index => $produto)
                    <tr>
                        <td class="text-center">
                            @if($index == 0)
                                🥇
                            @elseif($index == 1)
                                🥈
                            @elseif($index == 2)
                                🥉
                            @else
                                {{ $index + 1 }}º
                            @endif
                        </td>
                        <td>{{ $produto->nome_produto }}</td>
                        <td>{{ $produto->total_quantidade }}</td>
                        <td>R$ {{ number_format($produto->total_faturamento, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Nenhum produto vendido no período.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection