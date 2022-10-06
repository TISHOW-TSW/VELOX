<?php

namespace App\Services;

use App\Models\Caixa;
use App\Models\Compra;
use App\Models\Notificacao;
use App\Models\Operacao;
use App\Models\Produto;
use App\Models\SaldoGeral;
use App\Models\SaldoIndicacao;
use App\Models\SaldoProduto;
use App\Models\SaldoRendimento;
use App\Models\User;
use App\Services\MailService;
use Carbon\Carbon;

class OperationService
{
    public function iniciaCancelamentoContrato($userId, $produtoId)
    {

        $saldoRaiz = SaldoProduto::where('produto_id', $produtoId)->first();
        $saldoRaizId = $saldoRaiz->id;
        $valor = round($saldoRaiz->valor * 0.75, 2);

        // Verifica se houve saque por rendimento
        $saldoRendimento = SaldoRendimento::where('saldo_produto_id', $saldoRaizId)->first();
        $saquesRendimento = Operacao::where('tipo', 1)->where('status', 1)->where('saldo', 2)->where('saldo_id', $saldoRendimento->id)->sum('valor');

        $valor = round($valor - $saquesRendimento, 2);


        if($saquesRendimento > 0)
            $descricao = 'Raiz por cancelamento com estorno de saque rendimento';
        else
            $descricao = 'Raiz por cancelamento';

        $operacao = new Operacao();
        $operacao->user_id = $userId;
        $operacao->tipo = 1;
        $operacao->status = 2;
        $operacao->saldo = 1;
        $operacao->saldo_id = $saldoRaiz->id;
        $operacao->valor = $valor;
        $operacao->descricao = $descricao;
        $operacao->produto = $produtoId;
        $operacao->save();

        $valor = round($saldoRaiz->valor * 0.75, 2);
        // valor, tipo, saldo
        $this->addSaldoGeral($valor, 1, 1);
        $saldoRaiz->valor = 0.00;
        $saldoRaiz->update();

        return $operacao;
    }

    public function iniciaCompraSaldoRaiz($userId, $valor, $produto)
    {

        $operacao = new Operacao();
        $operacao->user_id = $userId;
        $operacao->tipo = 2;
        $operacao->status = 2;
        $operacao->saldo = 1;
        $operacao->valor = $valor;
        $operacao->descricao = 'Raiz';
        $operacao->produto = $produto;
        $operacao->save();


        return $operacao;
    }

    public function finalizaCompraSaldoRaiz($operacaoId, Compra $compra)
    {
        $operacao = Operacao::find($operacaoId);
        $saldoRaiz = $this->addSaldoRaiz($operacao->user_id, $operacao->valor, $operacao->produto, $compra->id);
        $operacao->status = 1;
        $operacao->saldo_id = $saldoRaiz->id;
        $operacao->descricao = 'Raiz';
        $operacao->update();
        $caixa = new Caixa();
        $caixa->descricao = 'Ativação da fatura #'.$compra->id.' do usuário '.$operacao->user->login;
        $caixa->valor = $operacao->valor;
        $caixa->user_id = $operacao->user_id;
        $caixa->tipo = 1;
        $caixa->save();
        return $operacao;


        // $user = User::find($operacao->user_id);

        // $faturas = Compra::where('ativo', 0)->orderBy('id', 'asc')->where('pay_address', "!=", 'NULL')->get();

        // $patrocinador1 = $user->patrocinador;

        // if (!$patrocinador1 === [] || !$patrocinador1 === null) {
        //     $produtosCompras = Compra::where('user_id', $patrocinador1->id)->where('status', 1)->pluck('produto_id');

        //     if (sizeof($produtosCompras) > 0) {
        //         $produtoMaisAlto = Produto::whereIn('id', $produtosCompras)->desc('valor')->first();
        //         $produto = Produto::find($produtoMaisAlto);

        //     } else {
        //         $porcentagem = 0;
        //         $valor = 0;
        //     }


        // }
    }

