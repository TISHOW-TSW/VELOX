<?php
if (!function_exists('formatCurrency')) {
    function formatCurrency($valor)
    {
        $valor = 'R$ ' . number_format($valor, 2, ',', '.');

        return $valor;
    }
}

if (!function_exists('getPrazoFinal')) {
    function getPrazoFinal($saque, $formatado = false)
    {



        switch ($saque->saldo) {
            case 1:
                $prazoFinal = strtotime(date('Y-m-d', strtotime($saque->created_at . ' + 3 days')));
                break;
            case 2:
                $prazoFinal = strtotime(date('Y-m-d', strtotime($saque->created_at . ' + 1 days')));
                break;
            case 3:
                $prazoFinal = strtotime(date('Y-m-d', strtotime($saque->created_at . ' + 2 days')));
                break;
        }

        if ($formatado)
            return date('d-m-Y h:i', $prazoFinal);


        return $prazoFinal;
    }
}

if (!function_exists('getTempoRestanteSaque')) {
    function getTempoRestanteSaque($saque)
    {
        $prazoFinal = getPrazoFinal($saque);
        $hoje = strtotime(date('Y-m-d h:i:s'));
        $prazoRestante = abs($prazoFinal - $hoje) / 3600;

        //return number_format($prazoRestante, 0);
        return $prazoFinal;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($data, $exercicio = false)
    {
        $data = date_create($data);
        if ($exercicio)
            return date_format($data, "Y");

        return date_format($data, "d/m/Y");
    }
}
if (!function_exists('stringToArray')) {
    function stringToArray($data)
    {

        return ((array)json_decode($data));

    }
}
if (!function_exists('assetDigital')) {
    function assetDigital($filename)
    {

        return 'https://nftcash.sfo3.digitaloceanspaces.com/'.$filename;

    }
}

use App\Models\UserAdmin;

if(!function_exists('isAdminActive'))
{
    function isAdminActive($login) : bool
    {
        $admin = UserAdmin::whereLogin($login)->isActive()->exists();

        return $admin ? true : false;
    }
}

