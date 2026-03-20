<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemVenda;
use App\Models\Venda;
use App\Models\Produto;

class ItemVendaSeeder extends Seeder
{
    public function run(): void
    {
        $vendas = Venda::all();
        $produtos = Produto::all();

        if ($vendas->count() > 0 && $produtos->count() > 0) {
            // Primeira venda: 2 itens
            ItemVenda::create([
                'venda_id' => $vendas[0]->id,
                'produto_id' => $produtos[0]->id,
                'nome_produto' => $produtos[0]->nome,
                'quantidade' => 2,
                'preco_unitario' => $produtos[0]->preco,
                'subtotal' => $produtos[0]->preco * 2,
            ]);

            ItemVenda::create([
                'venda_id' => $vendas[0]->id,
                'produto_id' => $produtos[1]->id,
                'nome_produto' => $produtos[1]->nome,
                'quantidade' => 1,
                'preco_unitario' => $produtos[1]->preco,
                'subtotal' => $produtos[1]->preco,
            ]);

            // Segunda venda: 1 item
            if (isset($vendas[1])) {
                ItemVenda::create([
                    'venda_id' => $vendas[1]->id,
                    'produto_id' => $produtos[2]->id,
                    'nome_produto' => $produtos[2]->nome,
                    'quantidade' => 3,
                    'preco_unitario' => $produtos[2]->preco,
                    'subtotal' => $produtos[2]->preco * 3,
                ]);
            }

            $this->command->info('Itens de venda criados com sucesso!');
        } else {
            $this->command->warn('Vendas ou produtos não encontrados.');
        }
    }
}