    public function renovacaoDePlano($saldoRaizId)
    {
        $raiz = SaldoProduto::findOrFail($saldoRaizId);
        $raiz->saque_renovacao = 1;
        $raiz->save();
        $compra = new Compra();
        $compra->user_id = $raiz->user_id;
        $compra->produto_id = $raiz->produto_id;
        $compra->status = 1;
        $compra->data_pagamento = Carbon::now();
        $compra->save();
        $novo = new SaldoProduto();
        $novo->produto_id = $raiz->produto_id;
        $novo->user_id = $raiz->user_id;
        $novo->valor = $raiz->valor;
        $novo->compra_id = $compra->id;
        $novo->save();
        $operacao = new Operacao();
        $operacao->user_id = $novo->user_id;
        $operacao->tipo = 5;
        $operacao->status = 1;
        $operacao->saldo = 1;
        $operacao->saldo_id = $novo->id;
        $operacao->valor = $novo->valor;
        $operacao->descricao = 'Renovação de seu plano no valor de R$ '.$novo->valor;
        $operacao->produto = $novo->produto_id;
        $operacao->save();
        $this->addSaldoRendimento($novo->id, $novo->user_id, Carbon::now());
        $this->attSaldoIndicaRenovacao($compra);

    }

    public function attSaldoIndicaNovaAssinatura($fatura, $nivel)
    {
        //dd($fatura->user);
        switch ($nivel) {
            case 0:
                $escolha = $fatura->user->patrocinador;
                $direto = true;
                $frase = '1°';

                break;
            case 1:
                $escolha = $fatura->user->segundo();
                $direto = false;
                $frase = '2°';

                break;
            case 2:
                $escolha = $fatura->user->terceiro();
                $direto = false;
                $frase = '3°';
                break;
            case 3:
                $escolha = $fatura->user->quarto();
                $direto = false;
                $frase = '4°';
                break;
            case 4:
                $escolha = $fatura->user->quinto();
                $direto = false;
                $frase = '5°';
                break;
        }

        // dd($escolha);

        $planodireto = Compra::where('user_id', $escolha->id)->where('status', 1)->with(['produto' => function ($query) {
            $query->orderBy('valor', 'ASC');
        }])->first();

        if (!empty($planodireto)) {
            // $controle = 0;

            // $buscamenor = $planodireto;


            //    echo $buscadireto4->plano->segundo4->plano->segundo->id."<br>";
            // echo $buscamenor->id . "<br>";

            // $somatotal = $buscamenor->somas->sum('valor');


            // $dobroplano = $buscamenor->plano->valor * 2;

            switch ($nivel) {
                case 0:

                    $acao = $planodireto->produto->direto;

                    break;
                case 1:

                    $acao = $planodireto->produto->segundo;
                    break;
                case 2:

                    $acao = $planodireto->produto->terceiro;
                    break;
                case 3:

                    $acao = $planodireto->produto->quarto;
                    break;
                case 4:

                    $acao = $planodireto->produto->quinto;
                    break;
            }
            $credito = (($acao / 100) * $fatura->produto->valor);

            $this->atualizaSaldoIndicacao($escolha->id, $credito, 4, $frase, $acao, $fatura->produto->valor, $fatura->user->login);

            // $dados = [
            //     'tipo' => 0,
            //     'descricao' => 'bonus ref. login ' . $fatura->user->name . ' ' . $frase . ' ' . $fatura->plano->name,
            //     'valor' => $saldo,
            //     'user_id' => $escolha->id,
            // ];
            //   Soma::create(['compra_id' => $buscamenor, 'valor' => $saldo]);
        } else {
            if ($direto) {
                $credito = ((10 / 100) * $fatura->produto->valor);

                $this->atualizaSaldoIndicacao($escolha->id, $credito, 4, $frase, 10, $fatura->valor, $fatura->user->login);
            } else {
                $this->atualizaSaldoIndicacao($escolha->id, 0, 4, $frase, 0, $fatura->valor, $fatura->user->login);
            }
        }
    }

    public function attSaldoIndicaRenovacao($fatura)
    {
        $escolha = $fatura->user->patrocinador;
        if (!isset($escolha)) {
            return;
        }
        $planodireto = Compra::where('user_id', $escolha->id)->where('status', 1)->with(['produto' => function ($query) {
            $query->orderBy('valor', 'ASC');
        }])->first();

        if (!empty($planodireto)) {
            $acao = $planodireto->produto->direto;
            $credito = ((($acao / 100) * $fatura->produto->valor) / 2);
            $this->atualizaSaldoIndicacao($escolha->id, $credito, 5, '1°', $acao, $fatura->valor, $fatura->user->login);
        } else {
            $this->atualizaSaldoIndicacao($escolha->id, 0, 5, '1°', 0, $fatura->valor, $fatura->user->login);
        }
    }


