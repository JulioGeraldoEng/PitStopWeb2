<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'data',
        'data_vencimento',
        'total',
        'status',
    ];

    // Relacionamento: uma venda pertence a um cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacionamento: uma venda tem muitos itens
    public function itens()
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }

    // Relacionamento: uma venda tem um recebimento
    public function recebimento()
    {
        return $this->hasOne(Recebimento::class);
    }
}