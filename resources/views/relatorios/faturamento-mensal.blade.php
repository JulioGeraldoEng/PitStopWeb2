@extends('layouts.app')

@section('title', 'Faturamento Mensal')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Faturamento Mensal</h3>
        <a href="{{ route('relatorios.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <th>Mês</th>
                    <th>Total de Vendas</th>
                    <th>Faturamento</th>
                    <th>Ticket Médio</th>
                </thead>
                <tbody>
                    @forelse($faturamentoMensal as $item)
                    <tr>
                        <td>{{ date('m/Y', strtotime($item->mes . '-01')) }}</td>
                        <td>{{ $item->total_vendas }}</td>
                        <td>R$ {{ number_format($item->total_faturamento, 2, ',', '.') }}</td>
                        <td>
                            @if($item->total_vendas > 0)
                                R$ {{ number_format($item->total_faturamento / $item->total_vendas, 2, ',', '.') }}
                            @else
                                R$ 0,00
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Nenhum dado disponível.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection