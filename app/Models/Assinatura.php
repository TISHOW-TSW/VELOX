<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    use HasFactory;

    protected $fillable =

        [
            'inicio',
            'fim',
            'buscador',
            'status',
            'plano_id',
            'user_id',
            'diapagamento',
            'tipo',
            'unico',
            'valor'
        ];


    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getStatusFormatedAttribute()
    {
        if ($this->status == 0) {
            return 'PENDENTE';

        } else {
            return 'PAGA';
        }
    }
}
