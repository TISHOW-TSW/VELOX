<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaldoRendimento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'valor',
        'saque_rendimento',
        'saldo_raiz_id',
    ];

    public function saldoRaqiz()
    {
        return $this->belongsTo(SaldoRaiz::class);
    }
}
