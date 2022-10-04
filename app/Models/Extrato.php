<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extrato extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'indicado_id',
        'pontos',
        'saldo'


    ];


    public function indicado()
    {
        return $this->belongsTo(User::class, 'indicado_id', 'id');
    }
}
