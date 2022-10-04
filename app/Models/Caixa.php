<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'valor',
        'tipo',
        'user_id',
    ];


    public function getEntradaAttribute()
    {
        $total = $this->all();
        dd($total);
    }


    public function getStatusAttribute()
    {
        if ($this->attributes['tipo'] == 1) {
            return 'Entrada';
        }

        return 'Saida';
    }
}
