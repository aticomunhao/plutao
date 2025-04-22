<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GerenciarDataLimiteDeFeriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dias_limite_de_ferias = DB::table('hist_dia_limite_de_ferias')->get();

        return view('dias-limite-ferias.gerenciar-dias-limite-ferias', compact('dias_limite_de_ferias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dias-limite-ferias.criar-dias-limite-ferias');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dias_limite_de_ferias = DB::table('hist_dia_limite_de_ferias')
            ->where('data_fim', '=', null)
            ->first();

        if ($dias_limite_de_ferias == null) {
            DB::table('hist_dia_limite_de_ferias')
                ->insert([
                    'dias' => $request->input('dias'),
                    'data_inicio' => Carbon::now()->toDateString(),
                ]);
        } else {
            DB::table('hist_dia_limite_de_ferias')
                ->where('data_fim', '=', null)
                ->update(['data_fim' => Carbon::yesterday()->toDateString()]);
            DB::table('hist_dia_limite_de_ferias')
                ->insert([
                    'dias' => $request->input('dias'),
                    'data_inicio' => Carbon::now()->toDateString(),
                ]);
        }
        app('flasher')->addSuccess('Alterado com Sucesso');
        return redirect()->route('index.gerenciar-dia-limite-ferias');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
