<?php

use App\Helpers\LogActivity as HelpersLogActivity;
use App\Http\Controllers\AcaoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\VantagemController;
use App\Mail\PaymentMail;
use App\Models\Anexo;
use App\Models\Assinatura;
use App\Models\Batalha;
use App\Models\Caixa;
use App\Models\Chat;
use App\Models\Compra;
use App\Models\Conta;
use App\Models\Credito;
use App\Models\Doc;
use App\Models\Endereco;
use App\Models\Estado;
use App\Models\Extrato;
use App\Models\Historico;
use App\Models\LogActivity;
use App\Models\Meta;
use App\Models\Movimento;
use App\Models\Plano;
use App\Models\Produto;
use App\Models\Relatorio;
use App\Models\Resposta;
use App\Models\Saque;
use App\Models\Saqueindica;
use App\Models\Saquerendimento;
use App\Models\Soma;
use App\Models\User;
use App\Models\Valorindicacao;
use App\Models\Valorredimento;
use App\Models\Video;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use BlockmoveAPI\APIClient;
use BlockmoveAPI\APIException;
use BlockmoveAPI\APIRequestException;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(url('login'));
});
Route::get("/logout", function () {
    return redirect('/dashboard');
});
Route::get('testevenda', function () {
    return view('vendas.index');
});


Route::get('chat/close/{id}', function ($id) {
    $chat = Chat::find($id);
    $chat->fill(['aberto' => 1]);
    $chat->save();

    return redirect('admin/chat');
})->middleware(['auth']);
Route::get('chat/open/{id}', function ($id) {
    $chat = Chat::find($id);
    $chat->fill(['aberto' => 0]);
    $chat->save();

    return redirect('admin/chat');
})->middleware(['auth']);

Route::get('testeapi', function () {
    return view('teste.api');
})->middleware(['auth']);
Route::get('indicacao/v2/{id}/plano/{plano}', function ($id, $plano) {
    $combo = Plano::find($plano);
    // dd($combo);
    $user = User::where('link', $id)->first();
    $user->fill(['contador' => $user->contador + 1]);
    $user->save();
    $planos = Plano::all();
    $produtos = Produto::all();
    return view('indicacao.register2', compact('user', 'planos', 'combo', 'produtos'));
});
Route::get('indicacao/v2/{id}', function ($id) {

    // dd($combo);
    $user = User::where('link', $id)->first();
    $user->fill(['contador' => $user->contador + 1]);
    $user->save();
    $planos = Plano::orderBy('valor', 'asc')->get();
    $produtos = Produto::orderBy('ordem', 'asc')->get();
    // $planos = Plano::orderBy('valor', 'asc')->get();
    return view('vendas.index', compact('user', 'planos', 'produtos'));
});
Route::get('/dashboard', function () {

    //dd(Auth::user());
    $planos = Plano::orderBy('valor', 'asc')->get();
    $indicados = User::where('quem', Auth::user()->link)->take(10)->get();
    $users = User::withCount('indicados')->where('quem', Auth::user()->link)->orderByDesc('indicados_count')->limit(10)->get();


    $buscas = Valorindicacao::where('user_id', Auth::user()->id)->orderByDesc('created_at')->get();
    $reward = Valorindicacao::where('user_id', Auth::user()->id)->sum('valor');


    if (Auth::user()->tipo != 0) {
        return redirect(url('admin/dashboard'));
    }

    return view('novodash.index', compact('planos', 'indicados', 'users', 'reward', 'buscas'));
})->middleware(['auth'])->name('dashboard');

Route::get('teste', function () {
    $planos = Plano::all();
    return view('painel.index', compact('planos'));
});



Route::get('corrige', function () {
    $users = User::all();
    foreach ($users as $user) {
        $user->fill(['link' => md5($user->cpf)]);
        $user->save();
    }
});
Route::resource('metas', MetaController::class);

Route::get('purchase/{id}', [AdminController::class, 'faturas'])->middleware(['auth']);

Route::get('renovar/{id}', function($id, \App\Services\CalendarService $calendarService, \App\Services\SaldoService $saldoService) {


    $antiga = Compra::find($id);
    //dd($antiga);
    $dados = [

        'plano_id' => $antiga->plano_id,
        'user_id' => Auth::user()->id,

    ];
    $compra = Compra::create($dados);
    $compra->fill([

        'status' => 1,
        'ativo' => 1,
        'dia_pagamento' => Carbon::now(),
        'valor' => $compra->plano->valor

    ]);
    $compra->save();

    $antiga->saldoRaiz->update(['valor' => 0]);



    $fatura = $compra;


    $buscar = $calendarService->validaDia($fatura->dia_pagamento);


    if ($buscar['respota'] == true) {
        $nova = $calendarService->validaDia($buscar['data']);

        if ($nova['respota'] == true) {
            $nova = $calendarService->validaDia($nova['data']);
            if ($nova['respota'] == true) {

            } else {
                $novadata = $nova['data'];
            }
        } else {
            $novadata = $nova['data'];
        }
    } else {
        $novadata = $buscar['data'];
    };


    $today = Carbon::parse($novadata);

    //dd($today->dayOfWeek)'
    if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY) {
        $today = $today->addDay();

        if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY) {

            $today = $today->addDay();
            if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY) {

            } else {
                $novadata = $today;
            }

        } else {
            $novadata = $today;
        }

    } else {
        $novadata = $today;
    }

    $fatura->update(['primeiro_rendimento' => $novadata]);



    $saldoService->createSaldoRaiz($compra);


    $grava = [
        'descricao' => 'Renovação de plano do ' . $compra->user->name,
        'valor' => $compra->plano->valor,
        'tipo' => 1,
        'user_id' => $compra->user->id,
    ];
    Caixa::create($grava);

    return redirect()->back();
});

Route::get('recruit/{id}', function ($id) {
    $user = User::where('link', $id)->first();
    //dd($user);
    if (isset($user)) {
        $user->fill(['contador' => $user->contador + 1]);
        $user->save();
        return view('indicacao.register', compact('user'));
    }

    return redirect(url('register'));
});

Route::post('registerindicado', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'login' => ['required', 'string', 'unique:users'],
        'password' => ['required', 'confirmed'],
        'cpf' => ['cpf', 'required'],
        'telefone' => ['required'],


    ]);

    //dd($request->all());


    $user = User::create([
        'name' => $request->name,
        'login' => $request->login,
        'email' => $request->email,
        'cpf' => $request->cpf,
        'password' => Hash::make($request->password),
        'telefone' => $request->telefone,
        'link' => md5($request->email),
        'quem' => $request->quem,


    ]);


    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
});


Route::get('player', function (\App\Services\CalendarService $calendarService) {
    //$direto = Auth::user()->indicados->pluck('link')->toArray();

    //dd($direto);
    $agora = Carbon::now();

    $resposta = ($calendarService->validaDia($agora)['respota']);

    $reward = Valorindicacao::where('user_id', Auth::user()->id)->sum('valor');

    $primeiros = User::where("quem", Auth::user()->link)->get();


    if (count($primeiros) > 0) {
        $segundos = User::whereIn("quem", $primeiros->pluck('link')->toArray())->whereHas('compras')->get();


       // dd($segundos->whereHas('compras'));
    } else {
        $segundos = [];
    }

    // dd($segundos);
    if (count($segundos) > 0) {
        $terceiros = User::whereIn("quem", $segundos->pluck('link')->toArray())->get();
    } else {
        $terceiros = [];
    }
    // dd($terceiros);


    return view('indicacao.diretos', compact('primeiros', 'segundos', 'terceiros', 'reward','resposta'));
})->middleware(['auth']);

Route::get('primeiro', function () {

    //dd('oi');

    $direto = Auth::user()->indicados->pluck('link')->toArray();

    //dd($direto);

    if (count($direto) > 0) {
        $indicados = User::whereIn("quem", $direto)->get();
        //dd($indicados);
    } else {
        $indicados = [];
    }


    $primeiros = User::whereIn("quem", $direto)->pluck('link');
    if (count($primeiros) > 0) {
        $segundos = User::whereIn("quem", $primeiros)->get();
    } else {
        $segundos = [];
    }
    if (count($segundos) > 0) {
        $terceiros = User::whereIn("quem", $segundos)->get();
    } else {
        $terceiros = [];
    }
    //  dd($primeiros);


    // dd($indicados);
    return view('indicacao.primeiro', compact('indicados', 'segundos', 'terceiros'));
});


Route::get('segundo', function () {

    //dd('oi');

    $direto = Auth::user()->indicados->pluck('link');

    $primeiros = User::whereIn("quem", $direto)->pluck('link');
    if (count($primeiros) > 0) {
        $indicados = User::whereIn("quem", $primeiros)->get();
    } else {
        $indicados = [];
    }


    // dd($indicados);
    $nivel = "Segundo";

    // dd($indicados);
    return view('indicacao.primeiro', compact('indicados', 'nivel'));
});

Route::get('terceiro', function () {

    //dd('oi');

    $direto = Auth::user()->indicados->pluck('link');
    if (count($direto) > 0) {
        $primeiros = User::whereIn("quem", $direto)->pluck('link');
    } else {
        $primeiros = [];
    }

    if (count($primeiros) > 0) {
        $segundos = User::whereIn("quem", $primeiros)->pluck('link');
    } else {
        $segundos = [];
    }


    //dd($segundos);


    if (count($segundos) > 0) {
        $indicados = User::whereIn("quem", $segundos)->get();
    } else {
        $indicados = [];
    }
    $nivel = "Terceiro";

    // dd($indicados);
    return view('indicacao.primeiro', compact('indicados', 'nivel'));
});


Route::get('customer/invoices', function () {
    $busca = Compra::where('user_id', Auth::user()->id)->first();

    return view('cliente.faturas.index', compact('busca'));
})->middleware(['auth']);

