<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BackupController;
use Illuminate\Support\Facades\File;

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

    // Importar backup via upload
    // Importar backup via upload
    public function import(Request $request)
    {
        try {
            $request->validate([
                'arquivo' => 'required|file|mimes:sqlite,sqlite3,db|max:10240',
            ]);
            
            $arquivo = $request->file('arquivo');
            $nomeImportado = 'importado_' . date('Ymd_His') . '.sqlite';
            
            // Garantir que a pasta existe
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0777, true);
            }
            
            // Mover arquivo para pasta de backups
            $arquivo->move($backupDir, $nomeImportado);
            
            return redirect()->route('backups.index')
                ->with('success', 'Arquivo importado com sucesso! Nome: ' . $nomeImportado)
                ->with('importado', $nomeImportado);
                
        } catch (\Exception $e) {
            return redirect()->route('backups.index')
                ->with('error', 'Erro ao importar: ' . $e->getMessage());
        }
    }

    // Restaurar após importar
    public function restaurarImportado(Request $request)
    {
        try {
            $filename = $request->filename;
            $backupPath = storage_path('app/backups/' . $filename);
            $bancoPath = database_path('pitstopweb.sqlite');
            
            if (!File::exists($backupPath)) {
                return redirect()->route('backups.index')
                    ->with('error', 'Arquivo não encontrado!');
            }
            
            // Fazer backup do banco atual antes de restaurar
            $this->criarBackup('auto_pre_restore_' . date('Ymd_His'));
            
            // Copiar backup importado para o banco atual
            File::copy($backupPath, $bancoPath);
            chmod($bancoPath, 0666);
            
            return redirect()->route('backups.index')
                ->with('success', 'Backup importado restaurado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->route('backups.index')
                ->with('error', 'Erro ao restaurar: ' . $e->getMessage());
        }
    }
}