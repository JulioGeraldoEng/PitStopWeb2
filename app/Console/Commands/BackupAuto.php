<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BackupController;

class BackupAuto extends Command
{
    protected $signature = 'backup:auto';
    protected $description = 'Cria backup automático do banco de dados';

    public function handle()
    {
        $controller = new BackupController();
        
        try {
            $nome = $controller->criarBackup();
            $this->info("Backup criado com sucesso: " . $nome);
        } catch (\Exception $e) {
            $this->error("Erro ao criar backup: " . $e->getMessage());
        }
    }
}