    public function iniciaSaqueSaldoRaiz($saldoRaizId, $meio)
    {
//dd($saldoRaizId);
        $saldoRaiz = SaldoProduto::find($saldoRaizId);
        //dd($saldoRaiz);
        $saldoRaiz->saque_renovacao = 1;
        $saldoRaiz->save();
        $operacao = new Operacao();
        $operacao->user_id = $saldoRaiz->user_id;
        $operacao->tipo = 1;
        $operacao->status = 2;
        $operacao->saldo = 1;
        $operacao->saldo_id = $saldoRaiz->id;
        $operacao->valor = $saldoRaiz->valor;
        $operacao->descricao = 'Solicitação de saque raiz';
        $operacao->produto = $saldoRaiz->produto_id;
        $operacao->meio_saque = $meio;
        $operacao->save();
        $this->saqueSaldoraiz($saldoRaiz->id);
        $notificacao = new Notificacao();
        $notificacao->user_id = $saldoRaiz->user_id;
        $notificacao->mensagem = 'Pronto, seu saque foi solicitado,agora é só esperar o prazo de nossa empresa e em breve você receberá em sua conta o dinheiro :).';
        $notificacao->save();

        $ms = new MailService();
        $destinatario = $saldoRaiz->user->email;
        $ms->saquePendente($destinatario, $operacao);

        return $operacao;
    }

    public function finalizaSaqueSaldoRaiz($operacaoId, $compra=null)
    {
        $operacao = Operacao::find($operacaoId);
        $operacao->status = 1;
        $operacao->update();
        $caixa = new Caixa();
        $caixa->descricao = 'Pagamento do saque #'.$operacao->id.' do usuário '.$operacao->user->login;
        $caixa->valor = $operacao->valor;
        $caixa->user_id = $operacao->user_id;
        $caixa->tipo = 2;
        $caixa->save();

        $ms = new MailService();
        $destinatario = $operacao->user->email;
        $ms->saquePago($destinatario, $operacao);

        return $operacao;
    }

    public function iniciaSaqueSaldoRendimento($userId, $valor, $saldoRendimentoId, $meio)
    {
        $operacao = new Operacao();
        $operacao->user_id = $userId;
        $operacao->tipo = 1;
        $operacao->status = 2;
        $operacao->saldo = 2;
        $operacao->saldo_id = $saldoRendimentoId;
        $operacao->valor = $valor;
        $operacao->descricao = 'Solicitação de saque de rendimento';
        $operacao->meio_saque = $meio;
        $operacao->save();
        $this->saqueSaldoRendimento($operacao->saldo_id, $valor);
        $notificacao = new Notificacao();
        $notificacao->user_id = $userId;
        $notificacao->mensagem = 'Pronto, seu saque foi solicitado,agora é só esperar o prazo de nossa empresa e em breve você receberá em sua conta o dinheiro :).';
        $notificacao->save();

        $ms = new MailService();
        $destinatario = $operacao->user->email;
        $ms->saquePendente($destinatario, $operacao);

        return $operacao;
    }

    public function finalizaSaqueSaldoRendimento($operacaoId, $compra=null)
    {
        $operacao = Operacao::find($operacaoId);
        $operacao->status = 1;
        $operacao->update();
        $caixa = new Caixa();
        $caixa->descricao = 'Pagamento do saque #'.$operacao->id.' do usuário '.$operacao->user->login;
        $caixa->valor = $operacao->valor;
        $caixa->user_id = $operacao->user_id;
        $caixa->tipo = 2;
        $caixa->save();

        $ms = new MailService();
        $destinatario = $operacao->user->email;
        $ms->saquePago($destinatario, $operacao);

        return $operacao;
    }

    public function iniciaSaqueSaldoIndicacao($saldoIndicacaoId, $valor, $meio)
    {
        $saldoIndicacao = SaldoIndicacao::find($saldoIndicacaoId);

        $operacao = new Operacao();
        $operacao->user_id = $saldoIndicacao->user_id;
        $operacao->tipo = 1;
        $operacao->status = 2;
        $operacao->saldo = 3;
        $operacao->saldo_id = $saldoIndicacaoId;
        $operacao->valor = $valor;
        $operacao->descricao = 'Solicitação de saque de rede';
        $operacao->meio_saque = $meio;
        $operacao->save();
        $this->saqueSaldoIndicacao($operacao->saldo_id, $operacao->valor);
        $notificacao = new Notificacao();
        $notificacao->user_id = $saldoIndicacao->user_id;
        $notificacao->mensagem = 'Pronto, seu saque foi solicitado,agora é só esperar o prazo de nossa empresa e em breve você receberá em sua conta o dinheiro :).';
        $notificacao->save();

        $ms = new MailService();
        $destinatario = $operacao->user->email;
        $ms->saquePendente($destinatario, $operacao);

        return $operacao;
    }

