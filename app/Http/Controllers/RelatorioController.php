<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Produto;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    public function index()
    {
        return view('relatorios.index');
    }
    
    public function vendasPorPeriodo(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);
        
        $dataInicio = $request->data_inicio;
        $dataFim = $request->data_fim;
        
        $vendas = Venda::with('cliente')
            ->whereBetween('data', [$dataInicio, $dataFim])
            ->orderBy('data', 'desc')
            ->get();
        
        $totalVendas = $vendas->count();
        $totalFaturamento = $vendas->sum('total');
        
        // Vendas por status
        $vendasPendentes = $vendas->where('status', 'pendente')->count();
        $vendasPagas = $vendas->where('status', 'pago')->count();
        $vendasCanceladas = $vendas->where('status', 'cancelado')->count();
        
        return view('relatorios.vendas-por-periodo', compact(
            'vendas', 'dataInicio', 'dataFim',
            'totalVendas', 'totalFaturamento',
            'vendasPendentes', 'vendasPagas', 'vendasCanceladas'
        ));
    }
    
    public function produtosMaisVendidos(Request $request)
    {
        $periodo = $request->periodo ?? 'mes';
        
        switch ($periodo) {
            case 'semana':
                $dataInicio = Carbon::now()->startOfWeek();
                $dataFim = Carbon::now()->endOfWeek();
                break;
            case 'mes':
                $dataInicio = Carbon::now()->startOfMonth();
                $dataFim = Carbon::now()->endOfMonth();
                break;
            case 'ano':
                $dataInicio = Carbon::now()->startOfYear();
                $dataFim = Carbon::now()->endOfYear();
                break;
            default:
                $dataInicio = Carbon::now()->startOfMonth();
                $dataFim = Carbon::now()->endOfMonth();
        }
        
        $produtos = ItemVenda::whereBetween('created_at', [$dataInicio, $dataFim])
            ->selectRaw('produto_id, nome_produto, SUM(quantidade) as total_quantidade, SUM(subtotal) as total_faturamento')
            ->groupBy('produto_id', 'nome_produto')
            ->orderBy('total_quantidade', 'desc')
            ->limit(10)
            ->get();
        
        return view('relatorios.produtos-mais-vendidos', compact('produtos', 'periodo', 'dataInicio', 'dataFim'));
    }
    
    public function faturamentoMensal()
    {
        $faturamentoMensal = Venda::selectRaw('strftime("%Y-%m", data) as mes, COUNT(*) as total_vendas, SUM(total) as total_faturamento')
            ->where('status', '!=', 'cancelado')
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->limit(12)
            ->get();
        
        return view('relatorios.faturamento-mensal', compact('faturamentoMensal'));
    }
}