Route::get('rewards', function () {
    $buscas = Valorredimento::where('user_id', Auth::user()->id)->get();

    //$reward = Valorredimento::where('user_id', Auth::user()->id)->sum('valor');
    $reward = Auth::user()->totalTodosRendimentos();

   // dd($reward);
    $tempo = Compra::where("user_id", Auth::user()->id)->where("ativo", 1)->first();
    if (isset($tempo)) {
        $finishTime = Carbon::parse($tempo['created_at']);
    } else {
        $finishTime = Carbon::now();
    }
    $startTime = Carbon::now();
    $totalDuration = $finishTime->diffInMinutes($startTime);
    //dd($totalDuration);
    return view('financeiro.geral', compact('buscas', 'reward', 'totalDuration'));
})->middleware(['auth']);

Route::get('player/bonus2', function () {
    $indicados = User::where('quem', Auth::user()->link)->take(10)->get();
    $users = User::withCount('indicados')->where('quem', Auth::user()->link)->orderByDesc('indicados_count')->limit(10)->get();
    return view('relatorio.geral', compact('users'));
})->middleware(['auth']);
Route::get('player/bonus', function () {
    $indicados = User::where('quem', Auth::user()->link)->take(10)->get();
    $users = User::withCount('indicados')->where('quem', Auth::user()->link)->orderByDesc('indicados_count')->limit(10)->get();

    $buscas = Valorindicacao::where('user_id', Auth::user()->id)->get();
    $reward = Valorindicacao::where('user_id', Auth::user()->id)->sum('valor');
    //  dd($reward);
    return view('relatorio.geral2', compact('users', 'buscas', 'reward'));
})->middleware(['auth']);
Route::get('cliente/pontos', function () {
    return view('relatorio.pontos');
})->middleware(['auth']);

Route::get('cliente/saque', function () {
    //dd(Auth::user()->saldo);
    return view('saque.index');
})->middleware(['auth']);

Route::get('carryout/withdrawal', function () {

    //  $valor = Auth::user()->sobra;

    $buscas = Valorredimento::where("user_id", Auth::user()->id)->get();
    $total = $buscas->sum('valor');

    if ($total > 25) {
        $valores =
            [
                'valor' => -$buscas->sum('valor'),
                'status' => 0,
                'user_id' => Auth::user()->id
            ];
        //dd($valores);

        //  \App\Models\Saque::create($valores);

        $dados = [
            'tipo' => 1,
            'descricao' => 'withdrawal ref. reward',
            'valor' => -$buscas->sum('valor'),
            'user_id' => Auth::user()->id
        ];
        Valorredimento::create($dados);

        //dd($pontuacao);
    }
    //   \App\Models\Movimento::create($dados);

    return redirect()->back();
})->middleware(['auth']);
Route::post('carryout/withdrawal/squad', function (Request $request) {
    return redirect()->back()->with('Error', 'Saque disponível somente em dias úteis');
    $validated = $request->validate([

        'meio_id' => 'required',


    ]);
    // dd($request->all());
    //  $valor = Auth::user()->sobra;

    $buscas = Valorindicacao::where("user_id", Auth::user()->id)->get();
    $total = $buscas->sum('valor');

    //dd($total);

    if ($total >= 20) {
        $valores =
            [
                'valor' => -$buscas->sum('valor'),
                'status' => 0,
                'user_id' => Auth::user()->id
            ];
        //dd($valores);

        //  \App\Models\Saque::create($valores);

        $dados = [
            'tipo' => 1,
            'descricao' => 'Saque Referente ao meu time',
            'valor' => -$buscas->sum('valor'),
            'user_id' => Auth::user()->id
        ];
        Valorindicacao::create($dados);

        Saqueindica::create([
            'data' => Carbon::now(),
            'valor' => $buscas->sum('valor'),
            'user_id' => Auth::user()->id,
            'meio' => $request->meio_id
        ]);

        //dd($pontuacao);
        return redirect()->back()->with('success', 'Saque Realizado com Sucesso');
    }
    //   \App\Models\Movimento::create($dados);

    return redirect()->back()->with('error', 'Saldo menor que R$ 20,00');
})->middleware(['auth']);

Route::get('/ships2', function () {
    $planos = Plano::orderBy('valor', 'asc')->get();
    // $users = User::all()->with('rated')->get()->sortByDesc('rated.rating');
    //    $planos = Plano::all()->with('vantagems')->get()->sortByDesc('vantagems.created_at');
    // dd($planos);
    return view('cliente.planos.index', compact('planos'));
})->middleware(['auth']);
Route::get('/ships', function () {
    $planos = Plano::orderBy('valor', 'asc')->get();
    // $users = User::all()->with('rated')->get()->sortByDesc('rated.rating');
    //    $planos = Plano::all()->with('vantagems')->get()->sortByDesc('vantagems.created_at');
    // dd($planos);
    return view('cliente.planos.index2', compact('planos'));
})->middleware(['auth']);

Route::get('endereco', function () {
    dd('oi');
});


Route::get('myaccount', function () {
    $docs = Doc::all();
    return view('cliente.minhaconta', compact('docs'));
})->middleware(['auth']);

Route::post('endereco', function (Request $request) {

    $estado = \App\Models\Estado::where("name", $request->uf)->first();

    if (!($estado)) {
        $estado = \App\Models\Estado::create(['name' => $request->uf]);
    }
    $cidade = \App\Models\Cidade::where("name", $request->cidade)->where("estado_id", $estado->id)->first();

    if (!($cidade)) {
        $cidade = \App\Models\Cidade::create(['name' => $request->cidade, 'estado_id' => $estado->id]);
    }

    $bairro = \App\Models\Bairro::where('name', $request->bairro)->where("estado_id", $estado->id)->where("cidade_id", $cidade->id)->first();

    //dd($bairro);
    if (!($bairro)) {
        $bairro = \App\Models\Bairro::create(
            [
                'name' => $request->bairro,
                'estado_id' => $estado->id,
                'cidade_id' => $cidade->id
            ]
        );
    }

    $grava = [
        'cep' => $request->cep,
        'endereco' => $request->endereco,
        'bairro_id' => $bairro->id,
        'user_id' => Auth::user()->id,
        'cidade_id' => $cidade->id,
        'estado_id' => $estado->id,
        'n' => $request->n,
        'complemento' => $request->complemento
    ];

    \App\Models\Endereco::create($grava);
    return redirect()->back();
});

Route::post('alterendereco', function (Request $request) {
    $estado = \App\Models\Estado::where("name", $request->uf)->first();


    if (!($estado)) {
        $estado = \App\Models\Estado::create(['name' => $request->uf]);
    }
    $cidade = \App\Models\Cidade::where("name", $request->cidade)->where("estado_id", $estado->id)->first();

    if (!($cidade)) {
        $cidade = \App\Models\Cidade::create(['name' => $request->cidade, 'estado_id' => $estado->id]);
    }

    $bairro = \App\Models\Bairro::where('name', $request->bairro)->where("estado_id", $estado->id)->where("cidade_id", $cidade->id)->first();

    //dd($bairro);
    if (!($bairro)) {
        $bairro = \App\Models\Bairro::create(
            [
                'name' => $request->bairro,
                'estado_id' => $estado->id,
                'cidade_id' => $cidade->id
            ]
        );
    }

    $grava = [
        'cep' => $request->cep,
        'endereco' => $request->endereco,
        'bairro_id' => $bairro->id,
        'user_id' => Auth::user()->id,
        'cidade_id' => $cidade->id,
        'estado_id' => $estado->id,
        'n' => $request->n,
        'complemento' => $request->complemento
    ];


    $user = Auth::user()->endereco;
    $user->fill($grava);
    $user->save();
    return redirect()->back();
});

Route::post('cadconta', [\App\Http\Controllers\PixController::class, 'store'])->middleware(['auth']);
Route::put('editapix/{pix}', [\App\Http\Controllers\PixController::class, 'update'])->middleware(['auth']);
Route::post('cadbankon', [\App\Http\Controllers\BankonController::class, 'store'])->middleware(['auth']);
Route::put('editabankon/{bankon}', [\App\Http\Controllers\BankonController::class, 'update'])->middleware(['auth']);

Route::post('admin/cadconta', function (Request $request) {
    $validated = $request->validate([

        'agencia' => 'required',


    ]);

    \App\Models\Conta::create($request->all());
    return redirect()->back();
});

Route::get('testepagamento', function () {
    //  $asaas = new Asaas('seu_token_de_acesso');

    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');


    //  $clientes = $asaas->Cliente()->getAll();

    // dd($clientes);
    $dadosLink = [
        "name" => "PLUS",
        "description" => "PLANO PLUS",
        "endDate" => "2022-01-15",
        "value" => 119.90,
        "billingType" => "UNDEFINED",
        "chargeType" => "DETACHED",
        "dueDateLimitDays" => 2,
        "subscriptionCycle" => null,
        "maxInstallmentCount" => 1

    ];
    $LinkPagamento = $asaas->LinkPagamento()->create($dadosLink);

    // $cobranca = $asaas->Cobranca()->getById(709376);


    // dd($LinkPagamento);

    return redirect($LinkPagamento->url);
});

Route::get('gerarreecibo/{id}', function ($id) {
    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');

    $cobranca = $asaas->Cobranca()->getById($id);

    //dd($cobranca);

    return redirect($cobranca->transactionReceiptUrl);
});

