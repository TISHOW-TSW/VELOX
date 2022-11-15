<?php

namespace App\Services;


use App\Models\Compra;
use Carbon\Carbon;
use GuzzleHttp\Client;

class ApiPixService
{


    public function gerarPix()
    {
        $compra = Compra::find(234);
        //dd($compra->plano->valor);
        $post = ['token_secreto' => 'EA8gnGEHKil8o6MQl2ov42spWqlPL2JKF',
            'id_ordem' => $compra->id, //Código da Ordem
            'descricao' => 'Compra' . $compra->plano->name, //Exemplo: Pedido 51
            //'valor' => floatval($compra->plano->valor), //Exemplo: 1.00];
            'valor' => floatval($compra->plano->valor), //Exemplo: 1.00];

        ];

        $ch = curl_init('https://api.thebank.com.br/velox/cobranca/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        //header('Content-Type: application/json');
// Resposta


        $response = stripslashes(curl_exec($ch));
        $res = json_decode(curl_exec($ch), true);


        $dados = explode(",", $response);



        //curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //$res=json_decode(curl_exec($ch),true);

       // dd($res);
        //prindd($res);

        //$codigoTransacao = $res->id_transacao;

        //dd($codigoTransacao);

        $compra->update(['pix' => 'novo']);

        //dd(json_decode($response));
        // print_r($response['id_transacao']);
        return json_decode($response);

    }


}
