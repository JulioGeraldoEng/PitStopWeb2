<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');           // INTEGER PRIMARY KEY AUTOINCREMENT
            $table->string('nome');             // Nome do produto
            $table->text('descricao')->nullable();  // Descrição
            $table->decimal('preco', 10, 2);    // Preço (R$ 99999999,99)
            $table->integer('estoque')->default(0); // Quantidade em estoque
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};