<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valorindicacao extends Model
{
    use HasFactory;
    protected $fillable = [
        'descricao',
        'valor',
        'tipo',
        'user_id',
        'created_at'
    ];
}
