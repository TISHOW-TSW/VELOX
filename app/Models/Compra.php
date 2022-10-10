<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plano_id',
        'ativo',
        'cortesia',
        'asaas_link',
        'metodo',
        'pay_address',
        'purchase_id',
        'pay_amount',
        'moeda',
        'buscador',
        'status',
        'valor',
        'dia_pagamento',
        'payment_id'
    ];

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }


    public function getAtivoFormatedAttribute()
    {
        if ($this->status == 0) {
            return 'PENDENTE';
        }
       if ($this->status == 1){
           return 'ATIVO';
       }
       if ($this->status == 2){
           return 'EXPIRADA';
       }

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campanha()
    {
        $batalha = Batalha::where('user_id', $this->attributes['user_id'])->where('compra_id', $this->attributes['id'])->orderBy('created_at', 'desc')->first();

        if (isset($batalha)) {
            $startTime = Carbon::now();


            $finishTime = Carbon::parse($batalha['created_at']);

            $totalDuration = $finishTime->diffInMinutes($startTime);
            //return $totalDuration;
            if ($totalDuration >= 14400) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }

    public function campanha2()
    {
        $batalha = Batalha::where('user_id', $this->attributes['user_id'])->where('compra_id', $this->attributes['id'])->orderBy('created_at', 'desc')->first();
        // dd($batalha);

        if (isset($batalha)) {
            $startTime = Carbon::now();


            $finishTime = Carbon::parse($batalha['created_at']);

            $totalDuration = $finishTime->diffInMinutes($startTime);
            // dd($totalDuration);
            if ($totalDuration >= 1440) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }


    public function valormoeda($valor, $moeda)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nowpayments.io/v1/estimate?amount=' . $valor . '&currency_from=usd&currency_to=' . $moeda,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: AACR78H-2S2MB2Z-PGM8VX1-QTFH0C8'
            ),
        ));

        $response = curl_exec($curl);

        $json = json_decode(utf8_encode($response), true);
        // dd($response->estimated_amount);
        curl_close($curl);


        return $json['estimated_amount'];
    }


    public function registracompra($moeda, $compra)
    {
        $ship = Compra::find($compra);
        switch ($moeda) {
            case 1:
                $busca = 'btc';
                break;
            case 2:
                $busca = 'eth';
                break;
            case 3:
                $busca = 'bnbbsc';
                break;
            case 4:
                $busca = 'ltc';
                break;
            case 5:
                $busca = 'trx';
                break;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nowpayments.io/v1/payment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "price_amount": ' . $ship->plano->valor . ',
                "price_currency": "usd",
                "pay_currency": "' . $busca . '",
                "ipn_callback_url": "https://nowpayments.io",
                "order_id": "' . $ship->id . '",
                "order_description": "Payment ship' . $ship->plano->name . '"
              }',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: AACR78H-2S2MB2Z-PGM8VX1-QTFH0C8',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $json = json_decode(utf8_encode($response), true);

        curl_close($curl);
        return $json;

        echo $response;
    }

    public function consultahash()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nowpayments.io/v1/payment/' . $this->attributes['payment_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: AACR78H-2S2MB2Z-PGM8VX1-QTFH0C8'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //dd($response);
        $json = json_decode(utf8_encode($response), true);

        return $json;
        echo $response;
    }


    public function somas()
    {
        return $this->hasMany(Soma::class);
    }


    public function rendimentos()
    {
        return $this->hasMany(Batalha::class,'compra_id','id');
    }

    public function consultahash2()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nowpayments.io/v1/payment/' . $this->attributes['payment_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: AACR78H-2S2MB2Z-PGM8VX1-QTFH0C8'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //dd($response);

        dd($response);
        $json = json_decode(utf8_encode($response), true);

        return $json;
        echo $response;
    }

    public function diasContados(){
        $porcentagem = round(((count($this->rendimentos) * 100)/5), 2);
        return $porcentagem;
    }
}
