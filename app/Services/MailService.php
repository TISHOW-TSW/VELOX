<?php
namespace App\Services;
use App\Mail\DemoMail;
use App\Mail\SaquePendenteMail;
use App\Mail\SaquePagoMail;
use App\Mail\SaqueEstornadoMail;
use Mail;

class MailService
{
    public function mailDemo($destinatario)
    {
        $mailData = [
            'title' => 'Mail Demo Title - HU3HU3HU3H3UH3',
            'body' => 'This is for testing email using smtp. HAHhahahASHEH'
        ];

        // gustavopantoja.ap@gmail.com
         
        Mail::to($destinatario)->send(new DemoMail($mailData));
           
        dd("Email is sent successfully.");
    }

    public function saquePendente($destinatario, $operacao)
    {
        $operacao->setAppends(['meio_formated', 'saldo_formated']);

        // dd([
        //     'operacao' => $operacao->toArray(),
        //     'usuario' => $operacao->user->toArray()
        // ]);

        switch($operacao->meio_saque) {
            case 1:
                $dados['BankOn'] = $operacao->user->bankon->cod_bankon;
                break;
            case 2:
                $dados['PIX'] = $operacao->user->pix->chave;
                break;
            case 3:
                // $dados['Instituição'] = BancoID($conta->banco);
                // $dados['Agência'] = $conta->agencia;
                // $dados['Conta'] = $conta->conta;
                // $dados['Tipo de Conta'] = $conta->tipo_formatted;
                // $dados['Documento'] = $conta->titular_documento;
                // $dados['Titular'] = $conta->titular_name;
                break;
        }
  
        $mailData = [
            'dados_pagamento' => $dados,
            'dados_saque' => $operacao->toArray()
        ];

        // dd($mailData);

        Mail::to($destinatario)->send(new SaquePendenteMail($mailData));
           
        return true;
    }

    public function saquePago($destinatario, $operacao)
    {
        $operacao->setAppends(['meio_formated', 'saldo_formated']);
  
        $mailData = [
            'dados_saque' => $operacao->toArray()
        ];

        // dd($mailData);

        Mail::to($destinatario)->send(new SaquePagoMail($mailData));
           
        return true;
    }

    public function saqueEstornado($destinatario, $operacao)
    {
        $operacao->setAppends(['meio_formated', 'saldo_formated']);
  
        $mailData = [
            'dados_saque' => $operacao->toArray()
        ];

        Mail::to($destinatario)->send(new SaqueEstornadoMail($mailData));
           
        return true;
    }
}