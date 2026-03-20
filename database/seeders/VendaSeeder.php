<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venda;
use App\Models\Cliente;
use Carbon\Carbon;

class VendaSeeder extends Seeder
{
    public function run(): void
    {
        // Pega o primeiro cliente (se existir)
        $cliente = Cliente::first();

        if ($cliente) {
            Venda::create([
                'cliente_id' => $cliente->id,
                'data' => Carbon::now()->format('Y-m-d'),
                'data_vencimento' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'total' => 150.00,
                'status' => 'pendente',
            ]);

            Venda::create([
                'cliente_id' => $cliente->id,
                'data' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'data_vencimento' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'total' => 320.50,
                'status' => 'pendente',
            ]);

            $this->command->info('Vendas criadas com sucesso!');
        } else {
            $this->command->warn('Nenhum cliente encontrado. Crie clientes primeiro.');
        }
    }
}