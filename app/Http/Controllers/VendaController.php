<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\ItemVenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    public function index()
    {
        $vendas = Venda::with('cliente')->orderBy('created_at', 'desc')->get();
        return view('vendas.index', compact('vendas'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        return view('vendas.create', compact('clientes', 'produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'data' => 'required|date',
            'data_vencimento' => 'nullable|date',
            'produtos' => 'required|array|min:1',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Calcular total
            $total = 0;
            $itens = [];

            foreach ($request->produtos as $item) {
                $produto = Produto::find($item['id']);
                $subtotal = $produto->preco * $item['quantidade'];
                $total += $subtotal;

                $itens[] = [
                    'produto_id' => $produto->id,
                    'nome_produto' => $produto->nome,
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $produto->preco,
                    'subtotal' => $subtotal,
                ];
            }

            // Criar venda
            $venda = Venda::create([
                'cliente_id' => $request->cliente_id,
                'data' => date('Y-m-d', strtotime($request->data)),  // ← Garantir formato Y-m-d
                'data_vencimento' => $request->data_vencimento ? date('Y-m-d', strtotime($request->data_vencimento)) : null,
                'total' => $total,
                'status' => 'pendente',
            ]);

            // Criar itens
            foreach ($itens as $item) {
                $item['venda_id'] = $venda->id;
                ItemVenda::create($item);

                // Atualizar estoque
                $produto = Produto::find($item['produto_id']);
                $produto->decrement('estoque', $item['quantidade']);
            }

            DB::commit();

            return redirect()->route('vendas.index')
                ->with('success', 'Venda registrada com sucesso! Total: R$ ' . number_format($total, 2, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao registrar venda: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $venda = Venda::with(['cliente', 'itens.produto'])->findOrFail($id);
        return view('vendas.show', compact('venda'));
    }

    public function destroy($id)
    {
        $venda = Venda::findOrFail($id);
        
        // Estornar estoque
        foreach ($venda->itens as $item) {
            $produto = Produto::find($item->produto_id);
            if ($produto) {
                $produto->increment('estoque', $item->quantidade);
            }
        }
        
        $venda->delete();

        return redirect()->route('vendas.index')
            ->with('success', 'Venda cancelada e estoque restaurado!');
    }

    public function recibo($id)
    {
        $venda = Venda::with(['cliente', 'itens.produto'])->findOrFail($id);
        return view('vendas.recibo', compact('venda'));
    }
}