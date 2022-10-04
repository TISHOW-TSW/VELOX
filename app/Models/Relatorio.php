<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'entrada',
        'saques',
        'pendentes',
        'despesas',
        'ativos',
        'inativos',
        'pagas',
        'naopagas'
    ];



    public function getDataFormatedAttribute()
    {
        $data = Carbon::create($this->attributes['data'])->locale('pt_BR')->isoFormat('D-MMMM-Y');

        return $data;
    }
}
