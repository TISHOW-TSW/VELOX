<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valorredimento extends Model
{
    use HasFactory;
    protected $fillable = [
        'descricao',
        'valor',
        'tipo',
        'user_id',
        'compra_id'
    ];
}
