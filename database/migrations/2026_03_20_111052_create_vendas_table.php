<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->increments('id');                    // INTEGER PRIMARY KEY AUTOINCREMENT
            $table->unsignedInteger('cliente_id');       // Cliente que comprou
            $table->string('data');                      // Data da venda (YYYY-MM-DD)
            $table->string('data_vencimento')->nullable(); // Data de vencimento
            $table->decimal('total', 10, 2);             // Total da venda
            $table->string('status')->default('pendente'); // pendente, pago, cancelado
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();

            // Chave estrangeira (SQLite friendly)
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};