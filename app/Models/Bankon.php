<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bankon extends Model
{
    use HasFactory;

    protected $fillable = [
        'cod_bankon',
        'user_id',
    ];
}
