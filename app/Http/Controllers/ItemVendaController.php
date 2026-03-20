<?php

namespace App\Http\Controllers;

use App\Models\ItemVenda;
use App\Models\Venda;
use App\Models\Produto;
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
    
    // Mostrar formulário de edição do item
    public function edit($id)
    {
        $item = ItemVenda::findOrFail($id);
        $produtos = Produto::orderBy('nome')->get();
        return view('vendas.item-edit', compact('item', 'produtos'));
    }
    
    // Atualizar o item da venda
    public function update(Request $request, $id)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
        ]);
        
        $item = ItemVenda::findOrFail($id);
        $venda = Venda::findOrFail($item->venda_id);
        
        // Verificar se a venda já está paga ou cancelada
        if ($venda->status != 'pendente') {
            return back()->with('error', 'Não é possível editar uma venda já paga ou cancelada.');
        }
        
        // Restaurar estoque do produto antigo
        $produtoAntigo = $item->produto;
        if ($produtoAntigo) {
            $produtoAntigo->increment('estoque', $item->quantidade);
        }
        
        // Obter novo produto
        $novoProduto = Produto::findOrFail($request->produto_id);
        
        // Verificar estoque do novo produto
        if ($novoProduto->estoque < $request->quantidade) {
            // Se não tiver estoque, restaurar o estoque que foi removido
            if ($produtoAntigo) {
                $produtoAntigo->decrement('estoque', $item->quantidade);
            }
            return back()->with('error', 'Estoque insuficiente para o novo produto!');
        }
        
        // Atualizar o item
        $novoSubtotal = $novoProduto->preco * $request->quantidade;
        
        $item->update([
            'produto_id' => $novoProduto->id,
            'nome_produto' => $novoProduto->nome,
            'quantidade' => $request->quantidade,
            'preco_unitario' => $novoProduto->preco,
            'subtotal' => $novoSubtotal,
        ]);
        
        // Atualizar estoque do novo produto
        $novoProduto->decrement('estoque', $request->quantidade);
        
        // Recalcular total da venda
        $novoTotal = $venda->itens()->sum('subtotal');
        $venda->update(['total' => $novoTotal]);
        
        return redirect()->route('vendas.show', $item->venda_id)
            ->with('success', 'Item atualizado com sucesso!');
    }
}