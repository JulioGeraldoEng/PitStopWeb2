<?php

namespace App\Http\Controllers;

use App\Models\ItemVenda;
use App\Models\Venda;
use Illuminate\Http\Request;

class ItemVendaController extends Controller
{
    // Remover um item individual da venda
    public function destroy($id)
    {
        $item = ItemVenda::findOrFail($id);
        $venda_id = $item->venda_id;
        
        // Restaurar estoque
        $produto = $item->produto;
        if ($produto) {
            $produto->increment('estoque', $item->quantidade);
        }
        
        $item->delete();
        
        // Recalcular total da venda
        $venda = Venda::find($venda_id);
        $novoTotal = $venda->itens()->sum('subtotal');
        $venda->update(['total' => $novoTotal]);
        
        return redirect()->route('vendas.show', $venda_id)
            ->with('success', 'Item removido com sucesso!');
    }
    
    // Adicionar um novo item a uma venda existente (opcional)
    public function store(Request $request)
    {
        $request->validate([
            'venda_id' => 'required|exists:vendas,id',
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
        ]);
        
        $venda = Venda::findOrFail($request->venda_id);
        $produto = \App\Models\Produto::findOrFail($request->produto_id);
        
        // Verificar estoque
        if ($produto->estoque < $request->quantidade) {
            return back()->with('error', 'Estoque insuficiente!');
        }
        
        $subtotal = $produto->preco * $request->quantidade;
        
        // Criar item
        $item = ItemVenda::create([
            'venda_id' => $request->venda_id,
            'produto_id' => $produto->id,
            'nome_produto' => $produto->nome,
            'quantidade' => $request->quantidade,
            'preco_unitario' => $produto->preco,
            'subtotal' => $subtotal,
        ]);
        
        // Atualizar estoque
        $produto->decrement('estoque', $request->quantidade);
        
        // Atualizar total da venda
        $novoTotal = $venda->itens()->sum('subtotal') + $subtotal;
        $venda->update(['total' => $novoTotal]);
        
        return redirect()->route('vendas.show', $request->venda_id)
            ->with('success', 'Item adicionado com sucesso!');
    }
}