Route::get('cliente/pagarplano/{id}', function ($id) {


    if (!Auth::user()->endereco) {
        return redirect(url('minhaconta'));
    }


    $assinatura = Assinatura::find($id);
    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');


    $cliente = $asaas->Cliente()->getByCpf(Auth::user()->cpf);

    // dd($cliente->data);

    if (!$cliente->data) {

        $dados = array(
            'name' => Auth::user()->name,
            'cpfCnpj' => Auth::user()->cpf,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->telefone,
            'mobilePhone' => Auth::user()->telefone,
            'address' => '',
            'addressNumber' => Auth::user()->endereco->n,
            'complement' => '',
            'province' => '',
            'postalCode' => Auth::user()->endereco->cep,
            'externalReference' => '',
            'notificationDisabled' => '',
            'additionalEmails' => ''
        );

        $cliente = $asaas->Cliente()->create($dados);

        $clientenovo = $cliente;
    } else {
        $clientenovo = $cliente->data[0]->id;
    }

    //dd($cliente);

    if ($assinatura->buscador == '') {


        $dadosCobranca = array(
            'customer' => $clientenovo,
            'billingType' => 'UNDEFINED',
            'value' => $assinatura->valor,
            'dueDate' => Carbon::now()->format('Y-m-d'),
            'description' => $assinatura->plano->name . ' ' . "Pagamento referente ao mês de " . Carbon::create($assinatura->inicio)->monthName,
            'externalReference' => '',
            'installmentCount' => '',
            'installmentValue' => '',
            'discount' => '',
            'interest' => '',
            'fine' => '',
        );


        $cobranca = $asaas->Cobranca()->create($dadosCobranca);
        //$LinkPagamento = $asaas->LinkPagamento()->create($dadosLink);
        //dd($cobranca);

        $assinatura->fill(['buscador' => $cobranca->id]);


        $assinatura->save();
    }

    $cobranca = $asaas->Cobranca()->getById($assinatura->buscador);

    //dd($cobranca);
    // $LinkPagamento = $asaas->LinkPagamento()->getById($assinatura->buscador);
    //dd($LinkPagamento);
    return redirect($cobranca->invoiceUrl);
    //dd($assinatura);
    //  dd($LinkPagamento);


});


Route::get('consultarfaturas/{id}', function ($id) {

    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');

    $cobranca = $asaas->Cobranca()->getById($id);

    dd($cobranca);
});


Route::get('testecliente', function () {


    //dd($dados);

    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');

    //$cliente = $asaas->Cliente()->create($dados);
    $dados = array(
        'name' => Auth::user()->name,
        'cpfCnpj' => Auth::user()->cpf,
        'email' => Auth::user()->email,
        'phone' => Auth::user()->telefone,
        'mobilePhone' => Auth::user()->telefone,
        'address' => '',
        'addressNumber' => Auth::user()->endereco->n,
        'complement' => '',
        'province' => '',
        'postalCode' => Auth::user()->endereco->cep,
        'externalReference' => '',
        'notificationDisabled' => '',
        'additionalEmails' => ''
    );
    $cliente = $asaas->Cliente()->getByCpf('00508571260');
    if (!$cliente) {

        $cliente = $asaas->Cliente()->create($dados);
    }

    $dadosCobranca = array(
        'customer' => $cliente->data[0]->id,
        'billingType' => "UNDEFINED",
        'value' => 50,
        'dueDate' => "2022-01-24",
        'description' => "Qualquer livro por apenas R$: 50,00",
        'externalReference' => '',
        'installmentCount' => '',
        'installmentValue' => '',
        'discount' => '',
        'interest' => '',
        'fine' => '',
    );

    $cobranca = $asaas->Cobranca()->getById('pay_4489705199214310');

    dd($cobranca);
});





Route::get('novo/registro', function () {
    HelpersLogActivity::addToLog('Acessou Função para adicionar Registro ao Caixa');
    return view('admin.registrarcaixa');
});

Route::post('registro/caixa', function (Request $request) {
    // dd($request->all());
    $request->validate([
        'descricao' => 'required',
        'valor' => ['required'],
        'tipo' => ['required'],
    ]);

    $request['user_id'] = Auth::user()->id;

    Caixa::create($request->all());

    if ($request->tipo == 1) {
        HelpersLogActivity::addToLog('Adicionou Registro de Entrada no Valor de R$' . number_format($request->valor, 2, ',', '.'));
    } else {
        HelpersLogActivity::addToLog('Adicionou Registro de Saida no Valor de R$' . number_format($request->valor, 2, ',', '.'));
    }

    return redirect(url('admin/caixa'));
});


Route::get('produto', function () {
    return view('produtos.index');
})->middleware(['auth']);

Route::get('upgrade/{id}', function ($id) {
    //dd($id);

    $plano = Plano::find($id);

    // dd($plano);

    $assinaturas = Auth::user()->assinaturas->where('status', 0);

    if (count($assinaturas) > 0) {
        foreach ($assinaturas as $assinatura) {
            if (count(Auth::user()->creditos->where('status', 1)) > 0) {
                if ($assinatura->unico == 1) {
                    $assinatura->fill(['plano_id' => $id, 'valor' => $plano->valor - $assinatura->valor]);
                    $assinatura->save();
                } else {
                    $assinatura->fill(['plano_id' => $id, 'valor' => $plano->valor - $assinatura->valor]);
                    $assinatura->save();
                }
            } else {
                $assinatura->fill(['plano_id' => $id, 'valor' => $plano->valor]);
                $assinatura->save();
            }
        }
    } else {
        $naobuscar = Auth::user()->assinaturas->where('status', 1)->where('unico', 1)->first();

        $assinaturas = Auth::user()->assinaturas->where('status', 1)->whereNotIn('id', $naobuscar->id);
        // dd(count($assinaturas));

        // dd($plano->valor);

        $diferenca = (count($assinaturas) * $plano->valor);


        $divisao = $diferenca - (Auth::user()->creditos->where("status", 1)->sum('valor') - $naobuscar->valor);
        //dd($divisao);
        // dd($diferenca);
        // $abertas = Assinatura::where('user_id', Auth::user()->id)->get();
        foreach ($assinaturas as $assinatura) {
            if ($assinatura->unico == 1) {

                // dd(count(Auth::user()->abertas()));
                // dd($assinatura);
                //dd($diferenca / count($assinaturas));
                $assinatura->fill(['plano_id' => $id, 'valor' => $divisao / count($assinaturas), 'status' => 0]);
                $assinatura->save();
            }
        }
    }

    return redirect(url('cliente/faturas'));
});

Route::get('arquivo', function () {

    return view('material.index');
})->middleware(['auth']);


Route::get('buscafatura/{id}', function ($id) {
    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');
    $cobranca = $asaas->Cobranca()->getById($id);

    dd($cobranca);
})->middleware(['auth']);


Route::get('gerarelatorio', function () {

    $agora = Carbon::now();

    $entrada = Caixa::where("tipo", 1)->whereMonth('created_at', $agora)->sum('valor');
    $faturaspendentes = Assinatura::where("status", 0)->with('plano')->whereMonth('inicio', $agora)->count();
    $faturaspagas = Assinatura::where("status", 1)->with('plano')->whereMonth('inicio', $agora)->count();

    $valorespendentes = Assinatura::where("status", 0)->whereMonth('inicio', $agora)->get();


    $users = User::withCount('indicados')->orderByDesc('indicados_count')->limit(10)->get();
    $estados = Estado::withCount('enderecos')->orderByDesc('enderecos_count')->get();

    //dd($users);
    //$users = User::withCount('indicados')->get();

    //dd($users);
    //dd($valorespendentes);
    $totalpendente = 0;
    foreach ($valorespendentes as $valorespendente) {
        $totalpendente += $valorespendente->plano->valor;
    }


    $usuarios = User::whereHas('assinaturas')->get();
    $nousers = User::doesnthave('assinaturas')->get()->pluck('id')->toArray();

    $controle = [];

    $semassinatura = [];

    foreach ($usuarios as $user) {
        if ($user->assinaturas[0]->status == 1) {

            $controle[] = $user->id;
        } else {
            $semassinatura[] = $user->id;
        }
    }
    foreach ($nousers as $nouser) {
        $semassinatura[] = $nouser;
    }

    //dd($semassinatura);

    $ativos = User::whereIn('id', $controle)->get();
    $inativos = User::whereIn('id', $semassinatura)->get();

    //dd($totalpendente);
    $saida = Caixa::where("tipo", 0)->whereMonth('created_at', $agora)->sum('valor');
    $saques = Saque::where("status", 1)->whereMonth('created_at', $agora)->sum('valor');

    $grava = [
        'data' => Carbon::now()->subDay(1),
        'entrada' => $entrada,
        'saques' => $saques,
        'pendentes' => $totalpendente,
        'despesas' => $saida,
        'ativos' => count($ativos),
        'inativos' => count($inativos),
        'pagas' => $faturaspagas,
        'naopagas' => $faturaspendentes,
    ];

    Relatorio::create($grava);
})->middleware(['auth']);


Route::get('add-to-log', function () {
    HelpersLogActivity::addToLog('My Testing Add To Log.');
    dd('log insert successfully.');
})->middleware(['auth']);

Route::get('pagamento/unico', function () {

    if (!Auth::user()->endereco) {
        return redirect(url('minhaconta'));
    }


    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');


    $cliente = $asaas->Cliente()->getByCpf(Auth::user()->cpf);

    // dd($cliente->data);

    if (!$cliente->data) {

        $dados = array(
            'name' => Auth::user()->name,
            'cpfCnpj' => Auth::user()->cpf,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->telefone,
            'mobilePhone' => Auth::user()->telefone,
            'address' => '',
            'addressNumber' => Auth::user()->endereco->n,
            'complement' => '',
            'province' => '',
            'postalCode' => Auth::user()->endereco->cep,
            'externalReference' => '',
            'notificationDisabled' => '',
            'additionalEmails' => ''
        );

        $cliente = $asaas->Cliente()->create($dados);

        $clientenovo = $cliente;
    } else {
        $clientenovo = $cliente->data[0]->id;
    }
    $saldo = Auth::user()->creditos->sum('valor');
    $emaberto = Auth::user()->abertas()->sum('valor');
    //dd($emaberto);

    $busca = Credito::where("user_id", Auth::user()->id)->where('status', 0)->first();
    $valor = count(\App\Models\Assinatura::where('user_id', Auth::user()->id)->where('status', 0)->get()) * Auth::user()->assinaturas->last()->plano->valor;
    $meses = count(\App\Models\Assinatura::where('user_id', Auth::user()->id)->where('status', 0)->get());
    $ultimo = Auth::user()->assinaturas->last();
    //dd($ultimo);
    // dd($ultimo);

    if (!$busca) {
        //dd($emaberto);

        /*    $grava2 = [
            'inicio' => $ultimo->fim,
            'fim' => Carbon::create($ultimo->fim)->addMonth($meses),
            'status' => 0,
            'plano_id' => $ultimo->plano_id,
            'user_id' => Auth::user()->id,
            'unico' => 1,
            'valor' => $valor
        ]; */
        if ($saldo > 0) {
            $grava2 = [
                'valor' => $emaberto,
                'user_id' => Auth::user()->id,
                'plano_id' => $ultimo->plano->id,

            ];
        } else {
            $grava2 = [
                'valor' => $valor,
                'user_id' => Auth::user()->id,
                'plano_id' => $ultimo->plano->id,

            ];
        }


        $busca = Credito::create($grava2);
    }


    if ($busca->buscador == '') {


        $dadosCobranca = array(
            'customer' => $clientenovo,
            'billingType' => 'UNDEFINED',
            'value' => $emaberto,
            'dueDate' => Carbon::now()->format('Y-m-d'),
            'description' => $busca->user->name . ' ' . "Pagamento Unico",
            'externalReference' => '',
            'installmentCount' => '',
            'installmentValue' => '',
            'discount' => '',
            'interest' => '',
            'fine' => '',
        );


        $cobranca = $asaas->Cobranca()->create($dadosCobranca);
        //$LinkPagamento = $asaas->LinkPagamento()->create($dadosLink);
        //dd($cobranca);

        $busca->fill(['buscador' => $cobranca->id]);


        $busca->save();
    }

    //dd($busca);
    $cobranca = $asaas->Cobranca()->getById($busca->buscador);

    //dd($cobranca);
    // $LinkPagamento = $asaas->LinkPagamento()->getById($assinatura->buscador);
    //dd($LinkPagamento);
    return redirect($cobranca->invoiceUrl);


    dd($busca);
});

