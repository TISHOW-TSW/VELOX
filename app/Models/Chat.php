<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;


    protected $fillable = [

        'user_id',
        'aberto',
        'message',
        'visto'

    ];



    public function status()
    {
        if ($this->attributes['aberto'] == 0) {
            return 'open';
        }
        if ($this->attributes['aberto'] == 1) {
            return 'close';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }
}
