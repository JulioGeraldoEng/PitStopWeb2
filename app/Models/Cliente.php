<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'telefone',
        'email',
        'observacao',
    ];

    // Relacionamento: um cliente pode ter muitas vendas
    public function vendas()
    {
        return $this->hasMany(Venda::class);    
    }
    
}