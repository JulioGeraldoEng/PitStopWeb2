<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Evitar duplicação: só cria se não existir
        if (!User::where('email', 'admin@pitstopweb.com')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@pitstopweb.com',
                'telefone' => '(11) 99999-9999',
                'password' => Hash::make('1234'),
                'is_admin' => true,
            ]);
            $this->command->info('Usuário admin criado com sucesso!');
        } else {
            $this->command->info('Usuário admin já existe. Nada a fazer.');
        }
    }
}