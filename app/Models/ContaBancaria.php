<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ContaBancaria extends Model
{
    use HasFactory;

    protected $fillable = [
        'codbanco',
        'agencia',
        'conta',
        'tipo_conta',
        'titular_name',
        'titular_documento',
        'user_id'
    ];


    public function getTipoFormatedAttribute()
    {
        // return 'oi';
        if ($this->attributes['tipo_conta'] == 1){
            return "Conta Corrente";
        }
        else{
            return "Conta Poupança";
        }
    }
}
