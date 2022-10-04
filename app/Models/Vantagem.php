<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vantagem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];


    public function planos(){
        return $this->belongsToMany(Plano::class);
    }
}
