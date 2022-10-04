<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'valor',
        'pontuacao',
        'ordem',
    ];


    public function getImgAttribute($n)
    {
      //  dd($n);
        switch ($n+1) {
            case 1:
                return "consultor.png";
            case 2:
                return "consultor.png";
            case 3:
                return "consultor_master.png";
                break;
            case 4:
                return "supervisor.png";
            case 5:
                return "supervisor_master.png";

            case 6:
                return "cordenador.png";

            case 7:
                return "coordenador_master.png";

            case 8:
                return "gerente.png";

            case 9:
                return "gerente_master.png";

            case 10:
                return "diretor.png";

            case 11:
                return "diretor_master.png";

            case 12:
                return "presidente.png";

            case 13:
                return "presidente_master.png";
        }

    }
}