Route::get('admin/contrato/{id}', function ($id) {
    $user = User::find($id);

    return view('contrato.index', compact('user'));
    dd($user);
});

Route::get('gerarteste', function () {
    $user = Auth::user();
    // dd($user->assinaturas->first());

    $grava = [
        'inicio' => Carbon::create($user->assinaturas->first()->inicio),
        'pontos' => $user->pontos,
        'user_id' => $user->id,
        'fechado' => 1,
    ];

    Historico::create($grava);
});

Route::get('docs', function () {
    $docs = Doc::all();
    return view('admin.documento.index', compact('docs'));
});

Route::get('docs/create', function () {
    return view('admin.documento.create');
});
Route::post('docs/create', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],

    ]);

    Doc::create($request->all());
    return redirect(url('docs'));
});


Route::get('bitbit', function () {


    // dd($imagem);
});
Route::get('docs/edit/{id}', function ($id) {
    $doc = Doc::find($id);

    return view('admin.documento.edit', compact('doc'));
});

Route::post('docs/edit/', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],

    ]);
    $doc = Doc::find($request->id);

    $doc->fill($request->all());

    $doc->save();

    //dd($doc);
    return redirect(url('docs'));
});
Route::get('admin/user/docs/{id}', function ($id) {
    $user = User::find($id);
    $docs = Doc::all();
    return view('admin.consulta', compact('user', 'docs'));
});
Route::get("validar/{id}", function ($id) {
    $doc = Anexo::find($id);
    $doc->fill(['valido' => 1]);
    $doc->save();
    return redirect()->back();
});
Route::get("invalidar/{id}", function ($id) {
    $doc = Anexo::find($id);
    if ($doc->verso) {
        unlink('arquivos/' . $doc->verso);
    }
    unlink('arquivos/' . $doc->frente);
    Anexo::destroy($id);
    //  $doc->fill(['valido' => 1]);
    // $doc->save();
    return redirect()->back();
});

Route::get('admin/cortesia/{id}', function ($id) {
    $fatura = Compra::find($id);

    $caixa = [
        'descricao' => 'Recebido da mensalidade cortesia do ' . $fatura->user->name,
        'valor' => 0,
        'tipo' => 1,
        'user_id' => $fatura->user->id,
    ];

    $fatura->fill(['ativo' => 1]);
    $fatura->save();
    Caixa::create($caixa);
    return redirect(url('admin/faturas'));
});

Route::get('corrigefaturas', function () {
    //$user = Auth::user();

    $users = User::all();
    foreach ($users as $user) {
        $creditos = Credito::where('user_id', $user->id)->where('status', 1)->sum('valor');
        //dd($creditos);

        if ($creditos > 0) {
            $abertas = Assinatura::where('user_id', $user->id)->where("status", 0)->get();


            foreach ($abertas as $aberta) {

                $aberta->fill(['status' => 1, 'unico' => 1]);
                $aberta->save();

                $grava = [
                    'descricao' => 'Recebido da mensalidade do ' . $aberta->user->name,
                    'valor' => $aberta->plano->valor,
                    'tipo' => 1,
                    'user_id' => Auth::user()->id,
                ];
                Caixa::create($grava);
                if (!empty($aberta->user->quem)) {
                    $plano = Plano::find($aberta->plano->id);

                    $user = User::where('link', $aberta->user->quem)->first();

                    $planolast = $user->assinaturas->last();

                    if (!empty($planolast)) {

                        if ($aberta->user->assinaturas->where("status", 1)->count() == 1) {
                            $extrato = [
                                'user_id' => $user->id,
                                'indicado_id' => $aberta->user->id,
                                'pontos' => $plano->pontos,
                                'saldo' => ($plano->valor - ($plano->valor * 0.3))
                            ];


                            Extrato::create($extrato);
                            $pontuacao = $user->pontos + $plano->pontos;
                            $dados = [
                                'tipo' => 0,
                                'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Direto ' . $plano->name,
                                'valor' => ($plano->valor - ($plano->valor * 0.3)),
                                'user_id' => $user->id
                            ];
                        } else {
                            $extrato = [
                                'user_id' => $user->id,
                                'indicado_id' => $aberta->user->id,
                                'pontos' => $plano->pontos,
                                'saldo' => ($plano->valor - ($plano->valor * ((100 - $planolast->plano->direto) / 100)))
                            ];


                            Extrato::create($extrato);
                            $pontuacao = $user->pontos + $plano->pontos;
                            $dados = [
                                'tipo' => 0,
                                'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Direto ' . $plano->name,
                                'valor' => ($plano->valor - ($plano->valor * ((100 - $planolast->plano->direto) / 100))),
                                'user_id' => $user->id
                            ];
                        }

                        \App\Models\Movimento::create($dados);
                        $user->fill(['pontos' => $pontuacao]);
                        $user->save();
                    }


                    $primeiro = User::where('link', $user->quem)->first();

                    if (!empty($primeiro)) {

                        $planolast1 = $primeiro->assinaturas->last();

                        //dd($planolast->plano->direto);


                        if (!empty($planolast1)) {

                            if (count($aberta->user->assinaturas->where("status", 1)) > 1) {
                                $extrato1 = [
                                    'user_id' => $primeiro->id,
                                    'indicado_id' => $aberta->user->id,
                                    'pontos' => $plano->pontos,
                                    'saldo' => ($plano->valor - ($plano->valor * ((100 - $planolast1->plano->primeiro) / 100)))
                                ];
                                $dados1 = [
                                    'tipo' => 0,
                                    'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Primeiro Nivel ' . $plano->name,
                                    'valor' => ($plano->valor - ($plano->valor * ((100 - $planolast1->plano->primeiro) / 100))),
                                    'user_id' => $primeiro->id
                                ];
                            } else {
                                $extrato1 = [
                                    'user_id' => $primeiro->id,
                                    'indicado_id' => $aberta->user->id,
                                    'pontos' => $plano->pontos,
                                    'saldo' => 0
                                ];
                                $dados1 = [
                                    'tipo' => 0,
                                    'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Primeiro Nivel ' . $plano->name,
                                    'valor' => 0,
                                    'user_id' => $primeiro->id
                                ];
                            }


                            // dd($extrato);
                            \App\Models\Movimento::create($dados1);

                            Extrato::create($extrato1);
                            $pontuacao = $primeiro->pontos + $plano->pontos;

                            //dd($pontuacao);
                            $primeiro->fill(['pontos' => $pontuacao]);
                            $primeiro->save();
                        }


                        $segundo = User::where('link', $primeiro->quem)->first();

                        if (!empty($segundo)) {

                            $planolast2 = $segundo->assinaturas->last();

                            if (!empty($planolast2)) {
                                if (count($aberta->user->assinaturas->where("status", 1)) > 1) {
                                    $extrato2 = [
                                        'user_id' => $segundo->id,
                                        'indicado_id' => $aberta->user->id,
                                        'pontos' => $plano->pontos,
                                        'saldo' => ($plano->valor - ($plano->valor * ((100 - $planolast2->plano->segundo) / 100)))
                                    ];

                                    $dados2 = [
                                        'tipo' => 0,
                                        'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Segundo Nivel ' . $plano->name,
                                        'valor' => ($plano->valor - ($plano->valor * ((100 - $planolast2->plano->segundo) / 100))),
                                        'user_id' => $segundo->id
                                    ];
                                } else {
                                    $extrato2 = [
                                        'user_id' => $segundo->id,
                                        'indicado_id' => $aberta->user->id,
                                        'pontos' => $plano->pontos,
                                        'saldo' => 0
                                    ];
                                    $dados2 = [
                                        'tipo' => 0,
                                        'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Segundo Nivel ' . $plano->name,
                                        'valor' => 0,
                                        'user_id' => $segundo->id
                                    ];
                                }


                                \App\Models\Movimento::create($dados2);

                                Extrato::create($extrato2);
                                $pontuacao = $segundo->pontos + $plano->pontos;

                                $segundo->fill(['pontos' => $pontuacao]);
                                $segundo->save();
                            }


                            $terceiro = User::where('link', $segundo->quem)->first();

                            if (!empty($terceiro)) {

                                $planolast3 = $terceiro->assinaturas->last();

                                if (!empty($planolast3)) {
                                    if (count($aberta->user->assinaturas->where("status", 1)) > 1) {
                                        $extrato3 = [
                                            'user_id' => $terceiro->id,
                                            'indicado_id' => $aberta->user->id,
                                            'pontos' => $plano->pontos,
                                            'saldo' => ($plano->valor - ($plano->valor * ((100 - $planolast3->plano->terceiro) / 100)))
                                        ];
                                        $dados3 = [
                                            'tipo' => 0,
                                            'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Terceiro Nivel ' . $plano->name,
                                            'valor' => ($plano->valor - ($plano->valor * ((100 - $planolast3->plano->terceiro) / 100))),
                                            'user_id' => $terceiro->id
                                        ];
                                    } else {
                                        $extrato3 = [
                                            'user_id' => $terceiro->id,
                                            'indicado_id' => $aberta->user->id,
                                            'pontos' => $plano->pontos,
                                            'saldo' => 0
                                        ];
                                        $dados3 = [
                                            'tipo' => 0,
                                            'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Terceiro Nivel ' . $plano->name,
                                            'valor' => 0,
                                            'user_id' => $terceiro->id
                                        ];
                                    }


                                    \App\Models\Movimento::create($dados3);
                                    Extrato::create($extrato3);
                                    $pontuacao = $terceiro->pontos + $plano->pontos;

                                    //dd($pontuacao);
                                    $terceiro->fill(['pontos' => $pontuacao]);
                                    $terceiro->save();
                                }
                            }
                        }
                    }
                } else {
                }
            };
        }
    }
});


