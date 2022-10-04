<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video',
        'valido',
    ];


    public function getStatusAttribute()
    {
        if ($this->attributes['valido'] == 1) {
            return 'Validad';
        }
        if ($this->attributes['valido'] == 0) {
            return 'pending';
        }
        if ($this->attributes['valido'] == 2) {
            return 'denied';
        }


        return 'Saida';
    }

    public function user()
    {
       return $this->belongsTo(User::class);
    }
}
