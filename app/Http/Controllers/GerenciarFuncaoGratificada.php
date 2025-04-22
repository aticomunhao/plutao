<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GerenciarFuncaoGratificada extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        if ($search) {
            $funcoesgratificadas = DB::table('funcao_gratificada')
                ->where('nomeFG', 'ilike', '%' . $search . '%')
                ->get();
        } else {
            $funcoesgratificadas = DB::table('funcao_gratificada')->get();
        }

        $dataDeHoje = Carbon::today()->toDateString();

        foreach ($funcoesgratificadas as $funcaogratificada) {

            if ($funcaogratificada->dt_fimFG < $dataDeHoje && $funcaogratificada->dt_fimFG != null) {
                $funcaogratificada->status = false;

            }
        }


        return view('funcaogratificada.gerenciar-funcao-gratificada', compact('funcoesgratificadas', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('funcaogratificada.criar-funcao-gratificada');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataDeHoje = Carbon::today()->toDateString();

        if ($request->input('data_inicial') > $request->input('data_final') && $request->input('data_final') != null) {
            app('flasher')->addError('Não é possivel adicionar, Data inicial maior que a Data Final');
            return redirect()->route('IndexGerenciarFuncaoGratificada');
        } else if ($request->input('data_final') != null) {
            $idfuncaoGratificada = DB::table('funcao_gratificada')
                ->insertGetId([
                    'nomeFG' => $request->input('nomefuncao'),
                    'salarioFG' => $request->input('salario'),
                    'dt_inicioFG' => $request->input('data_inicial'),
                    'dt_fimFG' => $request->input('data_final'),
                    'status' => false,
                    'dt_inicioSalFG' => $dataDeHoje
                ]);
            DB::table('hist_funcao_gratificada')
                ->insert([
                    'idFG' => $idfuncaoGratificada,
                    'salario' => $request->input('salario'),
                    'motivo' => 'Abertura da Cargo',
                    'dt_inicio' => $dataDeHoje
                ]);

            DB::table('hist_funcao_gratificada')
                ->insert([
                    'idFG' => $idfuncaoGratificada,
                    'salario' => $request->input('salario'),
                    'motivo' => 'Criação Função',
                    'datamod' => $request->input('data_final')
                ]);
            app('flasher')->addSuccess('Função Gratificada adicionada com Sucesso!');
            return redirect()->route('IndexGerenciarFuncaoGratificada');
        } else {
            $idfuncaoGratificada = DB::table('funcao_gratificada')
                ->insertGetId([
                    'nomeFG' => $request->input('nomefuncao'),
                    'salarioFG' => $request->input('salario'),
                    'dt_inicioFG' => $request->input('data_inicial'),
                    'dt_fimFG' => $request->input('data_final'),
                    'status' => true,
                    'dt_inicioSalFG' => $dataDeHoje
                ]);
            DB::table('hist_funcao_gratificada')
                ->insert([
                    'idFG' => $idfuncaoGratificada,
                    'salario' => $request->input('salario'),
                    'motivo' => 'Criação Função',
                    'datamod' => $dataDeHoje
                ]);


            app('flasher')->addSuccess('Função Gratificada adicionada com Sucesso!');
            return redirect()->route('IndexGerenciarFuncaoGratificada');
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $funcao = DB::table('funcao_gratificada')->where('id', $id)->first();

        $funcaogratificada = DB::table('hist_funcao_gratificada')
            ->where('idFG', $id)->orderBy('datamod', 'desc')
            ->get();


        return view('funcaogratificada.hist-funcao-gratificada', compact('funcaogratificada', 'funcao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $funcaogratificada = DB::table('funcao_gratificada')
            ->where('id', $id)
            ->first();

        return view('funcaogratificada.editar-funcao-gratificada', compact('funcaogratificada'));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $funcaogratificada = DB::table('funcao_gratificada')
            ->where('id', $id)
            ->first();


        if ($request->input('data_inicial') < $request->input('data_final')) {
            app('flasher')->addError('Não foi posso');
        } else if ($request->input('salario') < $funcaogratificada->salarioFG) {
            app('flasher')->addError('Não foi possivel editar, o salario inserido é menor que o salario anterior');
        } else {
            $dataDeHoje = Carbon::today()->toDateString();
            DB::table('funcao_gratificada')
                ->where('id', $id)
                ->update([
                    'nomeFG' => $request->input('nomecargo'),
                    'dt_inicioFG' => $request->input('data_inicial'),
                    'dt_fimFG' => $request->input('data_final'),
                    'salarioFG' => $request->input('salario'),

                ]);

            DB::table('hist_funcao_gratificada')
                ->insert([
                    'idFG' => $id,
                    'salario' => $request->input('salario'),
                    'motivo' => $request->input('motivoalteracao'),
                    'datamod' => $dataDeHoje
                ]);
        }

        return redirect()->route('IndexGerenciarFuncaoGratificada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function close(string $id, Request $request)
    {
        $funcaogratificada = DB::table('funcao_gratificada')
            ->where('id', $id)
            ->first();

        $dataDeHoje = Carbon::today()->toDateString();
        DB::table('funcao_gratificada')
            ->where('id', $id)
            ->update([
                'status' => false,
                'dt_fimFG' => $dataDeHoje
            ]);
        DB::table('hist_funcao_gratificada')
            ->insert([
                'idFG' => $id,
                'salario' => $funcaogratificada->salarioFG,
                'motivo' => 'Encerramento da Funcao',
                'datamod' => $dataDeHoje
            ]);
        return redirect()->route('IndexGerenciarFuncaoGratificada');
    }
}