Route::get('cliente/pagarcredito/{id}', function ($id) {
    // dd('oi');
    $assinatura = Credito::find($id);
    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');


    $cliente = $asaas->Cliente()->getByCpf(Auth::user()->cpf);

    // dd($cliente->data);

    if (!$cliente->data) {

        $dados = array(
            'name' => Auth::user()->name,
            'cpfCnpj' => Auth::user()->cpf,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->telefone,
            'mobilePhone' => Auth::user()->telefone,
            'address' => '',
            'addressNumber' => Auth::user()->endereco->n,
            'complement' => '',
            'province' => '',
            'postalCode' => Auth::user()->endereco->cep,
            'externalReference' => '',
            'notificationDisabled' => '',
            'additionalEmails' => ''
        );

        $cliente = $asaas->Cliente()->create($dados);

        $clientenovo = $cliente;
    } else {
        $clientenovo = $cliente->data[0]->id;
    }

    //dd($cliente);

    if ($assinatura->buscador == '') {


        $dadosCobranca = array(
            'customer' => $clientenovo,
            'billingType' => 'UNDEFINED',
            'value' => $assinatura->valor,
            'dueDate' => Carbon::now()->format('Y-m-d'),
            'description' => $assinatura->plano->name . ' ' . "Pagamento referente ao mês de " . Carbon::create($assinatura->inicio)->monthName,
            'externalReference' => '',
            'installmentCount' => '',
            'installmentValue' => '',
            'discount' => '',
            'interest' => '',
            'fine' => '',
        );


        $cobranca = $asaas->Cobranca()->create($dadosCobranca);
        //$LinkPagamento = $asaas->LinkPagamento()->create($dadosLink);
        //dd($cobranca);

        $assinatura->fill(['buscador' => $cobranca->id]);


        $assinatura->save();
    }

    $cobranca = $asaas->Cobranca()->getById($assinatura->buscador);

    //dd($cobranca);
    // $LinkPagamento = $asaas->LinkPagamento()->getById($assinatura->buscador);
    //dd($LinkPagamento);
    return redirect($cobranca->invoiceUrl);
    //dd($assinatura);
    //  dd($LinkPagamento);
});

Route::get("corrigeuser", function () {
    $users = User::all();

    foreach ($users as $user) {
        $user->fill(['cpf' => preg_replace("/[^0-9]/", "", $user->cpf)]);
        $user->save();
    }
});

Route::get('vaidesgraca', function () {
    $assinaturas = Assinatura::where("status", '1')->get();

    foreach ($assinaturas as $assinatura) {
        $assinatura->fill(['tipo' => 'CREDIT_CARD']);
        $assinatura->save();
    }
});


Route::get("testenivel", function () {
    dd(Auth::user()->terceiro());
});

Route::get("admin/produtos", function () {
    $produtos = Produto::all();
    return view('admin.produtos.index', compact('produtos'));
})->middleware(['auth']);
Route::get('admin/produtos/create', function () {
    return view('admin.produtos.create');
})->middleware(['auth']);


Route::post('admin/produtos/create', function (Request $request) {

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'ordem' => ['required'],
        'descricao' => ['required'],
        'youtube' => ['required']
    ]);


    $produto = Produto::create($request->all());

    return redirect(url('admin/produto/cadfoto', $produto->id));
});
Route::post('admin/produtos/edit', function (Request $request) {

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'ordem' => ['required'],
        'descricao' => ['required'],
        'youtube' => ['required']
    ]);


    $produto = Produto::find($request->id);
    $produto->fill($request->all());
    $produto->save();

    return redirect(url('admin/produto/cadfoto', $produto->id));
});

Route::get('admin/produto/cadfoto/{id}', function ($id) {
    $produto = Produto::find($id);

    return view('admin.produtos.cadfoto', compact('produto'));
});
Route::get('admin/plano/cadfoto/{id}', function ($id) {
    $produto = Plano::find($id);

    return view('painel.plano.cadfoto', compact('produto'));
});
Route::get('admin/produto/edit/{id}', function ($id) {
    $produto = Produto::find($id);

    return view('admin.produtos.edit', compact('produto'));
});

Route::get('admin/produto/caddoc/{id}', function ($id) {
    $produto = Produto::find($id);

    return view('admin.produtos.caddoc', compact('produto'));
});

Route::get('admin/produto/delete/{id}', function ($id) {
    $produto = Produto::find($id);

    //dd($produto);

    if (!empty($produto->img)) {
        unlink('arquivos/produtos/' . $produto->img);
    }
    if (!empty($produto->arquivo)) {
        unlink('arquivos/produtos/doc/' . $produto->arquivo);
    }

    Produto::destroy($produto->id);


    return redirect()->back();
});

Route::post('registerindicadoland', function (Request $request) {
    // dd($request->all());
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed'],
        'cpf' => ['cpf', 'required', 'unique:users'],
        'telefone' => ['required'],
        'nascimento' => ['required', 'date'],
        'cep' => ['required'],
        'endereco' => ['required'],
        'bairro' => ['required'],
        'cidade' => ['required'],
        'uf' => ['required'],
        'n' => ['required'],
        'aceite' => ['required'],
        'plano_id' => ['required'],
        'modalidade' => ['required'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'cpf' => preg_replace("/[^0-9]/", "", $request->cpf),
        'telefone' => $request->telefone,
        'link' => md5($request->cpf),
        'quem' => $request->quem,
        'nascimento' => $request->nascimento

    ]);

    $estado = \App\Models\Estado::where("name", $request->uf)->first();


    if (!($estado)) {
        $estado = \App\Models\Estado::create(['name' => $request->uf]);
    }
    $cidade = \App\Models\Cidade::where("name", $request->cidade)->where("estado_id", $estado->id)->first();

    if (!($cidade)) {
        $cidade = \App\Models\Cidade::create(['name' => $request->cidade, 'estado_id' => $estado->id]);
    }

    $bairro = \App\Models\Bairro::where('name', $request->bairro)->where("estado_id", $estado->id)->where("cidade_id", $cidade->id)->first();

    //dd($bairro);
    if (!($bairro)) {
        $bairro = \App\Models\Bairro::create(
            [
                'name' => $request->bairro,
                'estado_id' => $estado->id,
                'cidade_id' => $cidade->id
            ]
        );
    }

    $grava = [
        'cep' => $request->cep,
        'endereco' => $request->endereco,
        'bairro_id' => $bairro->id,
        'user_id' => $user->id,
        'cidade_id' => $cidade->id,
        'estado_id' => $estado->id,
        'n' => $request->n,
        'complemento' => $request->complemento
    ];

    \App\Models\Endereco::create($grava);

    if ($request->modalidade == 1) {
        return redirect(url('gerarfaturamensal/' . $user->id . '/plano/' . $request->plano_id));
    }
    if ($request->modalidade == 2) {
        return redirect(url('gerarfaturaunica/' . $user->id . '/plano/' . $request->plano_id));
    }
});

Route::get('gerarfaturamensal/{user_id}/plano/{plano_id}', function ($user_id, $plano_id) {
    //dd($plano_id);

    $plano = Plano::find($plano_id);

    //dd(count(Auth::user()->assinaturas));


    //dd($plano);

    $hoje = Carbon::now();

    $controle = [];
    $data = Carbon::now();
    $fim = Carbon::now()->addDays(30);
    $dados = [
        'inicio' => $data,
        'fim' => $fim,
        'status' => 0,
        'plano_id' => $plano_id,
        'user_id' => $user_id,
        'valor' => $plano->valor
    ];

    $salva = Assinatura::create($dados);

    for ($i = 1; $i <= 11; $i++) {
        //echo $i . "<br>";


        $datas = Assinatura::where('user_id', $user_id)->orderBy('fim', 'desc')->first();
        //dd($datas);
        $grava2 = [
            'inicio' => $datas->fim,
            'fim' => Carbon::create($datas->fim)->addDays((30 * 1)),
            'status' => 0,
            'plano_id' => $plano_id,
            'user_id' => $user_id,
            'valor' => $plano->valor
        ];


        //  $controle[] = $grava2;
        Assinatura::create($grava2);
    }

    return redirect(url('gerarpagamentomensal', $user_id));
});
Route::get('gerarfaturaunica/{user_id}/plano/{plano_id}', function ($user_id, $plano_id) {
    $plano = Plano::find($plano_id);

    //dd(count(Auth::user()->assinaturas));


    //dd($plano);

    $hoje = Carbon::now();

    $controle = [];
    $data = Carbon::now();
    $fim = Carbon::now()->addDays(30);
    $dados = [
        'inicio' => $data,
        'fim' => $fim,
        'status' => 0,
        'plano_id' => $plano_id,
        'user_id' => $user_id,
        'valor' => $plano->valor
    ];

    $salva = Assinatura::create($dados);

    for ($i = 1; $i <= 11; $i++) {
        //echo $i . "<br>";


        $datas = Assinatura::where('user_id', $user_id)->orderBy('fim', 'desc')->first();
        //dd($datas);
        $grava2 = [
            'inicio' => $datas->fim,
            'fim' => Carbon::create($datas->fim)->addDays((30 * 1)),
            'status' => 0,
            'plano_id' => $plano_id,
            'user_id' => $user_id,
            'valor' => $plano->valor
        ];


        //  $controle[] = $grava2;
        Assinatura::create($grava2);
    }

    return redirect(url('gerarpagamentoanual', $user_id));
});

