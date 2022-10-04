<?php

namespace App\Http\Controllers;

use App\Models\Assinatura;
use App\Models\Extrato;
use App\Models\Plano;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogActivity;
use App\Models\Compra;

class AdminController extends Controller
{
    //

    public function users()
    {
        $users = User::all();
        LogActivity::addToLog('Acessou aba Todos Usuarios');
        return view('painel.usuario.index', compact('users'));
    }

    public function ativos()
    {

        $usuarios = User::whereHas('compras')->get();
        $controle = [];
        foreach ($usuarios as $user) {
            foreach ($user->compras as $assinatura) {
                if ($assinatura->ativo == 1) {
                    $controle[] = $user->id;
                }
            }
        }
        //dd($controle);
        LogActivity::addToLog('Acessou aba Usuarios Ativos');
        $users = User::whereIn('id', $controle)->get();
        // dd($users);

        return view('painel.usuario.ativos', compact('users'));
    }

    public function pendentes()
    {
        // $users = User::with(['assinaturas', function ($q) {
        //  $q->whereNotNull('inicio');
        //}])->get();
        $users = User::doesnthave('compras')->get();
        //dd($users);
        LogActivity::addToLog('Acessou aba Usuarios Pendentes');
        return view('painel.usuario.pendentes', compact('users'));
    }


    public function faturas($id)
    {
       // dd($id);

        $busca = Compra::where('user_id', Auth::user()->id)->where('plano_id', $id)->where('ativo', 0)->first();
        if (isset($busca)) {
            return redirect(url('customer/invoices'));
        }
        $plano = Plano::find($id);

        //dd(count(Auth::user()->assinaturas));





        //dd($plano);

        $hoje = Carbon::now();


        $dados = [

            'plano_id' => $id,
            'user_id' => Auth::user()->id,

        ];

        $salva = Compra::create($dados);


        //dd($controle);

        //Assinatura::create($grava);

        return redirect(url('customer/invoices'));
    }
}
