<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itens_venda', function (Blueprint $table) {
            $table->increments('id');                    // INTEGER PRIMARY KEY AUTOINCREMENT
            $table->unsignedInteger('venda_id');         // FK para vendas
            $table->unsignedInteger('produto_id');       // FK para produtos
            $table->string('nome_produto');              // Nome do produto (para histórico)
            $table->integer('quantidade');               // Quantidade vendida
            $table->decimal('preco_unitario', 10, 2);    // Preço no momento da venda
            $table->decimal('subtotal', 10, 2);          // quantidade * preco_unitario
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();

            // Chaves estrangeiras
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itens_venda');
    }
};