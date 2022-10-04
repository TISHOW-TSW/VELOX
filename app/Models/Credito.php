<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    use HasFactory;


    protected $fillable = [
        'valor',
        'buscador',
        'status',
        'diapagamento',
        'user_id',
        'tipo',
        'plano_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class);
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
