<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'estoque',
    ];

    // Relacionamento: um produto pode estar em muitos itens de venda
    public function itensVenda()
    {
        return $this->hasMany(ItemVenda::class);
    }
}