Route::get('gerarpagamentomensal/{id}', function ($id) {
    $user = User::find($id);
    $busca = $user->assinaturas()->first();
    $assinatura = Assinatura::find($busca->id);
    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');


    $cliente = $asaas->Cliente()->getByCpf($user->cpf);

    // dd($cliente->data);

    if (!$cliente->data) {

        $dados = array(
            'name' => $user->name,
            'cpfCnpj' => $user->cpf,
            'email' => $user->email,
            'phone' => $user->telefone,
            'mobilePhone' => $user->telefone,
            'address' => '',
            'addressNumber' => $user->endereco->n,
            'complement' => '',
            'province' => '',
            'postalCode' => $user->endereco->cep,
            'externalReference' => '',
            'notificationDisabled' => '',
            'additionalEmails' => ''
        );

        $cliente = $asaas->Cliente()->create($dados);

        $clientenovo = $cliente;
    } else {
        $clientenovo = $cliente->data[0]->id;
    }

    //dd($cliente);

    if ($assinatura->buscador == '') {


        $dadosCobranca = array(
            'customer' => $clientenovo,
            'billingType' => 'UNDEFINED',
            'value' => $assinatura->valor,
            'dueDate' => Carbon::now()->format('Y-m-d'),
            'description' => $assinatura->plano->name . ' ' . "Pagamento referente ao mês de " . Carbon::create($assinatura->inicio)->monthName,
            'externalReference' => '',
            'installmentCount' => '',
            'installmentValue' => '',
            'discount' => '',
            'interest' => '',
            'fine' => '',
        );


        $cobranca = $asaas->Cobranca()->create($dadosCobranca);
        //$LinkPagamento = $asaas->LinkPagamento()->create($dadosLink);
        //dd($cobranca);

        $assinatura->fill(['buscador' => $cobranca->id]);


        $assinatura->save();
    }

    $cobranca = $asaas->Cobranca()->getById($assinatura->buscador);

    //dd($cobranca);
    // $LinkPagamento = $asaas->LinkPagamento()->getById($assinatura->buscador);
    //dd($LinkPagamento);
    return redirect($cobranca->invoiceUrl);
});
Route::get('gerarpagamentoanual/{id}', function ($id) {
    $user = User::find($id);
    // $busca = $user->assinaturas()->first();
    // $assinatura = Assinatura::find($busca->id);
    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');


    $cliente = $asaas->Cliente()->getByCpf($user->cpf);

    // dd($cliente->data);

    if (!$cliente->data) {

        $dados = array(
            'name' => $user->name,
            'cpfCnpj' => $user->cpf,
            'email' => $user->email,
            'phone' => $user->telefone,
            'mobilePhone' => $user->telefone,
            'address' => '',
            'addressNumber' => $user->endereco->n,
            'complement' => '',
            'province' => '',
            'postalCode' => $user->endereco->cep,
            'externalReference' => '',
            'notificationDisabled' => '',
            'additionalEmails' => ''
        );

        $cliente = $asaas->Cliente()->create($dados);

        $clientenovo = $cliente;
    } else {
        $clientenovo = $cliente->data[0]->id;
    }
    $saldo = $user->creditos->sum('valor');
    $emaberto = $user->abertas()->sum('valor');
    //dd($emaberto);

    $busca = Credito::where("user_id", $user->id)->where('status', 0)->first();
    $valor = count(\App\Models\Assinatura::where('user_id', $user->id)->where('status', 0)->get()) * $user->assinaturas->last()->plano->valor;
    $meses = count(\App\Models\Assinatura::where('user_id', $user->id)->where('status', 0)->get());
    $ultimo = $user->assinaturas->last();
    //dd($ultimo);
    // dd($ultimo);

    if (!$busca) {
        //dd($emaberto);

        /*    $grava2 = [
            'inicio' => $ultimo->fim,
            'fim' => Carbon::create($ultimo->fim)->addMonth($meses),
            'status' => 0,
            'plano_id' => $ultimo->plano_id,
            'user_id' => $user->id,
            'unico' => 1,
            'valor' => $valor
        ]; */
        if ($saldo > 0) {
            $grava2 = [
                'valor' => $emaberto,
                'user_id' => $user->id,
                'plano_id' => $ultimo->plano->id,

            ];
        } else {
            $grava2 = [
                'valor' => $valor,
                'user_id' => $user->id,
                'plano_id' => $ultimo->plano->id,

            ];
        }


        $busca = Credito::create($grava2);
    }


    if ($busca->buscador == '') {


        $dadosCobranca = array(
            'customer' => $clientenovo,
            'billingType' => 'UNDEFINED',
            'value' => $emaberto,
            'dueDate' => Carbon::now()->format('Y-m-d'),
            'description' => $busca->user->name . ' ' . "Pagamento Unico",
            'externalReference' => '',
            'installmentCount' => '',
            'installmentValue' => '',
            'discount' => '',
            'interest' => '',
            'fine' => '',
        );


        $cobranca = $asaas->Cobranca()->create($dadosCobranca);
        //$LinkPagamento = $asaas->LinkPagamento()->create($dadosLink);
        //dd($cobranca);

        $busca->fill(['buscador' => $cobranca->id]);


        $busca->save();
    }

    //dd($busca);
    $cobranca = $asaas->Cobranca()->getById($busca->buscador);

    //dd($cobranca);
    // $LinkPagamento = $asaas->LinkPagamento()->getById($assinatura->buscador);
    //dd($LinkPagamento);
    return redirect($cobranca->invoiceUrl);
});

Route::get('admin/ver/planos/{id}', function ($id) {
    $agora = Carbon::now();
    $plano = Plano::find($id);
    $assinaturas = Compra::where("ativo", 1)->whereMonth('created_at', $agora)->where('plano_id', $plano->id)->get();
    return view('admin.relatorio.visualizar', compact('plano', 'assinaturas'));
})->middleware(['auth']);

Route::get('cancelship/{id}', function ($id) {

    $compra = Compra::where('id', $id)->where('user_id', Auth::user()->id)->first();

    if (isset($compra)) {

        Compra::destroy($compra->id);

        return redirect(url('customer/invoices'));
    }
    return redirect(url('customer/invoices'));
})->middleware(['auth']);


Route::get('getupship/{id}', function ($id, \App\Services\SaldoService $saldoService) {
    return redirect()->back()->with('Error', 'Sua corrida começará no próximo dia útil');




    $compra = Compra::find($id);

   // dd($compra);

    if (count($compra->rendimentos) == 0){
//dd('oi');
        if ($compra->updated_at->addDay() <= \Carbon\Carbon::now()){
            $busca = [
                'user_id' => Auth::user()->id,
                'plano_id' => $compra->plano->id,
                'compra_id' => $compra->id,
            ];



            if (count($compra->rendimentos) != 5) {

                $rentabilidade = $compra->plano->valor * 0.10;

                $dados = [
                    'tipo' => 0,
                    'descricao' => "Redimento de 10% do carro " . $compra->plano->name,
                    'valor' => $rentabilidade,
                    'user_id' => Auth::user()->id,
                    'compra_id'=>$compra->id
                ];


                \App\Models\Valorredimento::create($dados);
                Batalha::create($busca);
                $saldoService->rendimento($compra->saldoRaiz);


            }
            $compra2 = Compra::find($compra->id);
            if (count($compra2->rendimentos) == 5) {
                $compra->update(['status' => 2]);
            }
        }else{
            return redirect()->back()->with('Error', 'Sua corrida começará no próximo dia útil');
        }

    }else{


        if($compra->rendimentos->last()->created_at->diffInHours() < 24){
            //dd($compra->rendimentos->last()->created_at->diffInHours());
            return redirect()->back()->with('Error', 'Sua corrida começará no próximo dia útil');

        } else{

            $busca = [
                'user_id' => Auth::user()->id,
                'plano_id' => $compra->plano->id,
                'compra_id' => $compra->id,
            ];


            if (count($compra->rendimentos) != 5) {

                $rentabilidade = $compra->plano->valor * 0.10;

                $dados = [
                    'tipo' => 0,
                    'descricao' => "Redimento de 10% do carro " . $compra->plano->name,
                    'valor' => $rentabilidade,
                    'user_id' => Auth::user()->id,
                    'compra_id'=>$compra->id
                ];


                \App\Models\Valorredimento::create($dados);
                Batalha::create($busca);
                $saldoService->rendimento($compra->saldoRaiz);


            }
            $compra2 = Compra::find($compra->id);
            if (count($compra2->rendimentos) == 5) {
                $compra->update(['status' => 2]);
            }

        }
    }
    //dd($compra->rendimentos);


    return redirect()->back();
})->middleware(['auth']);


Route::get('testepagamentoblock', function () {

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.nowpayments.io/v1/payment',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
  "price_amount": 3999.5,
  "price_currency": "usd",
  "pay_currency": "eth",
  "ipn_callback_url": "https://nowpayments.io",
  "order_id": "RGDBP-21314",
  "order_description": "Apple Macbook Pro 2019 x 1"
}',
        CURLOPT_HTTPHEADER => array(
            'x-api-key: AACR78H-2S2MB2Z-PGM8VX1-QTFH0C8',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    dd($response);

    curl_close($curl);
    echo $response;
});


Route::get('player/payment/{id}', function ($id) {

    $compra = Compra::find($id);


    return view('compra.index', compact(
        'compra',

    ));
})->middleware(['auth']);

