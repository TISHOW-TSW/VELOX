<?php

namespace App\Services;

use App\Models\Compra;
use App\Models\User;
use Carbon\Carbon;
use CodePhix\Asaas\Asaas;

class PaymentServices
{

    protected $asaas;

    public function __construct()
    {
        $asaas = new Asaas('$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwMDI4MzY6OiRhYWNoXzI2ZWQ3NTZlLTk1NTktNGMwMS05ZDZlLTI1NGZhYjdlYjY4Mg==', 'homologacao');
        $this->asaas = $asaas;

    }

    public function verifyCustumer(User $user)
    {
        //dd($user);
        $cliente = $this->asaas->Cliente()->getByCpf($user->cpf);


        if (!$cliente->data) {
            $cliente = $this->createCustumer($user);

            return $cliente;
        }

        //dd($cliente);
        return $cliente->data[0];


    }

    public function createCustumer(User $user)
    {
        $dados = array(
            'name' => $user->name,
            'cpfCnpj' => $user->cpf,
            'email' => $user->email,
        );


        // dd($dados);
        $cliente = $this->asaas->Cliente()->create($dados);
        return $cliente;
    }

    public function createPaymentBoleto($id_custumer, Compra $compra)
    {
        //dd($compra->produto);
        $dadosCobranca = array(
            'customer' => $id_custumer,
            'billingType' => 'undefined',
            'value' => $compra->plano->valor,
            'dueDate' => Carbon::now()->format('Y-m-d'),
            'description' => $compra->plano->name,
            'externalReference' => '',
            'installmentCount' => '',
            'installmentValue' => '',
            'discount' => '',
            'interest' => '',
            'fine' => '',
        );




        $cobranca = $this->asaas->Cobranca()->create($dadosCobranca);
//dd($cobranca);

        return $cobranca;


    }

    public function createPaymentCard($id_custumer, Compra $compra)
    {
        //dd($compra->produto);
        $dadosCobranca = array(
            'customer' => $id_custumer,
            'billingType' => 'CREDIT_CARD',
            'value' => $compra->plano->valor,
            'dueDate' => Carbon::now()->format('Y-m-d'),
            'description' => $compra->produto->name,
            'externalReference' => '',
            'installmentCount' => '',
            'installmentValue' => '',
            'discount' => '',
            'interest' => '',
            'fine' => '',
        );




        $cobranca = $this->asaas->Cobranca()->create($dadosCobranca);


        return $cobranca;

    }

}
