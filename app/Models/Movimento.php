<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'descricao',
        'valor',
        'user_id',
    ];


    public function getStatusFormatedAttribute()
    {
        if ($this->attributes['tipo'] == 0) {
            return 'In';
        }
        return 'Out';
    }
}