    public function finalizaSaqueSaldoIndicacao($operacaoId, $compra=null)
    {
        $operacao = Operacao::find($operacaoId);

        $operacao->status = 1;//
        $operacao->update();
        $caixa = new Caixa();
        $caixa->descricao = 'Pagamentodo saque #'.$operacao->id.' do usuário '.$operacao->user->login;
        $caixa->valor = $operacao->valor;
        $caixa->user_id = $operacao->user_id;
        $caixa->tipo = 2;
        $caixa->save();

        $ms = new MailService();
        $destinatario = $operacao->user->email;
        $ms->saquePago($destinatario, $operacao);

        return $operacao;
    }

    public function addSaldoRaiz($userId, $valor, $produtoId, $compraId)
    {
        $saldoRaiz = new SaldoProduto();
        $saldoRaiz->produto_id = $produtoId;
        $saldoRaiz->user_id = $userId;
        $saldoRaiz->valor = $valor;
        $saldoRaiz->compra_id = $compraId;
        $saldoRaiz->save();

        $this->addSaldoRendimento($saldoRaiz->id, $userId, Carbon::now());
        $this->addSaldoGeral($valor, 0, 1);

        return $saldoRaiz;
    }

    public function addSaldoRendimento($saldoProdutoId, $userId, $data)
    {
        $saldoRendimento = new SaldoRendimento();
        $saldoRendimento->saldo_produto_id = $saldoProdutoId;
        $saldoRendimento->user_id = $userId;
        $saldoRendimento->valor = 0.00;
        $saldoRendimento->data_limite = $data;
        $saldoRendimento->contador = 0;
        $saldoRendimento->save();
        //dd($saldoRendimento);
    }

    public function saqueSaldoraiz($saldoRaizId)
    {
        $saldoRaiz = SaldoProduto::find($saldoRaizId);
        // valor, tipo, saldo
        $this->addSaldoGeral($saldoRaiz->valor, 1, 1);
        $saldoRaiz->valor = 0.00;
        $saldoRaiz->update();
    }

    public function estornoSaldoraiz($operacao, $registrar=false, $observacoes=null)
    {
        $saldoRaiz = SaldoProduto::find($operacao->saldo_id);
        // valor, tipo, saldo
        $this->addSaldoGeral($saldoRaiz->valor, 0, 1);
        $saldoRaiz->valor = $operacao->valor;
        $saldoRaiz->update();

        if($registrar) {
            // Registrando o estorno em operações
            $operacaoEstorno = new Operacao();
            $operacaoEstorno->user_id = $saldoRaiz->user_id;
            $operacaoEstorno->tipo = 6;
            $operacaoEstorno->status = 1;
            $operacaoEstorno->saldo = 1;
            $operacaoEstorno->saldo_id = $saldoRaiz->id;
            $operacaoEstorno->valor = $operacao->valor;
            $operacaoEstorno->descricao = 'Estorno de saque raiz';
            $operacaoEstorno->produto = $saldoRaiz->produto_id;
            $operacaoEstorno->meio_saque = $operacao->meio_saque;
            $operacaoEstorno->observacoes = $observacoes;
            $operacaoEstorno->save();

            $operacao->estornado  = true;
            $operacao->observacoes = $observacoes;
            $operacao->operacao_estorno_id  = $operacaoEstorno->id;
            $operacao->save();

            $ms = new MailService();
            $destinatario = $operacao->user->email;
            $ms->saqueEstornado($destinatario, $operacao);

            // Registrando o estorno no caixa
            $caixa = new Caixa();
            $caixa->descricao = 'Estorno saque #'.$saldoRaiz->id.' do usuário '.$operacaoEstorno->user->login;
            $caixa->valor = $operacaoEstorno->valor;
            $caixa->user_id = $operacaoEstorno->user_id;
            $caixa->tipo = 3;
            $caixa->save();
        } else {
            $operacao->status = 3;
            $operacao->save();
        }
    }

