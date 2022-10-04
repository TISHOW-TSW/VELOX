<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batalha extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'plano_id',
        'compra_id'
    ];
}
