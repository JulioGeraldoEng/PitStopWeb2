<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupController extends Controller
{
    // Pasta onde os backups serão armazenados
    private $backupPath = 'app/backups';
    
    // Listar todos os backups disponíveis
    public function index()
    {
        $backups = [];
        $path = storage_path($this->backupPath);
        
        if (File::exists($path)) {
            $files = File::files($path);
            
            foreach ($files as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatSize($file->getSize()),
                    'date' => Carbon::createFromTimestamp($file->getMTime())->format('d/m/Y H:i:s'),
                    'path' => $file->getRealPath(),
                ];
            }
            
            // Ordenar por data (mais recente primeiro)
            usort($backups, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
        
        // Último backup
        $ultimoBackup = $backups ? $backups[0] : null;
        
        // Tamanho do banco atual
        $bancoAtual = database_path('pitstopweb.sqlite');
        $tamanhoBanco = File::exists($bancoAtual) ? $this->formatSize(File::size($bancoAtual)) : '0 KB';
        
        return view('backups.index', compact('backups', 'ultimoBackup', 'tamanhoBanco'));
    }
    
    // Criar backup manualmente
    public function create()
    {
        try {
            $this->criarBackup();
            return redirect()->route('backups.index')
                ->with('success', 'Backup criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('backups.index')
                ->with('error', 'Erro ao criar backup: ' . $e->getMessage());
        }
    }
    
    // Baixar um backup específico
    public function download($filename)
    {
        $path = storage_path($this->backupPath . '/' . $filename);
        
        if (!File::exists($path)) {
            return redirect()->route('backups.index')
                ->with('error', 'Arquivo não encontrado!');
        }
        
        return response()->download($path, $filename);
    }
    
    // Restaurar um backup
    public function restore($filename)
    {
        try {
            $backupPath = storage_path($this->backupPath . '/' . $filename);
            $bancoPath = database_path('pitstopweb.sqlite');
            
            if (!File::exists($backupPath)) {
                return redirect()->route('backups.index')
                    ->with('error', 'Arquivo de backup não encontrado!');
            }
            
            // Fazer backup do banco atual antes de restaurar (por segurança)
            $this->criarBackup('auto_pre_restore_' . date('Ymd_His'));
            
            // Copiar backup para o banco atual
            File::copy($backupPath, $bancoPath);
            
            // Dar permissão correta
            chmod($bancoPath, 0666);
            
            return redirect()->route('backups.index')
                ->with('success', 'Backup restaurado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->route('backups.index')
                ->with('error', 'Erro ao restaurar backup: ' . $e->getMessage());
        }
    }
    
    // Deletar um backup
    public function destroy($filename)
    {
        try {
            $path = storage_path($this->backupPath . '/' . $filename);
            
            if (File::exists($path)) {
                File::delete($path);
                return redirect()->route('backups.index')
                    ->with('success', 'Backup excluído com sucesso!');
            }
            
            return redirect()->route('backups.index')
                ->with('error', 'Arquivo não encontrado!');
                
        } catch (\Exception $e) {
            return redirect()->route('backups.index')
                ->with('error', 'Erro ao excluir backup: ' . $e->getMessage());
        }
    }
    
    // Método privado para criar backup (usado pelo cron também)
    public function criarBackup($nomePersonalizado = null)
    {
        $bancoPath = database_path('pitstopweb.sqlite');
        
        if (!File::exists($bancoPath)) {
            throw new \Exception('Banco de dados não encontrado!');
        }
        
        // Criar pasta de backups se não existir
        $backupDir = storage_path($this->backupPath);
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0777, true);
        }
        
        // Nome do arquivo
        if ($nomePersonalizado) {
            $nome = $nomePersonalizado . '.sqlite';
        } else {
            $nome = 'backup_' . date('Ymd_His') . '.sqlite';
        }
        
        $backupPath = $backupDir . '/' . $nome;
        
        // Copiar arquivo
        File::copy($bancoPath, $backupPath);
        
        // Dar permissão
        chmod($backupPath, 0666);
        
        // Manter apenas os últimos 30 backups (opcional)
        $this->limparBackupsAntigos(30);
        
        return $nome;
    }
    
    // Limpar backups antigos (mantém apenas os X mais recentes)
    private function limparBackupsAntigos($manter = 30)
    {
        $backupDir = storage_path($this->backupPath);
        
        if (!File::exists($backupDir)) {
            return;
        }
        
        $files = File::files($backupDir);
        
        // Ordenar por data de criação
        usort($files, function($a, $b) {
            return $a->getMTime() - $b->getMTime();
        });
        
        // Remover os mais antigos
        $total = count($files);
        if ($total > $manter) {
            $remover = array_slice($files, 0, $total - $manter);
            foreach ($remover as $file) {
                File::delete($file);
            }
        }
    }
    
    // Formatar tamanho do arquivo
    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < 3) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}