    public function saqueSaldoRendimento($saldoRendimentoId, $valor)
    {
        $saldoRendimento = SaldoRendimento::find($saldoRendimentoId);
        $this->addSaldoGeral($valor, 1, 2);
        $saldoRendimento->valor = $saldoRendimento->valor - $valor;
        if($saldoRendimento->contador <= 20){
            $saldoRendimento->contador = 0;
        }

        $saldoRendimento->update();
    }

    public function estornoSaldoRendimento($operacao, $registrar=false, $observacoes=null)
    {
        $saldoRendimento = SaldoRendimento::find($operacao->saldo_id);
        // valor, tipo, saldo
        $this->addSaldoGeral($saldoRendimento->valor, 0, 1);
        $saldoRendimento->valor = $saldoRendimento->valor + $operacao->valor;
        $saldoRendimento->update();

        if($registrar) {
            // Registrando o estorno em operações
            $operacaoEstorno = new Operacao();
            $operacaoEstorno->user_id = $saldoRendimento->user_id;
            $operacaoEstorno->tipo = 6;
            $operacaoEstorno->status = 1;
            $operacaoEstorno->saldo = 2;
            $operacaoEstorno->saldo_id = $saldoRendimento->id;
            $operacaoEstorno->valor = $operacao->valor;
            $operacaoEstorno->descricao = 'Estorno de saque raiz';
            $operacaoEstorno->produto = $saldoRendimento->produto_id;
            $operacaoEstorno->meio_saque = $operacao->meio_saque;
            $operacaoEstorno->observacoes = $observacoes;
            $operacaoEstorno->save();

            $operacao->estornado  = true;
            $operacao->observacoes = $observacoes;
            $operacao->operacao_estorno_id  = $operacaoEstorno->id;
            $operacao->save();

            $ms = new MailService();
            $destinatario = $operacao->user->email;
            $ms->saqueEstornado($destinatario, $operacao);

            // Registrando o estorno no caixa
            $caixa = new Caixa();
            $caixa->descricao = 'Estorno saque #'.$saldoRendimento->id.' do usuário '.$operacaoEstorno->user->login;
            $caixa->valor = $operacaoEstorno->valor;
            $caixa->user_id = $operacaoEstorno->user_id;
            $caixa->tipo = 3;
            $caixa->save();
        } else {
            $operacao->status = 3;
            $operacao->save();
        }
    }

    public function saqueSaldoIndicacao($saldoIndicacaoId, $valor)
    {
        $saldoIndicacao = SaldoIndicacao::find($saldoIndicacaoId);
        $saldoIndicacao->valor = $saldoIndicacao->valor - $valor;
        $saldoIndicacao->update();
        $this->addSaldoGeral($valor, 1, 3);
    }

    public function estornoSaldoIndicacao($operacao, $registrar=false, $observacoes=null)
    {
        $saldoIndicacao = SaldoIndicacao::find($operacao->saldo_id);
        $saldoIndicacao->valor = $saldoIndicacao->valor + $operacao->valor;
        $saldoIndicacao->update();
        $this->addSaldoGeral($operacao->valor, 0, 3);

        if($registrar) {
            // Registrando o estorno em operações
            $operacaoEstorno = new Operacao();
            $operacaoEstorno->user_id = $saldoIndicacao->user_id;
            $operacaoEstorno->tipo = 6;
            $operacaoEstorno->status = 1;
            $operacaoEstorno->saldo = 1;
            $operacaoEstorno->saldo_id = $saldoIndicacao->id;
            $operacaoEstorno->valor = $operacao->valor;
            $operacaoEstorno->descricao = 'Estorno de saque raiz';
            $operacaoEstorno->meio_saque = $operacao->meio_saque;
            $operacaoEstorno->observacoes = $observacoes;
            $operacaoEstorno->save();

            $operacao->estornado  = true;
            $operacao->observacoes = $observacoes;
            $operacao->operacao_estorno_id  = $operacaoEstorno->id;
            $operacao->save();

            $ms = new MailService();
            $destinatario = $operacao->user->email;
            $ms->saqueEstornado($destinatario, $operacao);

            // Registrando o estorno no caixa
            $caixa = new Caixa();
            $caixa->descricao = 'Estorno saque #'.$saldoIndicacao->id.' do usuário '.$operacaoEstorno->user->login;
            $caixa->valor = $operacao->valor;
            $caixa->user_id = $operacao->user_id;
            $caixa->tipo = 3;
            $caixa->save();
        } else {
            $operacao->status = 3;
            $operacao->save();
        }
    }

