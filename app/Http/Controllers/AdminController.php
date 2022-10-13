<?php

namespace App\Http\Controllers;

use App\Models\Assinatura;
use App\Models\Extrato;
use App\Models\Plano;
use App\Models\User;
use App\Models\Valorindicacao;
use App\Providers\RouteServiceProvider;
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


    public function backoffice(User $user)
    {
        Auth::guard('web')->login($user);
        return redirect(RouteServiceProvider::HOME);
    }


    public function addSaldo($id, Request $request)
    {
        $request->validate([
            'valor' => 'required',
            'observacoes' => 'required'
        ]);

        $user = User::findOrFail($id);


        //$valores =
          //  [
            //    'valor' => $request->valor,

              //  'user_id' => $user->id
           // ];


        $dados = [
            'tipo' => 1,
            'descricao' => $request->observacoes,
            'valor' => $request->valor,
            'user_id' => $user->id
        ];
        Valorindicacao::create($dados);

        return redirect()->back()->with('success', 'Saldo adicionado com sucesso.');
    }

    public function removeSaldo($id, Request $request)
    {
        $request->validate([
            'valor' => 'required',
            'observacoes' => 'required'
        ]);

        $user = User::findOrFail($id);


        $dados = [
            'tipo' => 1,
            'descricao' => $request->observacoes,
            'valor' => -$request->valor,
            'user_id' => $user->id
        ];
        Valorindicacao::create($dados);

       // $op = new OperationService();
       // $op->removeSaldoIndicacao($user, $request->valor, $request->observacoes);

        return redirect()->back()->with('success', 'Saldo removido com sucesso.');
    }
}
