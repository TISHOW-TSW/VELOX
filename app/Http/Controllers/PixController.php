<?php

namespace App\Http\Controllers;

use App\Models\Pix;
use Illuminate\Http\Request;

class PixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        Pix::create($request->all());
        return redirect()->back()->with('succes', 'Chave pix cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pix  $pix
     * @return \Illuminate\Http\Response
     */
    public function show(Pix $pix)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pix  $pix
     * @return \Illuminate\Http\Response
     */
    public function edit(Pix $pix)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pix  $pix
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pix $pix)
    {

        $pix->update($request->all());
        return redirect()->back()->with('success', 'Chave pix editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pix  $pix
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pix $pix)
    {
        //
    }
}