    public function atualizaSaldoRendimento($saldoRendimentoId)
    {
        //dd($saldoRendimentoId);
        $porcentagemArray = [1, 1, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 4, 6, 10];
        $saldoRendimento = SaldoRendimento::find($saldoRendimentoId);
        if ($saldoRendimento->contador > 19) {

            $sRaiz = SaldoProduto::find($saldoRendimento->saldo_produto_id);
            $compra = $sRaiz->compra;
            $compra->status = 2;
            $compra->save();
        }
        if ($saldoRendimento->contador > 19) {
            $msg = 'prazo de rendimento expirado';
            return $msg;
        }

        $saldoRaiz = SaldoProduto::find($saldoRendimento->saldo_produto_id);
        $porcentagem = $porcentagemArray[$saldoRendimento->contador];
        $porcentagemMsg = 0;
        for ($i = 0; $i <= $saldoRendimento->contador; $i++) {
            $porcentagemMsg += $porcentagemArray[$i];
        }
        $rendimento = (($porcentagem / 100) * $saldoRaiz->valor);
        $saldoRendimento->valor += $rendimento;
        $saldoRendimento->contador += 1;
        $operacao = new Operacao();
        $operacao->user_id = $saldoRaiz->user_id;
        $operacao->tipo = 3;
        $operacao->status = 1;
        $operacao->saldo = 2;
        $operacao->saldo_id = $saldoRendimento->id;
        $operacao->valor = $rendimento;
        $operacao->descricao = 'Bonificação diária referente a plano R$' . $saldoRaiz->produto->valor . ' (' . $porcentagemMsg . '%)';
        $operacao->save();
        $saldoRendimento->update();
        $this->addSaldoGeral($rendimento, 0, 2);
    }

    public function corrigeAtualizaSaldoRendimento($saldoRendimentoId,$data)
    {
        //dd($saldoRendimentoId);
        $porcentagemArray = [1, 1, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 4, 6, 10];
        $saldoRendimento = SaldoRendimento::find($saldoRendimentoId);
        if ($saldoRendimento->contador > 19) {

            $sRaiz = SaldoProduto::find($saldoRendimento->saldo_produto_id);
            $compra = $sRaiz->compra;
            $compra->status = 2;
            $compra->save();
        }
        if ($saldoRendimento->contador > 19) {
            $msg = 'prazo de rendimento expirado';
            return $msg;
        }

        $saldoRaiz = SaldoProduto::find($saldoRendimento->saldo_produto_id);
        $porcentagem = $porcentagemArray[$saldoRendimento->contador];
        $porcentagemMsg = 0;
        for ($i = 0; $i <= $saldoRendimento->contador; $i++) {
            $porcentagemMsg += $porcentagemArray[$i];
        }
        $rendimento = (($porcentagem / 100) * $saldoRaiz->valor);
        $saldoRendimento->valor += $rendimento;
        $saldoRendimento->contador += 1;
        $operacao = new Operacao();
        $operacao->user_id = $saldoRaiz->user_id;
        $operacao->tipo = 3;
        $operacao->status = 1;
        $operacao->saldo = 2;
        $operacao->saldo_id = $saldoRendimento->id;
        $operacao->valor = $rendimento;
        $operacao->created_at = $data;
        $operacao->descricao = 'Bonificação diária referente a plano R$' . $saldoRaiz->produto->valor . ' (' . $porcentagemMsg . '%)';
        $operacao->save();
        $saldoRendimento->update();
        $this->addSaldoGeral($rendimento, 0, 2);
    }

