<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\ItemVendaController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\BackupController;

// Rotas públicas
Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
//Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
//Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas protegidas (requer login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas apenas para ADMIN
    Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Rotas de Clientes (apenas admin)
    Route::resource('clientes', ClienteController::class);

    // Produtos
    Route::resource('produtos', ProdutoController::class);

    // Vendas
    Route::resource('vendas', VendaController::class);

    // Dentro do middleware 'is_admin'
    Route::delete('item-venda/{id}', [ItemVendaController::class, 'destroy'])
        ->name('item-venda.destroy');
    Route::post('item-venda', [ItemVendaController::class, 'store'])
        ->name('item-venda.store');
    Route::get('item-venda/{id}/edit', [ItemVendaController::class, 'edit'])
        ->name('item-venda.edit');
    Route::put('item-venda/{id}', [ItemVendaController::class, 'update'])
        ->name('item-venda.update');

    // Rotas de Relatórios
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/', [RelatorioController::class, 'index'])->name('index');
        Route::post('/vendas-periodo', [RelatorioController::class, 'vendasPorPeriodo'])->name('vendas.periodo');
        Route::post('/produtos-ranking', [RelatorioController::class, 'produtosMaisVendidos'])->name('produtos.ranking');
        Route::get('/faturamento-mensal', [RelatorioController::class, 'faturamentoMensal'])->name('faturamento.mensal');
    });

    // Rotas de Backup
    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/create', [BackupController::class, 'create'])->name('create');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('download');
        Route::post('/restore/{filename}', [BackupController::class, 'restore'])->name('restore');
        Route::delete('/destroy/{filename}', [BackupController::class, 'destroy'])->name('destroy');
    });
});