Route::post('payment/origin', function (Request $request) {
    $compra = Compra::find($request->compra_id);
    $busca = $compra->registracompra($request->moeda, $compra->id);

    //dd($busca);

    $grava = [
        'pay_address' => $busca['pay_address'],
        'purchase_id' => $busca['purchase_id'],
        'pay_amount' => $busca['pay_amount'],
        'moeda' => $busca['pay_currency'],
        'payment_id' => $busca['payment_id']

    ];

    $compra->fill($grava);

    $compra->save();


    return redirect(url('final/ship', $compra->id));
    // dd($compra);
})->middleware(['auth']);

Route::get('gerarpix/{id}', function ($id, \App\Services\PaymentServices $paymentServices) {

    $compra = Compra::find($id);

//dd($payment);
    if (!$compra->asaas_link) {

        $custumer_id = ($paymentServices->verifyCustumer(Auth::user()));

        // dd($custumer_id);

        //  dd($compra);
        $payment = $paymentServices->createPaymentBoleto($custumer_id, $compra);

        // dd($payment);


        $compra->fill(['asaas_link' => $payment->invoiceUrl]);
        $compra->fill(['buscador' => $payment->id]);

        $compra->save();

    }


    return redirect($compra->asaas_link);

});

Route::get('geraroix2/{id}', function ($id, \App\Services\PaymentServices $paymentServices) {

    $compra = Compra::find($id);

//dd($payment);
    if (!$compra->asaas_link) {

        $custumer_id = ($paymentServices->verifyCustumer(Auth::user()));

        // dd($custumer_id);

        //  dd($compra);
        $payment = $paymentServices->createPaymentBoleto($custumer_id, $compra);

        // dd($payment);


        $compra->fill(['asaas_link' => $payment->invoiceUrl]);
        $compra->fill(['buscador' => $payment->id]);

        $compra->save();

    }


    $pix = $paymentServices->createPaymentPix($compra);


    //inde dd($pix);

    //return redirect($compra->asaas_link);

    return view('cliente.faturas.pix', compact('pix'));

});


Route::get('final/ship/{id}', function ($id) {
    $compra = Compra::find($id);
    return view('admin.block.index', compact('compra'));
})->middleware(['auth']);


Route::get('player/chat', function () {
    //$novo  = Chat::orderByDesc
    $chats = Chat::where('user_id', Auth::user()->id)->orderByDesc('created_at')->get();
    return view('chat.index', compact('chats'));
})->middleware(['auth']);
Route::get('player/chat2', function () {

    $chats = Chat::where('user_id', Auth::user()->id)->get();
    return view('chat.index2', compact('chats'));
})->middleware(['auth']);
Route::post('newchat', function (Request $request) {
    $request->validate([
        'message' => ['required', 'min:20'],


    ]);

    $request['user_id'] = Auth::user()->id;
    Chat::create($request->all());

    return redirect()->back();
});
Route::post('admin/newchat', function (Request $request) {
    $request->validate([
        'message' => ['required', 'min:20'],
        'user_id' => ['required']


    ]);

    //$request['user_id'] = ;
    Chat::create($request->all());

    return redirect()->back();
});

Route::get('reply/{id}', function ($id) {
    $chat = Chat::find($id);

    if (isset($chat->respostas)) {

        foreach ($chat->respostas->where('user_id', '!=', Auth::user()->id) as $resposta) {
            $resposta->fill(['visto' => 1]);
            $resposta->save();
        }
    }
    return view('chat.responder', compact('chat'));
});

Route::post('suport/reply', function (Request $request) {
    $request->validate([
        'message' => ['required'],

    ]);
    $request['user_id'] = Auth::user()->id;

    Resposta::create($request->all());

    return redirect()->back()
        ->with('success', 'Reply');
})->middleware(['auth']);


/*  Route::get('novoteste', function (AcaoController $acaoController) {

    // $user = User::find(3);
    //dd($user->segundo());
    $faturas = Compra::where('ativo', 0)->orderBy('id', 'asc')->where('pay_address', "!=", 'NULL')->get();

    //dd($faturas);

    foreach ($faturas as $fatura) {
        //dd($fatura);



        $cobranca = $fatura->consultahash();

        if ($cobranca['payment_status'] == "finished") {
            $fatura->fill(['ativo' => 1]);
            $fatura->save();
            if (!empty($fatura->user->quarto())) {
                $direto = $acaoController::calculorenda($fatura, 4);
            }
            if (!empty($fatura->user->terceiro())) {
                $direto = $acaoController::calculorenda($fatura, 3);
            }
            if (!empty($fatura->user->segundo())) {
                $direto = $acaoController::calculorenda($fatura, 2);
            }
            if (!empty($fatura->user->primeiro())) {
                $direto = $acaoController::calculorenda($fatura, 1);
            }



            if (!empty($fatura->user->direto())) {
                $direto = $acaoController::calculorenda($fatura, 0);
            }
            //  return $direto;

        }
    }
})->middleware(['auth']); */

Route::post('cadvideo', function (Request $request) {

    $request->validate([
        'video' => ['required', 'url'],

    ]);


    $request['user_id'] = Auth::user()->id;


    Video::create($request->all());

    return redirect()->back();
})->middleware(['auth']);


Route::get('admin/video', function () {
    $videos = Video::where('valido', 0)->get();
    return view('video', compact('videos'));
})->middleware(['auth']);

Route::get('admin/validar/{id}', function ($id) {
    $video = Video::find($id);
    $dados = [
        'tipo' => 0,
        'descricao' => 'bonus ref. video ' . $video->video,
        'valor' => 2.5,
        'user_id' => $video->user_id,
    ];
    //dd($dados);


    \App\Models\Movimento::create($dados);

    $video->fill(['valido' => 1]);
    $video->save();

    return redirect()->back();
})->middleware(['auth']);
Route::get('admin/invalidar/{id}', function ($id) {
    $video = Video::find($id);


    $video->fill(['valido' => 2]);
    $video->save();

    return redirect()->back();
})->middleware(['auth']);


Route::get('corrigetudo', function () {
    //$faturas = Compra::where('ativo', 0)->orderBy('id', 'asc')->get();
    $faturas = Compra::all();
    foreach ($faturas as $fatura) {
        /*  $fatura->fill([
            'pay_address' => 'TNPuQvFnWPJskiPjC2ig2pRrB9Z565VmWp',
            'purchase_id' => '5153901902',
            'pay_amount' => 32.217209,
            'moeda' => 'trx',
            'payment_id' => '6187445628'
        ]);*/

        $fatura->fill(['ativo' => 0]);

        $fatura->save();
    }
});


Route::get('gerarsaque', function () {
    $users = User::all();
    // $valor = Auth::user()->sobra;


    //dd($pontuacao);


    foreach ($users as $user) {

        if ($user->sobra > 2) {
            $valores =
                [
                    'valor' => $user->sobra,
                    'status' => 0,
                    'user_id' => Auth::user()->id
                ];


            $dados = [
                'tipo' => 1,
                'descricao' => 'withdrawal ref. bonus',
                'valor' => $user->sobra,
                'user_id' => Auth::user()->id
            ];
            \App\Models\Saque::create($valores);
            \App\Models\Movimento::create($dados);
        }
    }
});

Route::get('contultahash/{id}', function ($id) {

    $compra = Compra::find($id);


    dd($compra->consultahash2());
});


Route::get('error404', function () {
    return view('error404');
});
Route::get('compracortesia', function () {
    $cortesia = Compra::where('');
});

Route::get('concertavalores', function () {
    $users = User::has('movimentos')->get();

    // dd($users);

    foreach ($users as $user) {
        $buscas = Movimento::where("user_id", $user->id)->where('descricao', 'like', "%reward%")->get();
        //dd($buscas);

        foreach ($buscas as $busca) {
            $grava = [
                'descricao' => $busca->descricao,
                'valor' => $busca->valor,
                'tipo' => $busca->tipo,
                'user_id' => $busca->user_id

            ];
            Valorredimento::create($grava);
        }
    };
});

Route::get('testevaloressaque', function () {
    $rendimentos = Valorredimento::where('valor', '<', 0)->get();
    $bonifica = Valorindicacao::where('valor', '<', 0)->get();

    //dd($rendimentos);

    foreach ($rendimentos as $rendimento) {
        $grava = [
            'valor' => -$rendimento->valor,
            'data' => $rendimento->created_at,
            'user_id' => $rendimento->user_id,
        ];

        Saquerendimento::create($grava);
    }
});

Route::get('geratudo', function (AcaoController $acaoController) {
    $compras = Compra::whereIn('id', [108, 109, 110])->get();
    //dd($compras);

    foreach ($compras as $fatura) {
        //dd($fatura);


        // $cobranca = $fatura->consultahash();


        $fatura->fill(['ativo' => 1]);
        $fatura->save();
        $details = [
            'title' => 'Corfirm Payment',
            'url' => url('dashboard')
        ];

        Mail::to($fatura->user->email)->send(new PaymentMail($details));
        $grava = [
            'descricao' => 'Recebido da mensalidade do ' . $fatura->user->name,
            'valor' => $fatura->plano->valor,
            'tipo' => 1,
            'user_id' => $fatura->user->id,
        ];

        Caixa::create($grava);

        if (!empty($fatura->user->quarto())) {
            $direto = $acaoController::calculorenda($fatura, 4);
        }
        if (!empty($fatura->user->terceiro())) {
            $direto = $acaoController::calculorenda($fatura, 3);
        }
        if (!empty($fatura->user->segundo())) {
            $direto = $acaoController::calculorenda($fatura, 2);
        }
        if (!empty($fatura->user->primeiro())) {
            $direto = $acaoController::calculorenda($fatura, 1);
        }
        if (!empty($fatura->user->direto())) {
            $direto = $acaoController::calculorenda($fatura, 0);
        }
        //  return $direto;


    }
});

