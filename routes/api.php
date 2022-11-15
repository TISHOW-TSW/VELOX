<?php

use App\Models\Anexo;
use App\Models\Plano;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('options/get-users', function (Request $request) {
    // $options = \App\Models\User::select('id', 'name as text')->get();

    $search = isset($request->search) ? $request->search : null;

    if ($search) {
        $options = \App\Models\User::select('id', 'login as text')->where('login', 'LIKE', '%' . $search . '%')->get();
    } else {
        $options = [];
    }


    return response([
        'results' => $options
    ], 201);
});


Route::get('dados/{id}', function ($id) {
    $user = \App\Models\User::find($id);

    $start = \Carbon\Carbon::now()->startOfMonth();
    $end = \Carbon\Carbon::now()->addDays(7);

    $ids = $user->indicados->pluck('id')->toArray();


    // dd($assinaturas);
    $controle = [];

    for ($i = $start; $i <= $end; $i->addDay()) {

        $assinaturas = \App\Models\Assinatura::whereIn('user_id', $ids)->whereDate('inicio', $start)->where('status', 1)->count();

        if ($assinaturas > 0) {
            $controle[] = ['name' => $i->format('d-m-Y'), 'total' => $assinaturas];
        }
    }

    return $controle;
});

Route::get('buscadados/{id}', function ($id) {

    $plano = Plano::find($id);

    $dado = [];

    $dado[] = ['id' => 1, 'name' => 'Mensal', 'valor' => 'R$ ' . number_format($plano->valor, 2, ',', '.')];
    $dado[] = ['id' => 2, 'name' => 'Anual', 'valor' => 'R$ ' . number_format($plano->valor * 12, 2, ',', '.')];


    //$novo = [];
    // $novo[] = ['name' => $plano->name, 'id' => $plano->id, $dado];
    return $dado;
});
Route::get('buscaplano/{id}', function ($id) {

    $plano = Plano::find($id);


    //$novo = [];
    // $novo[] = ['name' => $plano->name, 'id' => $plano->id, $dado];
    return $plano;
});


Route::post('file-upload/frente', function (Request $request) {

    //return ($request->all());

    $rules = array(
        'file' => 'required|mimes:jpeg,jpg,png,pdf|max:32760'
    );

    $error = Validator::make($request->all(), $rules);

    if ($error->fails()) {
        return response()->json(['errors' => $error->errors()->all()]);
    }
    $cliente = User::find($request->cliente_id);

    // return ($cliente);
    $image = $request->file('file');

    $new_name = rand() . '.' . $image->getClientOriginalExtension();
    //$image->move(public_path('arquivos'), $new_name);
    $busca = $cliente->anexos->where("doc_id", $request->doc_id);

    //  return ($busca);
    if (count($busca) > 0) {
        $image->move(public_path('arquivos'), $new_name);
        $salvar = $busca->first();
        $salvar->fill(['verso' => $new_name]);
        $salvar->save();
        //dd($cliente->doc);
        ///  $cliente->doc->fill(['frente' => $new_name]);


        // $cliente->doc->save();
    } else {

        $image->move(public_path('arquivos'), $new_name);
        // return 'oi';
        $grava = [
            'user_id' => $request->cliente_id,
            'frente' => $new_name,
            'doc_id' => $request->doc_id
        ];

        //  return $grava;

        $anexo = Anexo::create($grava);
    }

    $output = array(
        'success' => 'Image uploaded successfully',
        'image' => '<img src="/images/' . $new_name . '" class="img-thumbnail" />'
    );

    return $output;

    // $grava = ['custom' => $request['name'], 'name' => $new_name, 'protocolo_id' => $request['protocolo_id']];
});


