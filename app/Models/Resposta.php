<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{


    use HasFactory;



    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'visto'

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
