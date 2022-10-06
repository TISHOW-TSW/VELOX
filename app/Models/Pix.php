<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pix extends Model
{
    use HasFactory;

    protected $fillable = [
        'cod_banco',
        'banco',
        'chave',
        'user_id'
    ];
}
