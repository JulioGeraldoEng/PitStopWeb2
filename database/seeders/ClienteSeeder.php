<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            [
                'nome' => 'João Silva',
                'telefone' => '(11) 99999-1111',
                'email' => 'joao@email.com',
                'observacao' => 'Cliente desde 2024',
            ],
            [
                'nome' => 'Maria Santos',
                'telefone' => '(11) 99999-2222',
                'email' => 'maria@email.com',
                'observacao' => 'Indicação',
            ],
            [
                'nome' => 'Carlos Oliveira',
                'telefone' => '(11) 99999-3333',
                'email' => 'carlos@email.com',
                'observacao' => null,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }

        $this->command->info('Clientes criados com sucesso!');
    }
}