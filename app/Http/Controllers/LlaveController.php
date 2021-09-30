<?php

namespace App\Http\Controllers;

use App\Models\Llave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LlaveController extends Controller
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
        $request->validate([
            'imagen_llave' => 'image'
        ]);

       $request->file(key: 'imagen_llave')->store(path: '/public/imagen_llaves');

       $url = Storage::url($request->file(key: 'imagen_llave')->store(path: '/public/imagen_llaves'));

       return $url;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Llave  $llave
     * @return \Illuminate\Http\Response
     */
    public function show(Llave $llave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Llave  $llave
     * @return \Illuminate\Http\Response
     */
    public function edit(Llave $llave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Llave  $llave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Llave $llave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Llave  $llave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Llave $llave)
    {
        //
    }
}
