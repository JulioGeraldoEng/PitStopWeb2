<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $produtos = [
            [
                'nome' => 'Óleo Motor 5W30',
                'descricao' => 'Óleo sintético para motor, 1 litro',
                'preco' => 45.90,
                'estoque' => 50,
            ],
            [
                'nome' => 'Filtro de Óleo',
                'descricao' => 'Filtro de óleo para motor 1.0 a 2.0',
                'preco' => 25.50,
                'estoque' => 100,
            ],
            [
                'nome' => 'Pastilha de Freio',
                'descricao' => 'Pastilha de freio dianteira (jogo com 4)',
                'preco' => 89.90,
                'estoque' => 30,
            ],
            [
                'nome' => 'Velas de Ignição',
                'descricao' => 'Kit com 4 velas',
                'preco' => 65.00,
                'estoque' => 40,
            ],
            [
                'nome' => 'Lavagem Completa',
                'descricao' => 'Lavagem interna e externa',
                'preco' => 80.00,
                'estoque' => 999,
            ],
        ];

        foreach ($produtos as $produto) {
            Produto::create($produto);
        }

        $this->command->info('Produtos criados com sucesso!');
    }
}