<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    use HasFactory;

    // ← ADICIONAR ESTA LINHA
    protected $table = 'itens_venda';

    protected $fillable = [
        'venda_id',
        'produto_id',
        'nome_produto',
        'quantidade',
        'preco_unitario',
        'subtotal',
    ];

    // Relacionamento: item pertence a uma venda
    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    // Relacionamento: item pertence a um produto
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}