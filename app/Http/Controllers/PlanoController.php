<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanoRequest;
use App\Models\Plano;
use App\Models\Vantagem;
use Illuminate\Http\Request;

class PlanoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $planos = Plano::all();
        return view('painel.plano.index', compact('planos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vantagens = Vantagem::all();
        return view('painel.plano.create', compact('vantagens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlanoRequest $request)
    {
        //dd($request->all());

        // $vantagem = $request->vantagem
        //dd($request->all());
        $plano = Plano::create($request->all());
        $plano->vantagems()->attach($request->vantagem_id);

        return redirect(url('admin/plano/cadfoto', $plano->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function show(Plano $plano)
    {
        return view('painel.plano.show', compact('plano'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function edit(Plano $plano)
    {
        $vantagens = Vantagem::all();
        $case_services = $plano->vantagems->toArray();

        //dd($case_services);
        return view('painel.plano.edit', compact('plano', 'vantagens', 'case_services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plano $plano)
    {
        //  dd($request->all());
        $plano->fill($request->all());
        $plano->save();
        $plano->vantagems()->sync($request->vantagem_id);
        return redirect(url('admin/plano/cadfoto', $plano->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plano $plano)
    {
        //
    }
}
