<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');           // INTEGER PRIMARY KEY AUTOINCREMENT
            $table->string('nome');             // Nome do cliente
            $table->string('telefone')->nullable();      // Telefone (único? vc decide)
            $table->string('email')->nullable();         // Email
            $table->text('observacao')->nullable();      // Observações
            $table->string('created_at')->nullable();    // Data criação
            $table->string('updated_at')->nullable();    // Data atualização
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};