<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Venda;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClientes = Cliente::count();
        $totalProdutos = Produto::count();
        $vendasHoje = Venda::where('data', date('Y-m-d'))->count();
        $faturamentoHoje = Venda::where('data', date('Y-m-d'))->sum('total');

        return view('dashboard.index', compact(
            'totalClientes', 
            'totalProdutos', 
            'vendasHoje', 
            'faturamentoHoje'
        ));
    }
}