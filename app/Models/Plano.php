<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'valor',
        'ativo',
        'direto',
        'primeiro',
        'segundo',
        'terceiro',
        'img',

    ];

    public function vantagems()
    {
        return $this->belongsToMany(Vantagem::class);
    }
}
