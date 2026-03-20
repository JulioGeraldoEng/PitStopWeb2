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
        
        // Vendas de hoje (usando whereDate para comparar apenas a data)
        $hoje = Carbon::now()->format('Y-m-d');
        $vendasHoje = Venda::whereDate('data', $hoje)->count();
        $faturamentoHoje = Venda::whereDate('data', $hoje)->sum('total');
        
        // Vendas do mês (usando whereMonth e whereYear)
        $vendasMes = Venda::whereMonth('data', Carbon::now()->month)
            ->whereYear('data', Carbon::now()->year)
            ->count();
        $faturamentoMes = Venda::whereMonth('data', Carbon::now()->month)
            ->whereYear('data', Carbon::now()->year)
            ->sum('total');
        
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