    public function atualizaSaldoRendimentoLegado($saldoRendimentoId)
    {
        //dd($saldoRendimentoId);
        $porcentagemArray = [1, 1, 1, 1, 2, 1, 2, 1, 2, 3, 2, 2, 2, 2, 2, 2, 2, 5, 6, 10];
        $saldoRendimento = SaldoRendimento::find($saldoRendimentoId);
        if ($saldoRendimento->contador > 19) {

            $sRaiz = SaldoProduto::find($saldoRendimento->saldo_produto_id);
            $compra = $sRaiz->compra;
            $compra->status = 2;
            $compra->save();
        }
        if ($saldoRendimento->contador > 19) {
            $msg = 'prazo de rendimento expirado';
            return $msg;
        }

        $saldoRaiz = SaldoProduto::find($saldoRendimento->saldo_produto_id);
        $porcentagem = $porcentagemArray[$saldoRendimento->contador];
        $porcentagemMsg = 0;
        for ($i = 0; $i <= $saldoRendimento->contador; $i++) {
            $porcentagemMsg += $porcentagemArray[$i];
        }
        $rendimento = (($porcentagem / 100) * $saldoRaiz->valor);
        $saldoRendimento->valor += $rendimento;
        $saldoRendimento->contador += 1;
        $operacao = new Operacao();
        $operacao->user_id = $saldoRaiz->user_id;
        $operacao->tipo = 3;
        $operacao->status = 1;
        $operacao->saldo = 2;
        $operacao->saldo_id = $saldoRendimento->id;
        $operacao->valor = $rendimento;
        $operacao->descricao = 'Bonificação diária referente a plano R$' . $saldoRaiz->produto->valor . ' (' . $porcentagemMsg . '%)';
      //  $operacao->created_at = Carbon::parse('');
        $operacao->save();
        $saldoRendimento->update();
        $this->addSaldoGeral($rendimento, 0, 2);
    }

    public function corrigeAtualizaSaldoRendimentoLegado($saldoRendimentoId,$data)
    {
        //dd($saldoRendimentoId);
        $porcentagemArray = [1, 1, 1, 1, 2, 1, 2, 1, 2, 3, 2, 2, 2, 2, 2, 2, 2, 5, 6, 10];
        $saldoRendimento = SaldoRendimento::find($saldoRendimentoId);
        if ($saldoRendimento->contador > 19) {

            $sRaiz = SaldoProduto::find($saldoRendimento->saldo_produto_id);
            $compra = $sRaiz->compra;
            $compra->status = 2;
            $compra->save();
        }
        if ($saldoRendimento->contador > 19) {
            $msg = 'prazo de rendimento expirado';
            return $msg;
        }

        $saldoRaiz = SaldoProduto::find($saldoRendimento->saldo_produto_id);
        $porcentagem = $porcentagemArray[$saldoRendimento->contador];
        $porcentagemMsg = 0;
        for ($i = 0; $i <= $saldoRendimento->contador; $i++) {
            $porcentagemMsg += $porcentagemArray[$i];
        }
        $rendimento = (($porcentagem / 100) * $saldoRaiz->valor);
        $saldoRendimento->valor += $rendimento;
        $saldoRendimento->contador += 1;
        $operacao = new Operacao();
        $operacao->user_id = $saldoRaiz->user_id;
        $operacao->tipo = 3;
        $operacao->status = 1;
        $operacao->saldo = 2;
        $operacao->saldo_id = $saldoRendimento->id;
        $operacao->valor = $rendimento;
        $operacao->created_at = $data;
        $operacao->descricao = 'Bonificação diária referente a plano R$' . $saldoRaiz->produto->valor . ' (' . $porcentagemMsg . '%)';
        //  $operacao->created_at = Carbon::parse('');
        $operacao->save();
        $saldoRendimento->update();
        $this->addSaldoGeral($rendimento, 0, 2);
    }

    public function atualizaSaldoIndicacao($user, $credito, $tipo, $nivel, $porcentagem, $valorFatura, $afiliado)
    {
        //dd($valorFatura);
        switch ($tipo) {
            case 4:
                $descricao = 'Bonificação referente a nova adesão (' . $nivel . ')';
                break;
            case 5:
                $descricao = 'Bonificação referente a renovação (' . $nivel . ')';
                break;
        }
        $saldoIndicacao = SaldoIndicacao::where('user_id', $user)->first();
        if (!isset($saldoIndicacao)) {
            $saldoIndicacao = new SaldoIndicacao();
            $saldoIndicacao->user_id = $user;
            $saldoIndicacao->valor = 0.00;
            $saldoIndicacao->save();
        }
        $saldoIndicacao->valor = $saldoIndicacao->valor += $credito;
        $operacao = new Operacao();
        $operacao->user_id = $user;
        $operacao->tipo = $tipo;
        $operacao->status = 1;
        $operacao->saldo = 3;
        $operacao->saldo_id = $saldoIndicacao->id;
        $operacao->valor = $credito;
        $operacao->descricao = 'Bonificação de ' . $porcentagem . '% sobre a ativação de uma fatura no valor de R$' . $valorFatura . ' do login ' . $afiliado . ' do seu ' . $nivel . ' nível.';
        $operacao->save();
        $saldoIndicacao->update();

        $this->addSaldoGeral($credito, 0, 3);

    }


