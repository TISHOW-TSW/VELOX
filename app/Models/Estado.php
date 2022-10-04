<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function enderecos()
    {
        return $this->hasMany(Endereco::class);
    }
}
