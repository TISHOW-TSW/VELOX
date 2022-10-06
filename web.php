<?php

use App\Helpers\LogActivity as HelpersLogActivity;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\VantagemController;
use App\Models\Anexo;
use App\Models\Assinatura;
use App\Models\Caixa;
use App\Models\Conta;
use App\Models\Credito;
use App\Models\Doc;
use App\Models\Endereco;
use App\Models\Estado;
use App\Models\Extra;
use App\Models\Extrato;
use App\Models\Historico;
use App\Models\Interesse;
use App\Models\LogActivity;
use App\Models\Meta;
use App\Models\Plano;
use App\Models\Premio;
use App\Models\Produto;
use App\Models\Relatorio;
use App\Models\Saque;
use App\Models\Treinamento;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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
    $planos = Plano::orderBy('valor', 'asc')->get();
    $indicados = User::where('quem', Auth::user()->link)->take(10)->get();
    $extras = Extra::all();
    $users = User::withCount('indicados')->where('quem', Auth::user()->link)->orderByDesc('indicados_count')->limit(10)->get();

    if (Auth::user()->ordem == 12) {
        $nivel = Auth::user()->ordem;
        $pin = Meta::where("ordem", $nivel)->first();
    } else {
        $nivel = Auth::user()->ordem;
        $pin = Meta::where("ordem", $nivel)->first();
        $busca = Auth::user()->ordem + 1;
        $proximo = Meta::where("ordem", $busca)->first();

        //dd($pin);
    }
    if (Auth::user()->tipo != 0) {
        return redirect(url('admin/dashboard'));
    }

    return view('painel.index', compact('planos', 'pin', 'indicados', 'users', 'proximo', 'extras'));
})->middleware(['auth'])->name('dashboard');

Route::get('teste', function () {
    $planos = Plano::all();
    return view('painel.index', compact('planos'));
});

Route::get('admin/usuarios', [AdminController::class, 'users'])->middleware(['auth']);
Route::get('admin/usuarios/ativos', [AdminController::class, 'ativos'])->middleware(['auth']);
Route::get('admin/usuarios/pendentes', [AdminController::class, 'pendentes'])->middleware(['auth']);

Route::resource('vantagem', VantagemController::class)->middleware(['auth']);
Route::resource('plano', PlanoController::class)->middleware(['auth']);

Route::get('corrige', function () {
    $users = User::all();
    foreach ($users as $user) {
        $user->fill(['link' => md5($user->cpf)]);
        $user->save();
    }
});
Route::resource('metas', MetaController::class);

Route::get('assinatura/{id}', [AdminController::class, 'faturas'])->middleware(['auth']);

Route::get('indicacao/{id}', function ($id) {
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
        'password' => ['required', 'confirmed'],
        'cpf' => ['cpf_ou_cnpj', 'required', 'unique:users'],
        'telefone' => ['required'],
        'nascimento' => ['required', 'date'],
        'cep' => ['required'],
        'endereco' => ['required'],
        'bairro' => ['required'],
        'cidade' => ['required'],
        'uf' => ['required'],
        'n' => ['required'],
        'aceite' => ['required']
    ]);

    //dd($request->all());


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

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
});


Route::get('diretos', function () {
    return view('indicacao.diretos');
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
    //  dd($primeiros);
    $nivel = "Primeiro";

    // dd($indicados);
    return view('indicacao.primeiro', compact('indicados', 'nivel'));
})->middleware(['auth']);


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
})->middleware(['auth']);

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
})->middleware(['auth']);

Route::get('admin/extras', function () {
    $extras = Extra::all();
    return view('admin.extra.index', compact('extras'));
})->middleware(['auth']);
Route::get('admin/extra/edit/{id}', function ($id) {
    $extra = Extra::find($id);
    return view('admin.extra.edit', compact('extra'));
})->middleware(['auth']);
Route::post('admin/extras/edit/', function (Request $request) {
    // dd($request->all());
    $extra = Extra::find($request->id);
    $extra->fill($request->all());
    $extra->save();
    return redirect(url('admin/extras'));
})->middleware(['auth']);
Route::get('admin/extras/create', function () {
    // $extras = Extra::all();
    return view('admin.extra.create');
});
Route::post('admin/extras/create', function (Request $request) {
    // $extras = Extra::all();

    $validated = $request->validate([
        'name'    => 'required',


    ]);

    Extra::create($request->all());

    return redirect(url('admin/extras'));
    //   return view('admin.extra.create');
});
Route::get('cliente/faturas', function () {
    $busca = Assinatura::where('unico', 1)->where('user_id', Auth::user()->id)->first();

    return view('cliente.faturas.index', compact('busca'));
})->middleware(['auth']);

