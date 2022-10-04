<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'verso',
    ];

    public function getVersoFormatedAttribute()
    {
        if ($this->attributes['verso'] == 1) {

            return 'SIM';
        }

        return 'NÃO';
    }
}
