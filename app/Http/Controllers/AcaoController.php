<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Extrato;
use App\Models\Soma;
use App\Models\Valorindicacao;
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

        //dd($escolha);
        // $planodireto = Compra::where('user_id', $escolha->id)->where('ativo', 1)->get();

        $planodireto = Compra::where('user_id', $escolha->id)->with(['plano' => function ($query) {
            $query->orderBy('valor', 'ASC');
        }])->first();

      //  dd($planodireto);

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


            //$dobroplano = $buscamenor->plano->valor * 2;

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
            $saldo = (($acao / 100) * $fatura->plano->valor);
            //$saldo = ($fatura->plano->valor - ($fatura->plano->valor * ((100 - $acao) / 100)));
            $soma = $somatotal + $saldo;

            //if ($dobroplano < $soma) {

                //$diferenca = $dobroplano - $somatotal;
                //dd($diferenca);



                //$buscamenor->fill(['ativo' => 2]);
                //$buscamenor->save();
                //$ordem = $buscamenor->user->ordem + 1;
                //$buscamenor->user->fill(['ordem' => $ordem]);
                //$buscamenor->user->save();
                //$tem = Compra::where('user_id', $escolha->id)->where('ativo', 1)->count();

                //if ($tem > 0) {
                  //  $saldo = $saldo;
                //} else {
                  //  $saldo = $diferenca;
                //}
           // } else {
                //$saldo = $saldo;
            //}
            //
            //dd($saldo);
            //Soma::create(['compra_id' => $buscamenor->id, 'valor' => $saldo]);
            $extrato = [
                'user_id' => $fatura->user->id,
                'indicado_id' => $fatura->user->id,
                'pontos' => 0,
                'saldo' => $saldo
            ];


            //dd($extrato);
            // $pontuacao = $user->pontos + $plano->pontos;
            $dados = [
                'tipo' => 0,
                'descricao' => 'Bonus de '.$porc.'% sobre o carro '. $fatura->plano->name.' no valor de'. ' R$'.$saldo. ' do login'. $fatura->user->login . ' do seu ' . $frase,
                'valor' => $saldo,
                'user_id' => $escolha->id,
            ];
            //Soma::create(['compra_id' => $buscamenor, 'valor' => $saldo]);
            Extrato::create($extrato);
            \App\Models\Valorindicacao::create($dados);
        }
    }


    public function verifyNivel($nivel, $user)
    {

        switch ($nivel) {

            case 1:
                $escolha = $user->primeiro();
                $direto = false;
                $frase = 'Primeiro';

                break;
            case 2:
                $escolha = $user->segundo();
                $direto = false;
                $frase = 'Segundo';
                break;
            case 3:
                $escolha = $user->terceiro();
                $direto = false;
                $frase = 'Terceiro';
                break;


        }

//dd($user);
        return $escolha;

    }

    public function attSaldoIndicaNovaAssinatura($fatura, $nivel)
    {
        //dd($fatura->user);
        switch ($nivel) {

            case 1:
                $escolha = $fatura->user->primeiro();
                $direto = false;
                $frase = '1°';

                break;
            case 2:
                $escolha = $fatura->user->segundo();
                $direto = false;
                $frase = '2°';
                break;
            case 3:
                $escolha = $fatura->user->terceiro();
                $direto = false;
                $frase = '3°';
                break;

        }

        // dd($escolha);

        $planodireto = Compra::where('user_id', $escolha->id)->where('status', 1)->with(['plano' => function ($query) {
            $query->orderBy('valor', 'ASC');
        }])->first();

        if (!empty($planodireto)) {


            switch ($nivel) {

                case 1:

                    $acao = $planodireto->plano->primeiro;
                    break;
                case 2:

                    $acao = $planodireto->plano->segundo;
                    break;
                case 3:

                    $acao = $planodireto->plano->terceiro;
                    break;

            }
            $credito = (($acao / 100) * $fatura->plano->valor);

            $this->atualizaSaldoIndicacao($escolha->id, $credito, 4, $frase, $acao, $fatura->plano->valor, $fatura->user->login,$fatura);



        }
    }

    public function atualizaSaldoIndicacao($user, $credito, $tipo, $nivel, $porcentagem, $valorFatura, $afiliado,$fatura)
    {
       //dd($user);
        switch ($tipo) {
            case 4:
                $descricao = 'Bonificação referente a nova adesão (' . $nivel . ')';
                break;
            case 5:
                $descricao = 'Bonificação referente a renovação (' . $nivel . ')';
                break;
        }


        $dados = [
            'tipo' => 0,
            'descricao' => 'Bonus de '.$porcentagem.'% sobre o carro '.$fatura->plano->name.' com valor de R$'.$fatura->plano->valor.' no valor de'. ' R$'.$credito. ' do login'. $fatura->user->login . ' do seu ' . $descricao,
            'valor' => $credito,
            'user_id' => $user,
        ];
///fazendo pra ve se vai

        \App\Models\Valorindicacao::create($dados);


      //  $saldoIndicacao = Valorindicacao::where('user_id', $user)->first();

    //    dd($saldoIndicacao);
     //   if (!isset($saldoIndicacao)) {
       //     $saldoIndicacao = new SaldoIndicacao();
         //   $saldoIndicacao->user_id = $user;
           // $saldoIndicacao->valor = 0.00;
           // $saldoIndicacao->save();
       // }
       // $saldoIndicacao->valor = $saldoIndicacao->valor += $credito;
     //   $operacao = new Operacao();
       // $operacao->user_id = $user;
       // $operacao->tipo = $tipo;
       // $operacao->status = 1;
      //  $operacao->saldo = 3;
      //  $operacao->saldo_id = $saldoIndicacao->id;
      //  $operacao->valor = $credito;
      //  $operacao->descricao = 'Bonificação de ' . $porcentagem . '% sobre a ativação de uma fatura no valor de R$' . $valorFatura . ' do login ' . $afiliado . ' do seu ' . $nivel . ' nível.';
      //  $operacao->save();
      //  $saldoIndicacao->update();

   //     $this->addSaldoGeral($credito, 0, 3);

    }

}