Route::post('file-upload/produto/upload', function (Request $request) {
    $rules = array(
        'file' => 'required|mimes:jpeg,jpg,png,pdf|max:32760'
    );

    $error = Validator::make($request->all(), $rules);

    if ($error->fails()) {
        return response()->json(['errors' => $error->errors()->all()]);
    }

    //return $request->all();
    // $cliente = User::find($request->cliente_id);

    // return ($cliente);
    $image = $request->file('file');

    $new_name = rand() . '.' . $image->getClientOriginalExtension();
    //$image->move(public_path('arquivos'), $new_name);
    // $busca = $cliente->anexos->where("doc_id", $request->doc_id);
    $busca = Produto::find($request->produto_id);
    //return ($busca);
    if (!empty($busca->img)) {
        unlink('arquivos/produtos/' . $busca->img);
    }


    $image->move(public_path('arquivos/produtos'), $new_name);
    $salvar = $busca;
    $salvar->fill(['img' => $new_name]);
    $salvar->save();
    //dd($cliente->doc);
    ///  $cliente->doc->fill(['frente' => $new_name]);


    // $cliente->doc->save();


    $output = array(
        'success' => 'Image uploaded successfully',
        'image' => '<img src="/images/' . $new_name . '" class="img-thumbnail" />'
    );

    return $output;
});
Route::post('file-upload/plano/upload', function (Request $request) {

    //dd($request->all());
    $rules = array(
        'file' => 'required|mimes:jpeg,jpg,png,pdf|max:32760'
    );


    $plano = Plano::find($request->produto_id);

    $error = Validator::make($request->all(), $rules);

    if ($error->fails()) {
        return response()->json(['errors' => $error->errors()->all()]);
    }

    //dd($request->all());


    $file = $request->file('file');
    // ou
    $file = $request->file;
    $nameFile = "";
    if ($request->hasFile('file') && $request->file('file')->isValid()) {

        // Define um aleatório para o arquivo baseado no timestamps atual
        $name = uniqid(date('HisYmd'));
        //    dd($name);

        // Recupera a extensão do arquivo
        $extension = $request->file->extension();

        // dd($extension);

        // Define finalmente o nome
        $nameFile = "{$name}.{$extension}";

        // Faz o upload:
        //$upload = $request->file->storeAs('comprovantes', $nameFile, 'public');

        //  $upload = Storage::disk('digitalocean')->putFile('comprovantes', $nameFile, 'public');
        $upload = Storage::disk('digitalocean')->putFile('carros', request()->file, 'public');

        // return $nameFile;
        // Se tiver funcionado o arquivo foi armazenado em storage/app/public/categories/nomedinamicoarquivo.extensao
        //$produto = \App\Models\Produto::find($request['produto_id']);
        // $produto->fill(['file' => $nameFile]);
        $plano->update(['img' => $upload]);
        //  \App\Models\Comprovante::create(['file' => $upload, 'compra_id' => $request->compra_id]);
        // $produto->save();
        // Verifica se NÃO deu certo o upload (Redireciona de volta)
        if (!$upload) {
            return ('error' . ' Falha ao fazer upload');
        }
    };

    $output = array(
        'success' => 'Image uploaded successfully',
        'image' => '<img src="/produtos/' . $nameFile . '" class="img-thumbnail" />'
    );
    return $output;
});
Route::post('file-upload/produto/doc/upload', function (Request $request) {
    $rules = array(
        'file' => 'required|mimes:jpeg,jpg,png,pdf|max:32760'
    );

    $error = Validator::make($request->all(), $rules);

    if ($error->fails()) {
        return response()->json(['errors' => $error->errors()->all()]);
    }
    if (!empty($busca->arquivo)) {
        unlink('arquivos/produtos/doc' . $busca->arquivo);
    }


    //return $request->all();
    // $cliente = User::find($request->cliente_id);

    // return ($cliente);
    $image = $request->file('file');

    $new_name = rand() . '.' . $image->getClientOriginalExtension();
    //$image->move(public_path('arquivos'), $new_name);
    // $busca = $cliente->anexos->where("doc_id", $request->doc_id);
    $busca = Produto::find($request->produto_id);
    //return ($busca);
    $image->move(public_path('arquivos/produtos/doc'), $new_name);
    $salvar = $busca;
    $salvar->fill(['arquivo' => $new_name]);
    $salvar->save();
    //dd($cliente->doc);
    ///  $cliente->doc->fill(['frente' => $new_name]);


    // $cliente->doc->save();


    $output = array(
        'success' => 'Image uploaded successfully',
        'image' => '<img src="/images/' . $new_name . '" class="img-thumbnail" />'
    );

    return $output;
});


Route::post('file-upload/comprovante', function (Request $request) {


    $rules = array(
        'img' => 'required|mimes:jpeg,jpg,png,pdf|max:32760'
    );

    $error = Validator::make($request->all(), $rules);

    if ($error->fails()) {
        return response()->json(['errors' => $error->errors()->all()]);
    }


    $file = $request->file('img');
    // ou
    $file = $request->img;
    $nameFile = "";
    if ($request->hasFile('img') && $request->file('img')->isValid()) {

        // Define um aleatório para o arquivo baseado no timestamps atual
        $name = uniqid(date('HisYmd'));
        //    dd($name);

        // Recupera a extensão do arquivo
        $extension = $request->img->extension();

        // dd($extension);

        // Define finalmente o nome
        $nameFile = "{$name}.{$extension}";

        // Faz o upload:
        //$upload = $request->img->storeAs('comprovantes', $nameFile, 'public');

        //  $upload = Storage::disk('digitalocean')->putFile('comprovantes', $nameFile, 'public');
        $upload = Storage::disk('digitalocean')->putFile('comprovantesvelox', request()->img, 'public');

        // return $nameFile;
        // Se tiver funcionado o arquivo foi armazenado em storage/app/public/categories/nomedinamicoarquivo.extensao
        //$produto = \App\Models\Produto::find($request['produto_id']);
        // $produto->fill(['img' => $nameFile]);
        $compra = \App\Models\Compra::find($request->compra_id);
        $compra->update(['img' => $upload]);
        //\App\Models\Comprovante::create(['img' => $upload, 'compra_id' => $request->compra_id]);
        // $produto->save();
        // Verifica se NÃO deu certo o upload (Redireciona de volta)
        if (!$upload) {
            return ('error' . ' Falha ao fazer upload');
        }
    };

    $output = array(
        'success' => 'Image uploaded successfully',
        'image' => '<img src="/produtos/' . $nameFile . '" class="img-thumbnail" />'
    );
    return $output;

    // $grava = ['custom' => $request['name'], 'name' => $new_name, 'protocolo_id' => $request['protocolo_id']];
});


Route::get('compra/{id}', function ($id, \App\Services\ApiPixService $apiPixService) {

    $compra = \App\Models\Compra::find($id);
    //dd($compra);
    $novo = ($apiPixService->gerarPix($compra));
//dd($novo->id_transacao);

    $compra->update(['pix' => $novo->id_transacao]);


    //dd($compra);
    // $teste = json_decode($novo);

    // $teste = strip_tags($novo);

    //dd($teste);

    //dd($teste);
    ///dd(stringToArray($novo),1);

    return $novo;
});