Route::get('financeiro/geral', function () {
    return view('financeiro.geral');
})->middleware(['auth']);

Route::get('cliente/vendas', function () {
    return view('relatorio.geral');
})->middleware(['auth']);
Route::get('cliente/pontos', function () {
    return view('relatorio.pontos');
})->middleware(['auth']);

Route::get('cliente/saque', function () {
    //dd(Auth::user()->saldo);

    $hoje = Carbon::now();
    // dd($hoje->format('l'));
    $agora = 0;
    if (($hoje->format('l') == "Monday") || ($hoje->format('l') == "Wednesday") || ($hoje->format('l') == "Friday")) {
        $agora = 1;
    }
    return view('saque.index', compact('agora'));
})->middleware(['auth']);

Route::get('realizar/saque', function () {

    $valor = Auth::user()->sobra;


    $valores =
        [
            'valor' => $valor,
            'status' => 0,
            'user_id' => Auth::user()->id
        ];


    \App\Models\Saque::create($valores);

    $dados = [
        'tipo' => 1,
        'descricao' => 'saque ref. bonus de rede',
        'valor' => $valor,
        'user_id' => Auth::user()->id
    ];
    //dd($pontuacao);

    \App\Models\Movimento::create($dados);

    return redirect()->back();
})->middleware(['auth']);

Route::get('cliente/combos', function () {
    $planos = Plano::orderBy('valor', 'asc')->get();
    $extras = Extra::all();
    // $users = User::all()->with('rated')->get()->sortByDesc('rated.rating');
    //    $planos = Plano::all()->with('vantagems')->get()->sortByDesc('vantagems.created_at');
    // dd($planos);
    return view('cliente.planos.index', compact('planos', 'extras'));
})->middleware(['auth']);

Route::get('endereco', function () {
    dd('oi');
});

Route::get('admin/dashboard', function () {
    if (Auth::user()->tipo == 0) {
        return redirect(url('dashboard'));
    }
    if (Auth::user()->tipo == 3) {
        return redirect(url('admin/usuarios'));
    }
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
    // dd($faturaspendentes);


    return view('admin.index', compact('entrada', 'saida', 'saques', 'faturaspendentes', 'faturaspagas', 'totalpendente', 'users', 'ativos', 'inativos', 'estados'));
})->middleware(['auth']);

Route::get('admin/faturas', function () {
    //$faturas = Assinatura::all();
    HelpersLogActivity::addToLog('Acessou aba Faturas');
    return view('admin.faturas');
})->middleware(['auth']);

Route::get('minhaconta', function () {
    $docs = Doc::all();
    return view('cliente.minhaconta', compact('docs'));
})->middleware(['auth']);

