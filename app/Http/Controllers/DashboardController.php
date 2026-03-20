<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Venda;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Totais gerais
        $totalClientes = Cliente::count();
        $totalProdutos = Produto::count();
        
        // Vendas de hoje
        $hoje = Carbon::now()->format('Y-m-d');
        $vendasHoje = Venda::where('data', $hoje)->count();
        $faturamentoHoje = Venda::where('data', $hoje)->sum('total');
        
        // Vendas do mês
        $mesAtual = Carbon::now()->format('Y-m');
        $vendasMes = Venda::where('data', 'like', $mesAtual . '%')->count();
        $faturamentoMes = Venda::where('data', 'like', $mesAtual . '%')->sum('total');
        
        // Produtos com estoque baixo (menos de 5)
        $estoqueBaixo = Produto::where('estoque', '<', 5)->count();
        
        // Últimas vendas
        $ultimasVendas = Venda::with('cliente')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.index', compact(
            'totalClientes',
            'totalProdutos',
            'vendasHoje',
            'faturamentoHoje',
            'vendasMes',
            'faturamentoMes',
            'estoqueBaixo',
            'ultimasVendas'
        ));
    }
}