Route::get('admin/ativamanual/{compra}', function (Compra $compra, \App\Services\SaldoService $saldoService, AcaoController $acaoController, \App\Services\CalendarService $calendarService) {
    $compra->fill([
        'tipo' => '$cobranca->billingType',
        'status' => 1,
        'ativo' => 1,
        'dia_pagamento' => Carbon::now(),
        'valor' => $compra->plano->valor

    ]);
    $compra->save();



    $fatura = $compra;


    $buscar = $calendarService->validaDia($fatura->dia_pagamento);


    if ($buscar['respota'] == true) {
        $nova = $calendarService->validaDia($buscar['data']);

        if ($nova['respota'] == true) {
            $nova = $calendarService->validaDia($nova['data']);
            if ($nova['respota'] == true) {

            } else {
                $novadata = $nova['data'];
            }
        } else {
            $novadata = $nova['data'];
        }
    } else {
        $novadata = $buscar['data'];
    };


    $today = Carbon::parse($novadata);

    //dd($today->dayOfWeek)'
    if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY) {
        $today = $today->addDay();

        if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY) {

            $today = $today->addDay();
            if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY) {

            } else {
                $novadata = $today;
            }

        } else {
            $novadata = $today;
        }

    } else {
        $novadata = $today;
    }

    $fatura->update(['primeiro_rendimento' => $novadata]);



    $saldoService->createSaldoRaiz($compra);


    $grava = [
        'descricao' => 'Recebido da mensalidade do ' . $compra->user->name,
        'valor' => $compra->plano->valor,
        'tipo' => 1,
        'user_id' => $compra->user->id,
    ];
    Caixa::create($grava);

    if (!empty($compra->user->terceiro())) {
        $direto = $acaoController->calculorenda($compra, 3);
    }
    if (!empty($compra->user->segundo())) {
        $direto = $acaoController->calculorenda($compra, 2);
    }
    if (!empty($compra->user->primeiro())) {

        //dd($fatura->user->primeiro());

        $direto = $acaoController->calculorenda($compra, 1);
    }


    if ($compra->plano->id == 5 ||$compra->plano->id == 6||$compra->plano->id == 7 || $compra->plano->id == 8||$compra->plano->id == 9  ){
        $user = $compra->user;
        $add = $user->ordem + 1;
        $user->update(['ordem'=>$add]);
        $user->update(['ordem'=>$add]);
    }

    return redirect()->back()->with('success', 'Ativado Com sucesso');
});


Route::get('atualizarfaturas', function (AcaoController $acaoController, \App\Services\SaldoService $saldoService) {
    $faturas = Compra::where('buscador', "!=", 'NULL')->where('status', 0)->get();


    // dd($faturas);

    foreach ($faturas as $fatura) {
        $asaas = new \CodePhix\Asaas\Asaas('$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAyMTc4NzA6OiRhYWNoXzRmOWRmMjE4LTMzMGQtNDc1OC04ODFlLTA0YTU1NTA4ZDMyOQ==', 'production');
        $cobranca = $asaas->Cobranca()->getById($fatura->buscador);

        //dd($cobranca);

        if ($cobranca->status == 'CONFIRMED' || $cobranca->status == 'RECEIVED') {

            //dd($fatura);


            if ($fatura->status == 0) {
                $fatura->fill(
                    [
                        'tipo' => $cobranca->billingType,
                        'status' => 1,
                        'ativo' => 1,
                        'dia_pagamento' => Carbon::now(),
                        'valor' => $fatura->plano->valor

                    ]);
                $fatura->save();


                $saldoService->createSaldoRaiz($fatura);


                $grava = [
                    'descricao' => 'Recebido da mensalidade do ' . $fatura->user->name,
                    'valor' => $fatura->plano->valor,
                    'tipo' => 1,
                    'user_id' => Auth::user()->id,
                ];
                Caixa::create($grava);
            }

            if (!empty($fatura->user->terceiro())) {
                $direto = $acaoController->calculorenda($fatura, 3);
            }
            if (!empty($fatura->user->segundo())) {
                $direto = $acaoController->calculorenda($fatura, 2);
            }
            if (!empty($fatura->user->primeiro())) {

                //dd($fatura->user->primeiro());

                $direto = $acaoController->calculorenda($fatura, 1);
            }


        }
        // dd($cobranca);
        //dd($fatura);
    }

    // dd($faturas);
});


Route::get('sacarrendimento/{id}', function ($id) {
    $fatura = Compra::find($id);

    return view('cliente.saque.rendimento', compact('fatura'));
});

Route::post('saquerendimento', function (Request $request, \App\Services\SaldoService $saldoService) {
    return redirect()->back()->with('Error', 'Saque disponível somente em dias úteis');
    if ($request->valor < 10) {
        return redirect()->back()->with('error', 'O valor mínimo necessário é de R$10,00');
    }

    if ($request->meio_saque == null) {
        return redirect()->back()->with('error', 'É preciso informar um meio de saque');
    }

    $compra = Compra::find($request->compra_id);

    $saldoService->saqueRendimento($compra->saldoRaiz->saldoRendimento);


    $grava = [
        'descricao' => 'Saque de rendimento no valor de R$' . $request->valor . ',00',
        'valor' => $request->valor,
        'tipo' => 2,
        'user_id' => Auth::user()->id,
    ];
    Caixa::create($grava);

    Saquerendimento::create([
        'valor' => $request->valor,
        'data' => Carbon::now(),
        'status' => 0,
        'user_id' => Auth::user()->id,
        'meio_saque' => $request->meio_saque,
    ]);

    return redirect()->back()->with('success', 'Saque de rendimento solicitado com sucesso');
});

Route::get('sacarraiz/{compra}', function (Compra $compra, \App\Services\CalendarService $calendarService) {
    $fatura = $compra;
    $agora = Carbon::now();

    $resposta = ($calendarService->validaDia($agora)['respota']);

    return view('cliente.saque.raiz', compact('fatura','resposta'));
});

Route::post('saqueraiz', function (Request $request, \App\Services\SaldoService $saldoService) {
    return redirect()->back()->with('Error', 'Saque disponível somente em dias úteis');
    if ($request->valor == 0) {
        return redirect()->back()->with('error', 'Saldo indisponível para saque');
    }

    if ($request->meio_saque == null) {
        return redirect()->back()->with('error', 'É preciso informar um meio de saque');
    }

    $compra = Compra::find($request->compra_id);

    $saldoService->saqueRaiz($compra->saldoRaiz);


    $grava = [
        'descricao' => 'Saque de raiz no valor de R$' . $request->valor . ',00',
        'valor' => $request->valor,
        'tipo' => 2,
        'user_id' => Auth::user()->id,
    ];
    Caixa::create($grava);

    \App\Models\Saqueraiz::create([
        'valor' => $request->valor,
        'data' => Carbon::now(),
        'status' => 0,
        'user_id' => Auth::user()->id,
        'meio_saque' => $request->meio_saque,
    ]);


    return redirect()->back()->with('success', 'Saque de raiz solicitado com sucesso');
});

Route::get('cancelar/{compra}', function (Compra $compra, \App\Services\SaldoService $saldoService) {
    $fatura = $compra;
    $valor = $saldoService->valorCancelamento($fatura->saldoRaiz);
    if ($valor < 0) $valor = 0;

    return view('cliente.saque.cancelamento', compact('fatura', 'valor'));
});

Route::post('cancelarraiz', function (Request $request, \App\Services\SaldoService $saldoService) {
    return redirect()->back()->with('Error', 'Saque disponível somente em dias úteis');
    if ($request->valor == 0) {
        return redirect()->back()->with('Error', 'Saldo indisponível para saque');
    }

    if ($request->meio_saque == null) {
        return redirect()->back()->with('Error', 'É preciso informar um meio de saque');
    }

    $compra = Compra::find($request->compra_id);

    $saldoService->cancelamento($compra->saldoRaiz);


    $grava = [
        'descricao' => 'Saque de cancelamento no valor de R$' . $request->valor . ',00',
        'valor' => $request->valor,
        'tipo' => 2,
        'user_id' => Auth::user()->id,
    ];
    Caixa::create($grava);

    \App\Models\Saquecancelamento::create([
        'valor' => $request->valor,
        'data' => Carbon::now(),
        'status' => 0,
        'user_id' => Auth::user()->id,
        'meio_saque' => $request->meio_saque,
    ]);


    return redirect()->back()->with('success', 'Saque de cancelamento solicitado com sucesso');
});


Route::post('user/pin/{user}', function (User $user, Request $request) {
    $pin = bcrypt($request->pin);
    $user->update(['pin' => $pin]);
});

Route::get('novoadm', function () {
    \App\Models\UserAdmin::create(
        ['name' => 'gustavo',
            'login' => 'gus17',
            'email' => 'g@g.com',
            'password' => bcrypt('36110312')
        ]

    );
});

Route::get('todasfaturas', function (\App\Services\CalendarService $calendarService) {
    $faturas = Compra::where('status', 1)->get();


    foreach ($faturas as $fatura) {
        // dd($fatura);
        $buscar = $calendarService->validaDia($fatura->dia_pagamento);


        if ($buscar['respota'] == true) {
            $nova = $calendarService->validaDia($buscar['data']);

            if ($nova['respota'] == true) {
                $nova = $calendarService->validaDia($nova['data']);
                if ($nova['respota'] == true) {

                } else {
                    $novadata = $nova['data'];
                }
            } else {
                $novadata = $nova['data'];
            }
        } else {
            $novadata = $buscar['data'];
        };


        $today = Carbon::parse($novadata);

        //dd($today->dayOfWeek)'
        if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY) {
            $today = $today->addDay();

            if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY) {

                $today = $today->addDay();
                if ($today->dayOfWeek == \Carbon\Carbon::SUNDAY || $today->dayOfWeek == \Carbon\Carbon::SATURDAY) {

                } else {
                    $novadata = $today;
                }

            } else {
                $novadata = $today;
            }

        } else {
            $novadata = $today;
        }

        $fatura->update(['primeiro_rendimento' => $novadata]);

    }


});

Route::get('buscauser/{id}',function ($id){
    $user = User::find($id);
    //dd($user);
    dd($user->valorindicacos->toArray());

    $valorindica = Valorindicacao::find(117);

    //dd($valorindica->delete());

});


Route::post('cadatrapin',function (Request $request){
    $request->validate([
        'pin' => 'required|max:6',


    ]);


    Auth::user()->update(['pin'=>bcrypt($request->pin)]);

    return redirect()->back()->with('success','PIN cadastrado com sucesso');
});

require __DIR__ . '/auth.php';
