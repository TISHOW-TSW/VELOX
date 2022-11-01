<?php

namespace App\Services;


use Carbon\Carbon;
use GuzzleHttp\Client;

class CalendarService
{
    public function validaDia($data)
    {


$data = Carbon::parse($data)->addDay();
$data = $data->toDateString();

//dd($data);
        $ano = Carbon::now()->year;
        $client = new \GuzzleHttp\Client();
        $request = $client->get('https://brasilapi.com.br/api/feriados/v1/' . $ano);
        $response = $request->getBody()->getContents();
        $busca = json_decode($response);
//dd($busca);
        $busca = (array_column($busca, 'date'));

        //dd($busca);
        return (['respota'=>in_array($data, $busca),'data'=>$data]);

    }
    public function validarDiaPagamento($data)
    {


        $data = Carbon::parse($data);
        $data = $data->toDateString();

//dd($data);
        $ano = Carbon::now()->year;
        $client = new \GuzzleHttp\Client();
        $request = $client->get('https://brasilapi.com.br/api/feriados/v1/' . $ano);
        $response = $request->getBody()->getContents();
        $busca = json_decode($response);
//dd($busca);
        $busca = (array_column($busca, 'date'));

        //dd($busca);
        return (['respota'=>in_array($data, $busca),'data'=>$data]);

    }
}
