<?php

use App\Models\SaldoRendimento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Models\Compra;
use App\Helpers\LogActivity as HelpersLogActivity;
use App\Models\Saqueindica;
use App\Models\Saquerendimento;
use App\Models\Caixa;
use Carbon\Carbon;
use App\Models\Valorredimento;
use App\Models\User;
use App\Models\Plano;
use App\Models\Relatorio;

Route::name('admin.')->prefix('admin')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        //login route
        Route::get('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');
        Route::post('/login', [App\Http\Controllers\LoginController::class, 'processLogin']);
    });

    Route::get('caixa', function () {
        HelpersLogActivity::addToLog('Acessou aba de Caixa');
        $caixas = Caixa::orderBy("id", 'desc')->get();
        $entrada = Caixa::where("tipo", 1)->sum('valor');
        $saida = Caixa::where("tipo", 0)->sum('valor');
        // dd($entrada);
        return view('admin.caixa', compact('caixas', 'entrada', 'saida'));
    });

    Route::get('comprovantes', function () {
        $buscas = Compra::whereNotNull('img')->get();

        return view('admin.comprovantes', compact('buscas'));
    });

    Route::get('faturas', function () {

        $compras = Compra::all();
        HelpersLogActivity::addToLog('Acessou aba Faturas');
        return view('admin.faturas', compact('compras'));
    });

    Route::get('chat', function () {
        $chats = \App\Models\Chat::orderByDesc('created_at')->orderBy('aberto', 'desc')->get();

        // dd($aberto);
        return view('chat.index', compact('chats'));
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('user/edit/{user}/sponsor', [\App\Http\Controllers\AdminController::class, 'editSponsor']);
        Route::get('log', function () {
            $logs = \App\Models\LogActivity::orderByDesc('id')->get();

            return view('admin.log', compact('logs'));
        });

        Route::get('dashboard', function () {

            $agora = \Carbon\Carbon::now();

            $entrada = \App\Models\Caixa::where("tipo", 1)->sum('valor');
            $faturaspendentes = \App\Models\Compra::where("ativo", 0)->with('plano')->whereMonth('created_at', $agora)->count();
            $faturaspagas = \App\Models\Compra::where("ativo", 1)->with('plano')->whereMonth('created_at', $agora)->count();
            $valorespendentes = \App\Models\Compra::where("ativo", 0)->get();


            $users = \App\Models\User::withCount('indicados')->orderByDesc('indicados_count')->limit(30)->get();
            $estados = \App\Models\Estado::withCount('enderecos')->orderByDesc('enderecos_count')->get();

            $totalpendente = 0;
            foreach ($valorespendentes as $valorespendente) {
                $totalpendente += $valorespendente->plano->valor;
            }


            $usuarios = \App\Models\User::whereHas('compras')->get();
            ///dd($usuarios);
            $nousers = \App\Models\User::doesnthave('compras')->get()->pluck('id')->toArray();

            $controle = [];

            $semassinatura = [];

            foreach ($usuarios as $user) {
                if ($user->compras[0]->ativo == 1) {

                    $controle[] = $user->id;
                } else {
                    $semassinatura[] = $user->id;
                }
            }
            foreach ($nousers as $nouser) {
                $semassinatura[] = $nouser;
            }

            //dd($semassinatura);

            $ativos = \App\Models\User::whereIn('id', $controle)->get();
            $inativos = \App\Models\User::whereIn('id', $semassinatura)->get();

            //dd($totalpendente);
            $saida = \App\Models\Caixa::where("tipo", 0)->whereMonth('created_at', $agora)->sum('valor');
            $saques = \App\Models\Saqueindica::where("status", 1)->whereMonth('created_at', $agora)->sum('valor') + \App\Models\Saquerendimento::where("status", 1)->whereMonth('created_at', $agora)->sum('valor');
            // dd($faturaspendentes);


            return view('admin.index', compact('entrada', 'saida', 'saques', 'faturaspendentes', 'faturaspagas', 'totalpendente', 'users', 'ativos', 'inativos', 'estados'));
        });

        Route::get('usuarios', [AdminController::class, 'users']);
        Route::get('usuarios/ativos', [AdminController::class, 'ativos']);
        Route::get('usuarios/pendentes', [AdminController::class, 'pendentes']);

        Route::get('user/backoffice/{user}', [\App\Http\Controllers\AdminController::class, 'backoffice']);

        Route::get('saque', function () {
            $tipo = 'de Rendimento';
            $saques = Saquerendimento::all();
            $saqueindicaos = Saqueindica::all();
            $saquesRaiz = \App\Models\Saqueraiz::all();
            $saquesCancelamento = \App\Models\Saquecancelamento::all();

            return view('admin.saque.todos', compact('saques', 'tipo', 'saqueindicaos', 'saquesRaiz', 'saquesCancelamento'));
        });
        Route::get('saque/ativos', function () {
            $tipo = 'de Rendimentos';
            //  $saques = \App\Models\Saque::where('status', 1)->get();
            $saques = \App\Models\Saquerendimento::where('status', 1)->get();
            $saqueindicaos = Saqueindica::where('status', 1)->get();
            $saquesRaiz = \App\Models\Saqueraiz::where('status', 1)->get();
            $saquesCancelamento = \App\Models\Saquecancelamento::where('status', 1)->get();

            return view('admin.saque.todos', compact('saques', 'tipo', 'saqueindicaos', 'saquesRaiz', 'saquesCancelamento'));
        });
        Route::get('saque/pendentes', function () {
            $tipo = 'de Rendimentos';
            $saques = \App\Models\Saquerendimento::where('status', 0)->get();
            $saqueindicaos = Saqueindica::where('status', 0)->get();
            $saquesRaiz = \App\Models\Saqueraiz::where('status', 0)->get();
            $saquesCancelamento = \App\Models\Saquecancelamento::where('status', 0)->get();

            return view('admin.saque.todos', compact('saques', 'tipo', 'saqueindicaos', 'saquesRaiz', 'saquesCancelamento'));
        });
        Route::get('saque/pendentesgrupo', function () {
            $tipo = 'de Rendimentos';
            $saques = \App\Models\Saquerendimento::where('status', 0)->whereHas('user', function ($query) {
                $query->where('grupo', 1);
            })->get();
            $saqueindicaos = Saqueindica::where('status', 0)->whereHas('user', function ($query) {
                $query->where('grupo', 1);
            })->get();
            $saquesRaiz = \App\Models\Saqueraiz::where('status', 0)->whereHas('user', function ($query) {
                $query->where('grupo', 1);
            })->get();
            $saquesCancelamento = \App\Models\Saquecancelamento::where('status', 0)->whereHas('user', function ($query) {
                $query->where('grupo', 1);
            })->get();

            return view('admin.saque.todos', compact('saques', 'tipo', 'saqueindicaos', 'saquesRaiz', 'saquesCancelamento'));
        });
        Route::get('saque/estornados', function () {
            $tipo = 'ESTORNADO';
            $saques = \App\Models\Saque::where('status', 2)->get();


            return view('admin.saque.todos', compact('saques', 'tipo'));
        });


        Route::get('user/edit/{id}', function ($id) {
            $user = User::find($id);
            $usuarios = User::all();
            HelpersLogActivity::addToLog('Acessou aba de Edição do usuario ' . $user->name);
            return view('painel.usuario.edit', compact('user', 'usuarios'));
        });

        Route::get('user/visualizar/{id}', function ($id) {

            $user = User::find($id);
            $buscas = \App\Models\Valorindicacao::where('user_id', $user->id)->orderByDesc('created_at')->get();
            return view('painel.usuario.show', compact('user', 'buscas'));
        });

        Route::get('deleterede/{id}', function ($id) {
            $valorindica = \App\Models\Valorindicacao::find($id);
            $valorindica->delete();

            return redirect()->back()->with('success', 'Bonus Deletado com sucesso');
        });

        Route::post('consulta/faturas', function (Request $request) {
            //dd($request->all());

            $datas = explode(" - ", $request->data);
            //  dd($datas);

            //$inicio =  $datas[0];

            $inicio = Carbon::createFromFormat('d/m/Y', $datas[0])->format('Y-m-d');
            $final = Carbon::createFromFormat('d/m/Y', $datas[1])->format('Y-m-d');
            $inicio = Carbon::parse($inicio);
            $final = Carbon::parse($final)->addDay(1);
            //  $final =

            //  dd($inicio);

            if ($request->status == 1) {
                $buscas = Compra::where('ativo', 1)->whereBetween('created_at', [$inicio, $final])->get();
                // dd($buscas);
                HelpersLogActivity::addToLog('Pequisou faturas Pagas Periodo de ' . $inicio . ' à ' . $final);
                return view('admin.faturas', compact('buscas'));
            }
            if ($request->status == 0) {
                $buscas = Compra::where('ativo', 0)->whereBetween('created_at', [$inicio, $final])->get();
                //dd($buscas);
                HelpersLogActivity::addToLog('Pequisou faturas Pendente Periodo de ' . $inicio . ' à ' . $final);
                return view('admin.faturas', compact('buscas'));
            }
            if ($request->status == 2) {
                $buscas = Compra::whereBetween('created_at', [$inicio, $final])->get();
                // dd($buscas);
                HelpersLogActivity::addToLog('Pequisou faturas Geral Periodo de ' . $inicio . ' à ' . $final);
                return view('admin.faturas', compact('buscas'));
            }
        });
        Route::post('consulta/relatorio', function (Request $request) {
            //dd($request->all());

            $datas = explode(" - ", $request->data);
            //  dd($datas);

            //$inicio =  $datas[0];

            $inicio = Carbon::createFromFormat('d/m/Y', $datas[0])->format('Y-m-d');
            $final = Carbon::createFromFormat('d/m/Y', $datas[1])->format('Y-m-d');

            //  dd($inicio);


            $relatorios = Relatorio::whereBetween('data', [$inicio, $final])->get();
            // dd($buscas);
            HelpersLogActivity::addToLog('Pequisou faturas Pagas Periodo de ' . $inicio . ' à ' . $final);
            return view('admin.relatoriogeral', compact('relatorios'));
        });

        Route::post('user/edit', function (Request $request) {
            $request->validate([
                'name' => 'required',
                'email' => ['required'],
            ]);

            $user = User::find($request->id);
            $controle = '';

            //dd($request->all());
            if ($user->name != $request->name) {
                $controle .= 'Nome Alterado de ' . $user->name . ' para ' . $request->name . '<br>';
            }
            if ($user->email != $request->email) {
                $controle .= 'EMail Alterado de ' . $user->email . ' para ' . $request->email . '<br>';
            }
            if (empty($request->password)) {
                unset($request['password']);
            } else {
                $request['password'] = bcrypt($request->password);
            }
            $user->fill($request->all());
            $user->save();


            HelpersLogActivity::addToLog('Dados Alterados ' . $controle);
            return redirect()->back()->with('success', 'Usuario atualizado com sucesso');
        });
        Route::get('users', function () {

            $users = User::where("tipo", 1)->get();

            return view('admin.user.index', compact('users'));
        });
        Route::post('usuarios/edit', function (Request $request) {

            //    dd($request->all());

            $request->validate([
                'name' => 'required',
                'email' => ['required'],
                'tipo' => ['required'],

            ]);

            $user = User::find($request->id);
            $user->fill($request->all());
            $user->save();
        });
        Route::post('buscarcpf', function (Request $request) {
            $request->validate([

                'email' => ['required', 'email'],

            ]);
            //  $request['cpf'] = preg_replace("/[^0-9]/", "", $request->cpf);
            $user = User::where('email', $request->email)->first();

            if (empty($user)) {
                return redirect()->back();
            } else {
                \App\Models\UserAdmin::create(
                    ['name' => $user->name,
                        'login' => $user->login,
                        'email' => $user->email,
                        'password' => $user->password,
                    ]

                );
            }

            return redirect()->back();
        });


        Route::get('rendimento/visualizar/saque/{id}', function ($id) {
            $saque = \App\Models\Saquerendimento::find($id);
            $tipo = 0;
            $tiposaque = "RENDIMENTO";

            return view('admin.saque.visualizar', compact('saque', 'tipo', 'tiposaque'));
        });
        Route::get('raiz/visualizar/saque/{id}', function ($id) {
            $saque = \App\Models\Saqueraiz::find($id);
            $tipo = 2;
            $tiposaque = "RAIZ";

            //        dd($saque);

            return view('admin.saque.visualizar', compact('saque', 'tipo', 'tiposaque'));
        });

        Route::get('indica/visualizar/saque/{id}', function ($id) {
            $saque = Saqueindica::find($id);
            $tipo = 1;
            $tiposaque = "REDE";

            return view('admin.saque.visualizar', compact('saque', 'tipo', 'tiposaque'));
        });


        Route::get('indica/cancelar/saque/{id}', function ($id) {
            $valor = Saqueindica::find($id);


            $dados = [
                'tipo' => 1,
                'descricao' => 'Saque estornado do seu time',
                'valor' => $valor->valor,
                'user_id' => $valor->user_id
            ];
            \App\Models\Valorindicacao::create($dados);

            $valor->fill(['status' => 2]);
            $valor->save();

            return redirect()->back();
            // dd($valor->valor);
        });


        Route::get('rendimento/cancelar/saque/{id}', function ($id) {
            $saque = \App\Models\Saquerendimento::find($id);

            $dados = [
                'tipo' => 0,
                'descricao' => "cancel withdraw",
                'valor' => $saque->valor,
                'user_id' => Auth::user()->id
            ];


            Valorredimento::create($dados);

            Saquerendimento::destroy($id);

            return redirect()->back();

            // dd($dados);

            return view('admin.saque.cancelar', compact('saque'));
        });


        Route::get('rendimento/deletar/saque/{id}', function ($id) {
            $saque = \App\Models\Saquerendimento::find($id);






            Saquerendimento::destroy($id);

            return redirect()->back();

            // dd($dados);

            return view('admin.saque.cancelar', compact('saque'));
        });


        Route::get('delete/conta/{id}', function ($id) {


            Conta::destroy($id);

            return redirect()->back();
        });

        Route::get('usuarios/edit/{id}', function ($id) {
            $user = User::find($id);

            return view('admin.user.edit', compact('user'));
        });
        Route::get('relatorios', function () {
            $logs = HelpersLogActivity::logActivityLists();
            $agora = Carbon::now();
            $planos = Plano::all();
            $assinaturas = Assinatura::where("status", 1)->whereMonth('inicio', $agora)->get();
            HelpersLogActivity::addToLog('Acessou Relatorio');
            // dd($premium);
            $relatorios = Relatorio::all();
            return view('admin.relatoriogeral', compact('relatorios', 'assinaturas', 'planos', 'logs'));
        });
        Route::get('relatorioplanos', function () {
            $logs = HelpersLogActivity::logActivityLists();
            $agora = Carbon::now();
            $planos = Plano::all();
            $assinaturas = Compra::where("ativo", 1)->whereMonth('created_at', $agora)->get();
            HelpersLogActivity::addToLog('Acessou Relatorio');
            // dd($premium);
            $relatorios = Relatorio::all();
            return view('admin.relatorioplano', compact('relatorios', 'assinaturas', 'planos', 'logs'));
        });
        Route::get('logs', function () {
            $logs = HelpersLogActivity::logActivityLists();
            $agora = Carbon::now();
            $planos = Plano::all();
            $assinaturas = Assinatura::where("status", 1)->whereMonth('inicio', $agora)->get();
            HelpersLogActivity::addToLog('Acessou Relatorio');
            // dd($premium);
            $relatorios = Relatorio::all();
            return view('admin.relatorio', compact('relatorios', 'assinaturas', 'planos', 'logs'));
        });


        Route::get('todasfaturas', function () {
            $faturas = Compra::where('status', 1)->get();

            dd($faturas);
        });


        Route::post('/logout', function (Request $request) {
            Auth::guard('admin')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->action([
                LoginController::class,
                'login'
            ]);
        })->name('logout');

    });

    Route::get('zerarend', function () {
        $saldos = \App\Models\SaldoRendimento::all();
        foreach ($saldos as $saldo) {
            $saldo->update(['valor' => 0]);
        }
        $corridas = \App\Models\Batalha::all();
        //dd($corridas);
        foreach ($corridas as $corrida) {
            $corrida->delete();
        }
        $rendimentos = Valorredimento::all();
        foreach ($rendimentos as $rendimento) {
            $rendimento->delete();
        }


    });

    Route::post('user/{user}/add-saldo', [\App\Http\Controllers\AdminController::class, 'addSaldo']);
    Route::post('user/{user}/remove-saldo', [\App\Http\Controllers\AdminController::class, 'removeSaldo']);


    Route::get('buscacorrecao/{id}', function ($id, \App\Http\Controllers\AcaoController $acaoController) {

        $users = User::all();

        //$user = User::find($id);

        foreach ($users as $user) {

            foreach ($user->valorindicacos as $valorindicaco) {

                if ($valorindicaco->valor > 0) {
                    $valorindicaco->delete();
                };
            };

            /*        $buscas = User::where('quem', $user->link)->whereHas('compras', function ($query) {
                        $query->where('status', 1);
                    })->get();

                    foreach ($buscas as $busca) {
                       // $novos = $busca->compras->where('status', 1);
                        //fdd($novo);

                        foreach ($busca->compras as $novo) {

                            if ($novo->status ==1) {

                                for ($i = 1; $i >= 3; $i++) {

                                    $indicado = $acaoController->verifyNivel($i, $novo->user);
                                    if (!empty($indicado)) {
                                        echo $indicado->name . "<br>";

                                        $atualiza = $acaoController->attSaldoIndicaNovaAssinatura($novo, $i);
                                    }
                                }
                            }
                        }
                    }

                   */
            // dd($buscas);

        }


        $compras = Compra::where('status', 1)->get();

        //dd(count($compras));


        foreach ($compras as $novo) {

            for ($i = 1; $i <= 3; $i++) {

                $indicado = $acaoController->verifyNivel($i, $novo->user);
                if (!empty($indicado)) {
                    echo $indicado->name . "<br>";

                    $atualiza = $acaoController->attSaldoIndicaNovaAssinatura($novo, $i);
                }
            }

        }

        //   dd($user);
    });


    Route::resource('vantagem', \App\Http\Controllers\VantagemController::class)->middleware(['auth']);
    Route::resource('plano', \App\Http\Controllers\PlanoController::class)->middleware(['auth']);


    Route::get('getupindiviual/{compra}', function (Compra $compra) {
        //dd($compra);

        $busca = [
            'user_id' => $compra->user_id,
            'plano_id' => $compra->plano->id,
            'compra_id' => $compra->id,
        ];


        $op = \App\Models\Valorredimento::find(204);
        //dd($op);
        $compra->saldoRaiz->saldoRendimento->update(['valor' => 2]);
        ///  $op->delete();

        \App\Models\Batalha::create($busca);
        //$saldoService->rendimento($compra->saldoRaiz);

    });

    Route::get('funcionadesgraca', function (\App\Services\SaldoService $saldoService) {

        $batalhas = \App\Models\Batalha::all();

        //  dd($batalhas->toArray());


        // $compras = Compra::where('status',1)->get();

        foreach ($batalhas as $batalha) {
            $rentabilidade = $batalha->compra->plano->valor * 0.10;

            $dados = [
                'tipo' => 0,
                'descricao' => "Redimento de 10% do carro " . $batalha->compra->plano->name,
                'valor' => $rentabilidade,
                'user_id' => $batalha->compra->user_id,
                'compra_id' => $batalha->compra->id
            ];


            \App\Models\Valorredimento::create($dados);
            //Batalha::create($busca);


            if (!$batalha->compra->saldoRaiz->saldoRendimento) {
                SaldoRendimento::create([
                    'valor' => 0.00,
                    'saque_rendimento' => 0.00,
                    'saldo_raiz_id' => $batalha->compra->saldoRaiz->id,
                ]);
            }

            $valor = ($batalha->compra->saldoRaiz->valor * 10) / 100;


            $saldoRaiz = \App\Models\SaldoRaiz::find($batalha->compra->saldoRaiz->id);

            // dd($saldoRaiz);


            // $batalha->compra->saldoRaiz->saldoRendimento->valor += $valor;
            //  $batalha->compra->saldoRaiz->saldoRendimento->update();

            $saldoService->rendimento($saldoRaiz);

        }
    });

    Route::get('pacotes', function () {
        $compras = Compra::where('status', '!=', 0)->whereHas('plano', function ($query) {
            $query->where('valor', 500);
        })->get();
        foreach ($compras as $compra) {
            $user = $compra->user;

            $user->fill(['ordem' => 1]);
            $user->save();

            //dd($user);


        }
    });

    Route::get('pagar/rendimento/saque/{id}', function ($id) {

        $saque = Saquerendimento::find($id);

        $saque->fill(['status' => 1]);
        $saque->save();


        $grava = [
            'descricao' => 'Pagamento de Saque para ' . $saque->user->name,
            'valor' => $saque->valor,
            'tipo' => 0,
            'user_id' => $saque->user_id,
        ];

        Caixa::create($grava);

        return redirect(url('admin/saque'));
    });

    Route::get('pagar/indica/saque/{id}', function ($id) {

        $saque = Saqueindica::find($id);

        $saque->fill(['status' => 1]);
        $saque->save();


        $grava = [
            'descricao' => 'Pagamento de Saque para ' . $saque->user->name,
            'valor' => $saque->valor,
            'tipo' => 0,
            'user_id' => $saque->user_id,
        ];

        Caixa::create($grava);

        return redirect(url('admin/saque'));
    });
    Route::get('pagar/raiz/saque/{id}', function ($id) {

        $saque = \App\Models\Saqueraiz::find($id);
//dd($saque);
        $saque->fill(['status' => 1]);
        $saque->save();


        $grava = [
            'descricao' => 'Pagamento de Saque para ' . $saque->user->name,
            'valor' => $saque->valor,
            'tipo' => 0,
            'user_id' => $saque->user_id,
        ];

        Caixa::create($grava);

        return redirect(url('admin/saque'))->with('Success', 'Saque pago com sucesso');;
    });

    Route::get('faturaexcluir/{id}', function ($id) {

        $compra = Compra::find($id);
//$saldoraiza = \App\Models\SaldoRaiz::where('compra_id',$compra->id)->get();

//dd($saldoraiza);

        //dd($compra->saldoRaiz);
        if ($compra->saldoRaiz) {
            $compra->saldoRaiz->delete();
        }


        // $compra->saldoRaiz->delete();
        //dd($compra->id);
        $compra->delete();
        // $saldo = \App\Models\SaldoRaiz::find(686);
        // dd($saldo);
        return redirect()->back()->with('success', 'Fatura Deletada com sucesso');
    });


    Route::get('restaura/{id}', function ($id, \App\Services\SaldoService $saldoService) {
        $fatura = Compra::find($id);


        if (count($fatura->rendimentos) == 5) {
            $fatura->update(['status' => 2]);
        }
        if (!$fatura->saldoRaiz) {
            $saldoService->createSaldoRaiz($fatura);
        }
        $novo = $fatura->saldoRaiz;
        $fatura = Compra::find($fatura->id);
        $novo->update(['valor' => $fatura->plano->valor]);
        // $fatura->saldoRaiz->saldoRendimento->valor
        // dd($fatura->saldoRaiz);
        $sRend = \App\Models\SaldoRendimento::where('saldo_raiz_id', $novo->id)->first();
        if (!isset($sRend)) {
            \App\Models\SaldoRendimento::create([
                'valor' => 0.00,
                'saque_rendimento' => 0.00,
                'saldo_raiz_id' => $novo->id,
            ]);
        } else {
            $sRend->update([
                'valor' => 0.00,
                'saque_rendimento' => 0.00,
            ]);
        }

        foreach ($fatura->rendimentos as $rendimento) {

            $soma = ($fatura->plano->valor * 10 / 100);
            $total = $fatura->saldoRaiz->saldoRendimento->valor += $soma;

            $fatura->saldoRaiz->saldoRendimento->update(['valor' => $total]);
        }


        //dd( $total = $fatura->saldoRaiz->saldoRendimento->update['valor']);
    });
    Route::get('restaura2/{id}', function ($id) {
        $fatura = Compra::find($id);

        $novo = $fatura->saldoRaiz;


        if (count($fatura->rendimentos) == 5) {
            $fatura->update(['status' => 2]);
        }

        $novo->update(['valor' => 0]);
        // $fatura->saldoRaiz->saldoRendimento->valor
        // dd($fatura->saldoRaiz);


        return redirect()->back();
        //dd( $total = $fatura->saldoRaiz->saldoRendimento->update['valor']);
    });

    Route::get('gerarsaldorend', function () {
        $saldosRaiz = \App\Models\SaldoRaiz::all();
        foreach ($saldosRaiz as $saldoRaiz) {
            $saldoRend = \App\Models\SaldoRendimento::where('saldo_raiz_id', $saldoRaiz->id)->first();
            if (!isset($saldoRend)) {
                \App\Models\SaldoRendimento::create([
                    'valor' => 0.00,
                    'saque_rendimento' => 0.00,
                    'saldo_raiz_id' => $saldoRaiz->id,
                ]);
            }
        }
        echo 'pronto';
    });

    Route::get('corrigerendimento', function (\App\Services\SaldoService $saldoService) {
        $compras = \App\Models\SaldoRaiz::doesntHave('saldoRendimento')->get();


        foreach ($compras as $compra) {
            $saldoService->createSaldoRendimento($compra);

        }


    });

    Route::get('corrigirbanco', function () {

        $compras = Compra::where('status', 1)->get();

        dd($compras->toArray());
        $novos = User::whereHas('compras', function ($query) {
            $query->where('status', 1);
        })->get();

        foreach ($novos as $novo) {
            $dados = ($novo->compras->where('status', 1));
            //dd($dados);
            foreach ($dados as $dado) {
                $novabusca = $novo->compras->whereIn('valor', $dado->valor);

                if (count($novabusca) > 1) {
                    $retorno = ['id' => 0, 'valor' => 0];
                    foreach ($novabusca as $item) {
//dd($retorno['valor']);
                        if ($retorno['valor'] <= count($item->rendimentos)) {
                            $retorno = ['id' => $item->id, 'valor' => (count($item->rendimentos))];
                        } else {
                            $item->delete();
                        }
                        //    echo $item->id . " " . (count($item->rendimentos)) . "<p>";
                    }

                    // dd($retorno['id']);

                }
            }


            //  $dados = ($novo->compras->where('status', 1));

            //dd($dados);
        }

    });


    Route::get('conflito', function () {
        $compras = Compra::doesnthave('saldoRaiz')->where('status', 2)->get();
        foreach ($compras as $compra) {
            dd($compra);
        }
    });


    Route::get('buscarcontato', function () {
        $users = User::all();
        $i = 0;
        foreach ($users as $user) {
            $newtelefone = (preg_replace("/[^0-9]/", "", $user->telefone));
            // $comeco = '55';
            $user->update(['telefone' => $newtelefone]);

            $i++;
        }

        dd($i);
    });
    Route::get('buscarcontato1', function () {
        $users = User::all();
        $i = 0;
        foreach ($users as $user) {

            if (substr($user->telefone, 0, 2) === "55") {
                echo $user->telefone . "<br>";
                $i++;
            } else {
                $user->update(['telefone' => '55' . $user->telefone]);
            }

        }

        dd($i);
    });

    Route::get('addcontato', function () {
        return view('admin.contato');
    });
    Route::post('consultarcontato', function (Request $request) {
        //dd($request->all());
        $i = 0;
        $contatos = explode(",", $request->contatos);

        //dd($contatos);
        foreach ($contatos as $contato) {
            $newtelefone = (preg_replace("/[^0-9]/", "", $contato));
            // dd($newtelefone);

            $busca = User::where('telefone', $newtelefone)->first();
            if (isset($busca)) {
                $i++;
                $busca->update(['grupo' => 1]);
            }

        }
        return redirect()->back()->with('success', 'Foram Adicionados ' . $i . ' contatos');
    });

    Route::get('testeutil', function (\App\Services\CalendarService $calendarService) {

        $today = Carbon::now();

        // dd($today);


        $controle = true;
        while ($controle <= 1) {

            $validadata = ($calendarService->validaDia($today));
            if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY || $validadata == true) {
                $today->addDay();
            } else {
                $controle++;
            }

        }

        dd($today);
    });

});
