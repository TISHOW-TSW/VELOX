<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Extrato;
use App\Models\Soma;
use Illuminate\Http\Request;

class AcaoController extends Controller
{

    function calculorenda($fatura, $nivel)
    {
       // dd($fatura);
        //return $fatura;
        // $$$buscadireto4->plano->segundo4->plano->segundo;
        switch ($nivel) {

            case 1:
                $escolha = $fatura->user->primeiro();
                $frase = 'PRIMEIRO NIVEL';
                $porc = $fatura->plano->primeiro;

                break;
            case 2:
                $escolha = $fatura->user->segundo();
                $frase = 'SEGUNDO NIVEL';
                $porc = $fatura->plano->segundo;

                break;
            case 3:
                $escolha = $fatura->user->terceiro();
                $frase = 'TERCEIRO NIVEL';
                $porc = $fatura->plano->terceiro;
                break;

        }
        // $planodireto = Compra::where('user_id', $escolha->id)->where('ativo', 1)->get();

        $planodireto = Compra::where('user_id', $escolha->id)->where('ativo', 1)->with(['plano' => function ($query) {
            $query->orderBy('valor', 'ASC');
        }])->first();

        //dd($planodireto);

        //  $locations = App\Location::with(['region' => function ($query) {
        //    $query->orderBy('name');
        // }])->get();

        if (!empty($planodireto)) {
            //dd($planodireto);
            // $buscadireto4->plano->segundo = [];
            $controle = 0;
            //   $varia = 0;

            $buscamenor = $planodireto;


            //    echo $buscadireto4->plano->segundo4->plano->segundo->id."<br>";
            echo $buscamenor->id . "<br>";

            $somatotal = $buscamenor->somas->sum('valor');


            $dobroplano = $buscamenor->plano->valor * 2;

            switch ($nivel) {

                case 1:

                    $acao = $buscamenor->plano->primeiro;
                    break;
                case 2:

                    $acao = $buscamenor->plano->segundo;
                    break;
                case 3:

                    $acao = $buscamenor->plano->terceiro;
                    break;

            }
            // dd($acao);
            $saldo = ($fatura->plano->valor - ($fatura->plano->valor * ((100 - $acao) / 100)));
            $soma = $somatotal + $saldo;

            if ($dobroplano < $soma) {

                $diferenca = $dobroplano - $somatotal;
                //dd($diferenca);



                $buscamenor->fill(['ativo' => 2]);
                $buscamenor->save();
                $ordem = $buscamenor->user->ordem + 1;
                $buscamenor->user->fill(['ordem' => $ordem]);
                $buscamenor->user->save();
                $tem = Compra::where('user_id', $escolha->id)->where('ativo', 1)->count();

                if ($tem > 0) {
                    $saldo = $saldo;
                } else {
                    $saldo = $diferenca;
                }
            } else {
                $saldo = $saldo;
            }
            //
            //dd($saldo);
            Soma::create(['compra_id' => $buscamenor->id, 'valor' => $saldo]);
            $extrato = [
                'user_id' => $fatura->user->primeiro()->id,
                'indicado_id' => $fatura->user->id,
                'pontos' => 0,
                'saldo' => $saldo
            ];

            // $pontuacao = $user->pontos + $plano->pontos;
            $dados = [
                'tipo' => 0,
                'descricao' => 'Bonus de '.$porc.' sobre o carro '. $fatura->plano->name.' no valor de'. ' R$'.$saldo. ' do login'. $fatura->user->login . ' do seu ' . $frase,
                'valor' => $saldo,
                'user_id' => $escolha->id,
            ];
            //   Soma::create(['compra_id' => $buscamenor, 'valor' => $saldo]);
            Extrato::create($extrato);
            \App\Models\Valorindicacao::create($dados);
        }
    }
}
