<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaldoRaiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'valor',
        'user_id',
        'compra_id',
    ];

    public function saldoRendimento()
    {
        return $this->hasOne(SaldoRendimento::class);
    }
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
}