    public function verifyNivel($nivel, $user)
    {

        switch ($nivel) {
            case 0:
                $escolha = $user->patrocinador;
                $direto = true;
                $frase = 'Direto';

                break;
            case 1:
                $escolha = $user->segundo();
                $direto = false;
                $frase = 'Segundo';

                break;
            case 2:
                $escolha = $user->terceiro();
                $direto = false;
                $frase = 'Terceiro';
                break;
            case 3:
                $escolha = $user->quarto();
                $direto = false;
                $frase = 'Quarto';
                break;

            case 4:
                $escolha = $user->quinto();
                $direto = false;
                $frase = 'Quinto';
                break;
        }

//dd($user);
        return $escolha;

    }

    public function adicionaSaldoIndicacao($user, $valor, $justificativa)
    {
        $saldoIndicacao = SaldoIndicacao::where('user_id', $user->id)->first();
        $saldoIndicacao->valor = $saldoIndicacao->valor + $valor;
        $saldoIndicacao->save();

        $operacao = new Operacao();
        $operacao->user_id = $saldoIndicacao->user_id;
        $operacao->tipo = 4;
        $operacao->status = 1;
        $operacao->saldo = 3;
        $operacao->saldo_id = $saldoIndicacao->id;
        $operacao->valor = $valor;
        $operacao->descricao = "Adição saldo de rede referente a $justificativa";
        $operacao->save();
        $notificacao = new Notificacao();
        $notificacao->user_id = $saldoIndicacao->user_id;
        $notificacao->mensagem = 'Pronto, saldo de rede adicionado! :)';
        $notificacao->save();

        $caixa = new Caixa();
        $caixa->descricao = 'Adição saldo de rede #' . $operacao->id . ' do usuário ' . $operacao->user->login . ' referente a ' . $justificativa;
        $caixa->valor = $operacao->valor;
        $caixa->user_id = $operacao->user_id;
        $caixa->tipo = 1;
        $caixa->save();

        $this->addSaldoGeral($operacao->valor, 0, 3);

        return $operacao;
    }

    public function removeSaldoIndicacao($user, $valor, $justificativa)
    {
        $saldoIndicacao = SaldoIndicacao::where('user_id', $user->id)->first();
        $saldoIndicacao->valor = $saldoIndicacao->valor - $valor;
        $saldoIndicacao->save();

        $operacao = new Operacao();
        $operacao->user_id = $saldoIndicacao->user_id;
        $operacao->tipo = 6;
        $operacao->status = 1;
        $operacao->saldo = 3;
        $operacao->saldo_id = $saldoIndicacao->id;
        $operacao->valor = $valor;
        $operacao->descricao = "Subtração saldo de rede referente a $justificativa";
        $operacao->save();
        $notificacao = new Notificacao();
        $notificacao->user_id = $saldoIndicacao->user_id;
        $notificacao->mensagem = 'Infelizmente retiramos um valor do saldo de rede :(';
        $notificacao->save();

        $caixa = new Caixa();
        $caixa->descricao = 'Subtração saldo de rede #' . $operacao->id . ' do usuário ' . $operacao->user->login . ' referente a ' . $justificativa;
        $caixa->valor = $operacao->valor;
        $caixa->user_id = $operacao->user_id;
        $caixa->tipo = 1;
        $caixa->save();

        $this->addSaldoGeral($operacao->valor, 1, 3);

        return $operacao;
    }

    public function iniciaSaldoIndicacao($userId)
    {
        $saldoIndicacao = new SaldoIndicacao();
        $saldoIndicacao->user_id = $userId;
        $saldoIndicacao->valor = 0.00;
        $saldoIndicacao->save();
    }

    public function addSaldoGeral($valor, $tipo, $saldo)
    {
        $saldoGeral = new SaldoGeral();
        $saldoGeral->valor = $valor;
        $saldoGeral->tipo = $tipo;
        $saldoGeral->saldo = $saldo;
        $saldoGeral->save();
    }

}
