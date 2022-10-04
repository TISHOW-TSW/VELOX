<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doc_id',
        'frente',
        'verso',
        'valido',
        'possui'
    ];


    public function getStatusAttribute()
    {
        if ($this->attributes['valido'] == 0) {
            return "Em Analise";
        }
        if ($this->attributes['valido'] == 1) {
            return "Valido ";
        }
    }
}
