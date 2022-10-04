<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Extrato;
use App\Models\Soma;
use Illuminate\Http\Request;

class AcaoController extends Controller
{

    public function calculorenda($fatura, $nivel)
    {
        // return $fatura;
        // $$$buscadireto4->plano->segundo4->plano->segundo;
        switch ($nivel) {
            case 0:
                $escolha = $fatura->user->direto();
                $frase = 'MY SQUAD';

                break;
            case 1:
                $escolha = $fatura->user->primeiro();
                $frase = 'FIRST SQUAD';

                break;
            case 2:
                $escolha = $fatura->user->segundo();
                $frase = 'SECOND SQUAD';
                break;
            case 3:
                $escolha = $fatura->user->terceiro();
                $frase = 'THIRD SQUAD';
                break;
            case 4:
                $escolha = $fatura->user->quarto();
                $frase = 'FOURTH SQUAD';
                break;
        }
        // $planodireto = Compra::where('user_id', $escolha->id)->where('ativo', 1)->get();

        $planodireto = Compra::where('user_id', $escolha->id)->where('ativo', 1)->with(['plano' => function ($query) {
            $query->orderBy('valor', 'ASC');
        }])->first();

        // dd($planodireto);

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
                case 0:

                    $acao = $buscamenor->plano->direto;
                    break;
                case 1:

                    $acao = $buscamenor->plano->primeiro;
                    break;
                case 2:

                    $acao = $buscamenor->plano->segundo;
                    break;
                case 3:

                    $acao = $buscamenor->plano->terceiro;
                    break;
                case 4:

                    $acao = $buscamenor->plano->quarto;
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
                'user_id' => $fatura->user->direto()->id,
                'indicado_id' => $fatura->user->id,
                'pontos' => $fatura->plano->pontos,
                'saldo' => $saldo
            ];

            // $pontuacao = $user->pontos + $plano->pontos;
            $dados = [
                'tipo' => 0,
                'descricao' => 'bonus ref. login ' . $fatura->user->name . ' ' . $frase . ' ' . $fatura->plano->name,
                'valor' => $saldo,
                'user_id' => $escolha->id,
            ];
            //   Soma::create(['compra_id' => $buscamenor, 'valor' => $saldo]);
            Extrato::create($extrato);
            \App\Models\Valorindicacao::create($dados);
        }
    }
}
