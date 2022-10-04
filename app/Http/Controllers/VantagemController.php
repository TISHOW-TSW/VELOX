<?php

namespace App\Http\Controllers;

use App\Http\Requests\VantagemRequest;
use App\Models\Vantagem;
use Illuminate\Http\Request;

class VantagemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $vantagens = Vantagem::all();

       // dd($vantagens);
        return view('painel.vantagem.index', compact('vantagens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('painel.vantagem.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VantagemRequest $request)
    {
        Vantagem::create($request->all());
        return redirect()->route('vantagem.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vantagem  $vantagem
     * @return \Illuminate\Http\Response
     */
    public function show(Vantagem $vantagem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vantagem  $vantagem
     * @return \Illuminate\Http\Response
     */
    public function edit(Vantagem $vantagem)
    {
        return view('painel.vantagem.edit', compact('vantagem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vantagem  $vantagem
     * @return \Illuminate\Http\Response
     */
    public function update(VantagemRequest $request, Vantagem $vantagem)
    {
        $vantagem->fill($request->all());
        $vantagem->save();

        return redirect()->route('vantagem.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vantagem  $vantagem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vantagem $vantagem)
    {
        //
    }
}