Route::post('endereco', function (Request $request) {
    // dd($request->all());

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

Route::post('cadconta', function (Request $request) {
    $validated = $request->validate([
        'banco_id' => 'required',
        'name'    => 'required',
        'agencia' => 'required',
        'conta' => 'required',

    ]);
    $request['user_id'] = Auth::user()->id;
    \App\Models\Conta::create($request->all());
    return redirect()->back();
});
Route::post('admin/cadconta', function (Request $request) {
    $validated = $request->validate([
        'banco_id' => 'required',
        'name'    => 'required',
        'agencia' => 'required',
        'conta' => 'required',

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
    $asaas = new \CodePhix\Asaas\Asaas('d48b8d7d12855aea3a7b81f89b861dbb61ed78813c746f785a847151bb746878', 'producao');


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
        $clientenovo =  $cliente->data[0]->id;
    }

    //dd($cliente);

    if ($assinatura->buscador == '') {


        $dadosCobranca = array(
            'customer'             => $clientenovo,
            'billingType'          => 'UNDEFINED',
            'value'                => $assinatura->valor,
            'dueDate'              => Carbon::now()->format('Y-m-d'),
            'description'          => $assinatura->plano->name . ' ' . "Pagamento referente ao mês de " . Carbon::create($assinatura->inicio)->monthName,
            'externalReference'    => '',
            'installmentCount'     => '',
            'installmentValue'     => '',
            'discount'             => '',
            'interest'             => '',
            'fine'                 => '',
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


Route::get('admin/user/edit/{id}', function ($id) {
    $user = User::find($id);
    $usuarios = User::all();
    HelpersLogActivity::addToLog('Acessou aba de Edição do usuario ' . $user->name);
    return view('painel.usuario.edit', compact('user', 'usuarios'));
});

Route::get('admin/user/visualizar/{id}', function ($id) {

    $user = User::find($id);

    return view('painel.usuario.show', compact('user'));
})->middleware(['auth']);

Route::post('admin/consulta/faturas', function (Request $request) {
    //dd($request->all());

    $datas = explode(" - ", $request->data);
    //  dd($datas);

    //$inicio =  $datas[0];

    $inicio = Carbon::createFromFormat('d/m/Y', $datas[0])->format('Y-m-d');
    $final = Carbon::createFromFormat('d/m/Y', $datas[1])->format('Y-m-d');

    //  dd($inicio);

    if ($request->status == 1) {
        $buscas = Assinatura::where('status', 1)->whereBetween('inicio', [$inicio, $final])->get();
        // dd($buscas);
        HelpersLogActivity::addToLog('Pequisou faturas Pagas Periodo de ' . $inicio . ' à ' . $final);
        return view('admin.faturas', compact('buscas'));
    }
    if ($request->status == 0) {
        $buscas = Assinatura::where('status', 0)->whereBetween('inicio', [$inicio, $final])->get();
        // dd($buscas);
        HelpersLogActivity::addToLog('Pequisou faturas Pendente Periodo de ' . $inicio . ' à ' . $final);
        return view('admin.faturas', compact('buscas'));
    }
    if ($request->status == 2) {
        $buscas = Assinatura::whereBetween('inicio', [$inicio, $final])->get();
        // dd($buscas);
        HelpersLogActivity::addToLog('Pequisou faturas Geral Periodo de ' . $inicio . ' à ' . $final);
        return view('admin.faturas', compact('buscas'));
    }
});
Route::post('admin/consulta/relatorio', function (Request $request) {
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

Route::get('admin/saque', function () {
    $tipo = 'TODOS';
    $saques = \App\Models\Saque::all();

    return view('admin.saque.todos', compact('saques', 'tipo'));
})->middleware(['auth']);


Route::get('admin/visualizar/saque/{id}', function ($id) {
    $saque = \App\Models\Saque::find($id);

    return view('admin.saque.visualizar', compact('saque'));
})->middleware(['auth']);
Route::get('admin/cancelar/saque/{id}', function ($id) {
    $saque = \App\Models\Saque::find($id);

    return view('admin.saque.cancelar', compact('saque'));
})->middleware(['auth']);
Route::get('admin/caixa', function () {
    HelpersLogActivity::addToLog('Acessou aba de Caixa');
    $caixas = Caixa::orderBy("id", 'desc')->get();
    $entrada = Caixa::where("tipo", 1)->sum('valor');
    $saida = Caixa::where("tipo", 0)->sum('valor');
    // dd($entrada);
    return view('admin.caixa', compact('caixas', 'entrada', 'saida'));
})->middleware(['auth']);

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
        'customer'             => $cliente->data[0]->id,
        'billingType'          => "UNDEFINED",
        'value'                => 50,
        'dueDate'              => "2022-01-24",
        'description'          => "Qualquer livro por apenas R$: 50,00",
        'externalReference'    => '',
        'installmentCount'     => '',
        'installmentValue'     => '',
        'discount'             => '',
        'interest'             => '',
        'fine'                 => '',
    );

    $cobranca = $asaas->Cobranca()->getById('pay_4489705199214310');

    dd($cobranca);
});

Route::get('atualizarfaturas', function () {
    $faturas = Assinatura::where('buscador', "!=", 'NULL')->get();



    foreach ($faturas as $fatura) {
        $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');
        $cobranca = $asaas->Cobranca()->getById($fatura->buscador);

        // dd($cobranca);

        if ($cobranca->status == 'CONFIRMED') {

            // dd($fatura);


            if ($fatura->status == 0) {
                $fatura->fill(['tipo' => $cobranca->billingType, 'status' => 1]);
                $fatura->save();

                $grava = [
                    'descricao' => 'Recebido da mensalidade do ' . $fatura->user->name,
                    'valor' => $fatura->plano->valor,
                    'tipo' => 1,
                    'user_id' => Auth::user()->id,
                ];
                Caixa::create($grava);
            }



            if (!empty($fatura->user->quem)) {
                $plano = Plano::find($fatura->plano->id);
                //dd($plano);

                $user = User::where('link', $fatura->user->quem)->first();


                $planolast = $user->assinaturas->last();

                if (!empty($planolast)) {
                    $extrato = [
                        'user_id' => $user->id,
                        'indicado_id' => $fatura->user->id,
                        'pontos' => $plano->pontos,
                        'saldo' => $plano->valor
                    ];


                    Extrato::create($extrato);
                    $pontuacao = $user->pontos + $plano->pontos;
                    $dados = [
                        'tipo' => 0,
                        'descricao' => 'bonus ref. login ' . $fatura->user->name . ' Direto',
                        'valor' => $plano->valor,
                        'user_id' => $user->id
                    ];
                    //dd($pontuacao);

                    \App\Models\Movimento::create($dados);
                    $user->fill(['pontos' => $pontuacao]);
                    $user->save();
                }


                $primeiro = User::where('link', $user->quem)->first();

                if (!empty($primeiro)) {

                    $planolast1 = $primeiro->assinaturas->last();

                    //dd($planolast->plano->direto);


                    if (!empty($planolast1)) {
                        $extrato1 = [
                            'user_id' => $primeiro->id,
                            'indicado_id' => $fatura->user->id,
                            'pontos' => $plano->pontos,
                            'saldo' => 0
                        ];

                        // dd($extrato);


                        Extrato::create($extrato1);
                        $pontuacao = $primeiro->pontos + $plano->pontos;

                        //dd($pontuacao);
                        $primeiro->fill(['pontos' => $pontuacao]);
                        $primeiro->save();
                    }


                    $segundo = User::where('link', $primeiro->quem)->first();

                    if (!empty($segundo)) {

                        $planolast2 = $segundo->assinaturas->last();

                        //dd($planolast->plano->direto);


                        if (!empty($planolast2)) {
                            $extrato2 = [
                                'user_id' => $segundo->id,
                                'indicado_id' => $fatura->user->id,
                                'pontos' => $plano->pontos,
                                'saldo' => 0
                            ];

                            // dd($extrato);


                            Extrato::create($extrato2);
                            $pontuacao = $segundo->pontos + $plano->pontos;

                            //dd($pontuacao);
                            $segundo->fill(['pontos' => $pontuacao]);
                            $segundo->save();
                        }


                        $terceiro = User::where('link', $segundo->quem)->first();

                        if (!empty($terceiro)) {

                            $planolast3 = $terceiro->assinaturas->last();

                            //dd($planolast->plano->direto);


                            if (!empty($planolast3)) {
                                $extrato3 = [
                                    'user_id' => $terceiro->id,
                                    'indicado_id' => $fatura->user->id,
                                    'pontos' => $plano->pontos,
                                    'saldo' => 0
                                ];

                                // dd($extrato);


                                Extrato::create($extrato3);
                                $pontuacao = $segundo->pontos + $plano->pontos;

                                //dd($pontuacao);
                                $terceiro->fill(['pontos' => $pontuacao]);
                                $terceiro->save();
                            }
                        }
                    }
                }
            } else {
            }
        }
        // dd($cobranca);
        //dd($fatura);
    }

    // dd($faturas);
});


Route::get('pagar/saque/{id}', function ($id) {

    $saque = Saque::find($id);

    $saque->fill(['status' => 1, 'liberacao' => Carbon::now()]);
    $saque->save();


    $grava = [
        'descricao' => 'Pagamento de Saque para ' . $saque->user->name,
        'valor' => $saque->valor,
        'tipo' => 0,
        'user_id' => Auth::user()->id,
    ];

    Caixa::create($grava);

    return redirect(url('admin/saque'));
});



Route::get('admin/saque/ativos', function () {
    $tipo = 'PAGOS';
    $saques = \App\Models\Saque::where('status', 1)->get();

    return view('admin.saque.todos', compact('saques', 'tipo'));
});

Route::get('admin/saque/pendentes', function () {
    $tipo = 'PENDENTES';
    $saques = \App\Models\Saque::where('status', 0)->get();

    return view('admin.saque.todos', compact('saques', 'tipo'));
});
Route::get('admin/saque/estornados', function () {
    $tipo = 'ESTORNADO';
    $saques = \App\Models\Saque::where('status', 2)->get();


    return view('admin.saque.todos', compact('saques', 'tipo'));
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
Route::post('admin/user/edit', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => ['required'],
        'cpf' => ['required', 'cpf'],
        'nascimento' => ['required'],
        'telefone' => 'required',
        'cep' => ['required'],
        'endereco' => ['required'],
        'bairro' => ['required'],
        'cidade' => ['required'],
        'uf' => ['required'],
        'n' => ['required'],
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
    $user->fill($request->all());
    $user->save();

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

    if ($user->endereco) {
        $user->endereco->fill($grava);
        $user->endereco->save();
    } else {
        Endereco::create($grava);
    }

    HelpersLogActivity::addToLog('Dados Alterados ' . $controle);
    return redirect()->back();
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
        $naobuscar =  Auth::user()->assinaturas->where('status', 1)->where('unico', 1)->first();

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

Route::get('admin/delete/conta/{id}', function ($id) {


    Conta::destroy($id);

    return redirect()->back();
})->middleware(['auth']);
Route::get('admin/users', function () {

    $users = User::where("tipo", 1)->get();

    return view('admin.user.index', compact('users'));
})->middleware(['auth']);
Route::get('admin/usuarios/edit/{id}', function ($id) {
    $user = User::find($id);

    return view('admin.user.edit', compact('user'));
})->middleware(['auth']);

Route::post('admin/usuarios/edit', function (Request $request) {

    //    dd($request->all());

    $request->validate([
        'name' => 'required',
        'email' => ['required'],
        'cpf' => ['required', 'cpf'],
        'nascimento' => ['required'],
        'telefone' => 'required',
        'tipo' => ['required'],
        'cep' => ['required'],
        'endereco' => ['required'],
        'bairro' => ['required'],
        'cidade' => ['required'],
        'uf' => ['required'],
        'n' => ['required'],
    ]);

    $user = User::find($request->id);
    $user->fill($request->all());
    $user->save();
});
Route::post('admin/buscarcpf', function (Request $request) {
    $request->validate([

        'cpf' => ['required', 'cpf'],

    ]);
    $request['cpf'] = preg_replace("/[^0-9]/", "", $request->cpf);
    $user = User::where('cpf', $request->cpf)->first();

    if (empty($user)) {
        return redirect()->back();
    }

    return redirect(url('admin/usuarios/edit', $user->id));
})->middleware(['auth']);

Route::get('buscafatura/{id}', function ($id) {
    $asaas = new \CodePhix\Asaas\Asaas('41891bad9d2d17a3ba2af9f77ec179751010bd79e9439e919194925827aba3d1', 'homologacao');
    $cobranca = $asaas->Cobranca()->getById($id);

    dd($cobranca);
})->middleware(['auth']);

Route::get('admin/relatorios', function () {
    $logs = HelpersLogActivity::logActivityLists();
    $agora = Carbon::now();
    $planos = Plano::all();
    $assinaturas = Assinatura::where("status", 1)->whereMonth('inicio', $agora)->get();
    HelpersLogActivity::addToLog('Acessou Relatorio');
    // dd($premium);
    $relatorios = Relatorio::all();
    return view('admin.relatoriogeral', compact('relatorios', 'assinaturas', 'planos', 'logs'));
})->middleware(['auth']);
Route::get('admin/relatorioplanos', function () {
    $logs = HelpersLogActivity::logActivityLists();
    $agora = Carbon::now();
    $planos = Plano::all();
    $assinaturas = Assinatura::where("status", 1)->whereMonth('inicio', $agora)->get();
    HelpersLogActivity::addToLog('Acessou Relatorio');
    // dd($premium);
    $relatorios = Relatorio::all();
    return view('admin.relatorioplano', compact('relatorios', 'assinaturas', 'planos', 'logs'));
})->middleware(['auth']);
Route::get('admin/logs', function () {
    $logs = HelpersLogActivity::logActivityLists();
    $agora = Carbon::now();
    $planos = Plano::all();
    $assinaturas = Assinatura::where("status", 1)->whereMonth('inicio', $agora)->get();
    HelpersLogActivity::addToLog('Acessou Relatorio');
    // dd($premium);
    $relatorios = Relatorio::all();
    return view('admin.relatorio', compact('relatorios', 'assinaturas', 'planos', 'logs'));
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
        $clientenovo =  $cliente->data[0]->id;
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
                'valor' =>  $emaberto,
                'user_id' => Auth::user()->id,
                'plano_id' => $ultimo->plano->id,

            ];
        } else {
            $grava2 = [
                'valor' =>  $valor,
                'user_id' => Auth::user()->id,
                'plano_id' => $ultimo->plano->id,

            ];
        }





        $busca =  Credito::create($grava2);
    }


    if ($busca->buscador == '') {


        $dadosCobranca = array(
            'customer'             => $clientenovo,
            'billingType'          => 'UNDEFINED',
            'value'                => $emaberto,
            'dueDate'              => Carbon::now()->format('Y-m-d'),
            'description'          => $busca->user->name . ' ' . "Pagamento Unico",
            'externalReference'    => '',
            'installmentCount'     => '',
            'installmentValue'     => '',
            'discount'             => '',
            'interest'             => '',
            'fine'                 => '',
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
    $fatura = Assinatura::find($id);

    $caixa = [
        'descricao' => 'Recebido da mensalidade cortesia do ' . $fatura->user->name,
        'valor' => 0,
        'tipo' => 1,
        'user_id' => $fatura->user->id,
    ];
    if (!empty($fatura->user->quem)) {
        $plano = Plano::find($fatura->plano->id);

        $user = User::where('link', $fatura->user->quem)->first();

        $planolast = $user->assinaturas->last();

        if (!empty($planolast)) {

            $extrato = [
                'user_id' => $user->id,
                'indicado_id' => $fatura->user->id,
                'pontos' => 0,
                'saldo' => 0
            ];


            Extrato::create($extrato);
            $pontuacao = $user->pontos + 0;
            $dados = [
                'tipo' => 0,
                'descricao' => 'bonus ref. login ' . $fatura->user->name . ' Direto',
                'valor' => 0,
                'user_id' => $user->id
            ];

            \App\Models\Movimento::create($dados);
            $user->fill(['pontos' => $pontuacao]);
            $user->save();
        }


        $primeiro = User::where('link', $user->quem)->first();

        if (!empty($primeiro)) {

            $planolast1 = $primeiro->assinaturas->last();

            //dd($planolast->plano->direto);


            if (!empty($planolast1)) {
                $extrato1 = [
                    'user_id' => $primeiro->id,
                    'indicado_id' => $fatura->user->id,
                    'pontos' => 0,
                    'saldo' => 0
                ];

                // dd($extrato);


                Extrato::create($extrato1);
                $pontuacao = $primeiro->pontos + 0;

                //dd($pontuacao);
                $primeiro->fill(['pontos' => $pontuacao]);
                $primeiro->save();
            }


            $segundo = User::where('link', $primeiro->quem)->first();

            if (!empty($segundo)) {

                $planolast2 = $segundo->assinaturas->last();

                if (!empty($planolast2)) {
                    $extrato2 = [
                        'user_id' => $segundo->id,
                        'indicado_id' => $fatura->user->id,
                        'pontos' => 0,
                        'saldo' => 0
                    ];


                    Extrato::create($extrato2);
                    $pontuacao = $segundo->pontos + 0;

                    $segundo->fill(['pontos' => $pontuacao]);
                    $segundo->save();
                }


                $terceiro = User::where('link', $segundo->quem)->first();

                if (!empty($terceiro)) {

                    $planolast3 = $terceiro->assinaturas->last();

                    if (!empty($planolast3)) {
                        $extrato3 = [
                            'user_id' => $terceiro->id,
                            'indicado_id' => $fatura->user->id,
                            'pontos' => 0,
                            'saldo' => 0
                        ];



                        Extrato::create($extrato3);
                        $pontuacao = $segundo->pontos + 0;

                        //dd($pontuacao);
                        $terceiro->fill(['pontos' => $pontuacao]);
                        $terceiro->save();
                    }
                }
            }
        }
    } else {
    }
    $fatura->fill(['status' => 1, 'valor' => 0]);
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
                                    'saldo' => ($plano->valor - ($plano->valor * ((100 -  $planolast1->plano->primeiro) / 100)))
                                ];
                                $dados1 = [
                                    'tipo' => 0,
                                    'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Primeiro Nivel ' . $plano->name,
                                    'valor' => ($plano->valor - ($plano->valor * ((100 -  $planolast1->plano->primeiro) / 100))),
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
                                        'saldo' => ($plano->valor - ($plano->valor * ((100 -  $planolast2->plano->segundo) / 100)))
                                    ];

                                    $dados2 = [
                                        'tipo' => 0,
                                        'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Segundo Nivel ' . $plano->name,
                                        'valor' => ($plano->valor - ($plano->valor * ((100 -  $planolast2->plano->segundo) / 100))),
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
                                            'saldo' => ($plano->valor - ($plano->valor * ((100 -  $planolast3->plano->terceiro) / 100)))
                                        ];
                                        $dados3 = [
                                            'tipo' => 0,
                                            'descricao' => 'bonus ref. login ' . $aberta->user->name . ' Terceiro Nivel ' . $plano->name,
                                            'valor' => ($plano->valor - ($plano->valor * ((100 -  $planolast3->plano->terceiro) / 100))),
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
        $clientenovo =  $cliente->data[0]->id;
    }

    //dd($cliente);

    if ($assinatura->buscador == '') {


        $dadosCobranca = array(
            'customer'             => $clientenovo,
            'billingType'          => 'UNDEFINED',
            'value'                => $assinatura->valor,
            'dueDate'              => Carbon::now()->format('Y-m-d'),
            'description'          => $assinatura->plano->name . ' ' . "Pagamento referente ao mês de " . Carbon::create($assinatura->inicio)->monthName,
            'externalReference'    => '',
            'installmentCount'     => '',
            'installmentValue'     => '',
            'discount'             => '',
            'interest'             => '',
            'fine'                 => '',
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
Route::get('admin/produto/edit/{id}', function ($id) {
    $produto = Produto::find($id);

    return view('admin.produtos.edit', compact('produto'));
});
Route::get('admin/energia', function () {
    $interesses = Interesse::all();
    return view('energia.index', compact('interesses'));
});

Route::get('admin/produto/caddoc/{id}', function ($id) {
    $produto = Produto::find($id);

    return view('admin.produtos.caddoc', compact('produto'));
})->middleware(['auth']);

Route::get('admin/produto/delete/{id}', function ($id) {
    $produto  = Produto::find($id);

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
        'cpf' => ['cpf_ou_cnpj', 'required', 'unique:users'],
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
        $clientenovo =  $cliente->data[0]->id;
    }

    //dd($cliente);

    if ($assinatura->buscador == '') {


        $dadosCobranca = array(
            'customer'             => $clientenovo,
            'billingType'          => 'UNDEFINED',
            'value'                => $assinatura->valor,
            'dueDate'              => Carbon::now()->format('Y-m-d'),
            'description'          => $assinatura->plano->name . ' ' . "Pagamento referente ao mês de " . Carbon::create($assinatura->inicio)->monthName,
            'externalReference'    => '',
            'installmentCount'     => '',
            'installmentValue'     => '',
            'discount'             => '',
            'interest'             => '',
            'fine'                 => '',
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
        $clientenovo =  $cliente->data[0]->id;
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
                'valor' =>  $emaberto,
                'user_id' => $user->id,
                'plano_id' => $ultimo->plano->id,

            ];
        } else {
            $grava2 = [
                'valor' =>  $valor,
                'user_id' => $user->id,
                'plano_id' => $ultimo->plano->id,

            ];
        }





        $busca =  Credito::create($grava2);
    }


    if ($busca->buscador == '') {


        $dadosCobranca = array(
            'customer'             => $clientenovo,
            'billingType'          => 'UNDEFINED',
            'value'                => $emaberto,
            'dueDate'              => Carbon::now()->format('Y-m-d'),
            'description'          => $busca->user->name . ' ' . "Pagamento Unico",
            'externalReference'    => '',
            'installmentCount'     => '',
            'installmentValue'     => '',
            'discount'             => '',
            'interest'             => '',
            'fine'                 => '',
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
})->middleware(['auth']);

Route::get('admin/ver/planos/{id}', function ($id) {
    $agora = Carbon::now();
    $plano = Plano::find($id);
    $assinaturas = Assinatura::where("status", 1)->whereMonth('inicio', $agora)->where('plano_id', $plano->id)->get();
    return view('admin.relatorio.visualizar', compact('plano', 'assinaturas'));
})->middleware(['auth']);


Route::get('admin/treinamentos', function () {
    $treinamentos = Treinamento::all();

    return view('admin.treinamentos.index', compact('treinamentos'));
})->middleware(['auth']);


Route::get('admin/treinamentos/create', function () {
    return view('admin.treinamentos.create');
})->middleware(['auth']);

Route::post('admin/treinamentos/create', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'video' => ['required', 'string', 'max:255'],

    ]);

    Treinamento::create($request->all());

    return redirect(url('admin/treinamentos'));
})->middleware(['auth']);


Route::get('treinamentos', function () {
    $treinamentos = Treinamento::all();
    return view('treinamentos.index', compact('treinamentos'));
})->middleware(['auth']);

Route::get('conheca/{id}', function ($id) {
    $extra = Extra::find($id);
    $existe = Interesse::where('user_id', Auth::user()->id)->where('extra_id', $id)->first();
    return view('extra.conheca', compact('extra', 'existe'));
})->middleware(['auth']);
Route::get("interesse/{id}", function ($id) {
    $grava = ['user_id' => Auth::user()->id, 'extra_id' => $id];
    Interesse::create($grava);
    return redirect(url('cliente/combos'));
})->middleware(['auth']);

Route::get('admin/delete/user/{id}', function ($id) {
    $user = User::find($id);
    User::destroy($id);

    return 'ok';
});
Route::get('admin/delete/fatura/{id}', function ($id) {
    //$user = Assinatura::find($id);
    Assinatura::destroy($id);

    return 'ok';
});
Route::get('admin/niveis', function () {
    $metas = Meta::withCount('users')->get();
    return view('admin.nivel.index', compact('metas'));
});

Route::get('admin/ver/nivel/{id}', function ($id) {

    $meta = Meta::find($id);
    //dd($meta);
    $usuarios = User::where("ordem", $meta->id)->get();
    //dd($usuarios);
    // $assinaturas = Assinatura::where("status", 1)->whereMonth('inicio', $agora)->where('plano_id', $plano->id)->get();
    return view('admin.nivel.visualizar', compact('meta', 'usuarios'));
});

Route::get('admin/entrega/premio/user/{user}/meta/{meta}', function ($user, $meta) {

    Premio::create(['user_id' => $user, 'meta_id' => $meta, 'status' => 1]);

    return redirect()->back();
});

Route::get('admin/premios', function () {
    $premios = Premio::all();
    return view('admin.relatorio.premio', compact('premios'));
});
require __DIR__ . '/auth.php';
