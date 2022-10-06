<?php

namespace App\Services;


use Carbon\Carbon;
use GuzzleHttp\Client;

class CalendarService
{
    public function validaDia($data)
    {
        $ano = Carbon::now()->year;
        $client = new \GuzzleHttp\Client();
        $request = $client->get('https://brasilapi.com.br/api/feriados/v1/' . $ano);
        $response = $request->getBody()->getContents();
        $busca = json_decode($response);

        $busca = (array_column($busca, 'date'));
        return (in_array($data, $busca));

    }
}
