<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chamar o seeder de usuário admin
        $this->call(AdminUserSeeder::class);
        $this->call(ClienteSeeder::class);  // ← ADICIONAR ESTA LINHA
    }
}