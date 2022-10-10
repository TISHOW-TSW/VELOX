<?php

namespace App\Services;

use App\Models\Compra;
use App\Models\SaldoRaiz;
use App\Models\SaldoRendimento;

class SaldoService
{
    public function createSaldoRaiz(Compra $compra)
    {
        $saldoRaiz = SaldoRaiz::create([
            'valor' => $compra->plano->valor,
            'user_id' => $compra->user_id,
            'compra_id' => $compra->id
        ]);
        $this->createSaldoRendimento($saldoRaiz);
    }

    public function createSaldoRendimento(SaldoRaiz $saldoRaiz)
    {
        SaldoRendimento::create([
            'valor' => 0.00,
            'saque_rendimento' => 0.00,
            'saldo_raiz_id' => $saldoRaiz->id,
        ]);
    }

    public function saqueRaiz(SaldoRaiz $saldoRaiz)
    {
        $saldoRaiz->valor = 0.00;
        $saldoRaiz->update();
    }

    public function saqueRendimento(SaldoRendimento $saldoRendimento)
    {
        $saldoRendimento->saque_rendimento = $saldoRendimento->saque_rendimento + $saldoRendimento->valor;
        $saldoRendimento->valor = 0.00;
        $saldoRendimento->update();
    }

    public function rendimento(SaldoRaiz $saldoRaiz)
    {
        $valor = ($saldoRaiz->valor * 10)/100;
        $saldoRaiz->saldoRendimento->valor += $valor;
        $saldoRaiz->saldoRendimento->update();
    }

    public function valorCancelamento(SaldoRaiz $saldoRaiz)
    {
        $valor = (($saldoRaiz->valor * 77)/100) - $saldoRaiz->saldoRendimento->saque_rendimento;
        return $valor;
    }

    public function cancelamento(SaldoRaiz $saldoRaiz)
    {
        $valor = (($saldoRaiz->valor * 77)/100) - $saldoRaiz->saldoRendimento->saque_rendimento;
        $saldoRaiz->valor = 0.00;
        $saldoRaiz->update();
        $saldoRaiz->saldoRendimento->valor = 0.00;
        $saldoRaiz->saldoRendimento->update();
        $valor = (($saldoRaiz->valor * 77)/100) - $saldoRaiz->saldoRendimento->saque_rendimento;
    }


}
