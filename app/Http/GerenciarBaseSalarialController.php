<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class GerenciarBaseSalarialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idf)
    {


        $hist_base_salarial = DB::table('hist_base_salarial as hist_bs')
            ->join('cargos as cr', 'hist_bs.id_cargo_regular', '=', 'cr.id')
            ->leftJoin('cargos as fg', 'hist_bs.id_funcao_gratificada', '=', 'fg.id')
            ->join('funcionarios as f', 'f.id', '=', 'hist_bs.id_funcionario')
            ->where('hist_bs.id_funcionario', $idf)
            ->select(
                'hist_bs.id as hist_bs_id',
                'hist_bs.data_inicio as hist_bs_dtinicio',
                'hist_bs.data_fim as hist_bs_dtfim',
                'hist_bs.id_funcionario as hist_bs_bsidf',
                'hist_bs.id_funcao_gratificada as hist_bs_idfg',
                'hist_bs.salario_funcao_gratificada as hist_bs_fg_salario',
                'hist_bs.id_cargo_regular as hist_bs_idfg',
                'hist_bs.salario_cargo as hist_bs_cr_salario',
                'cr.nome as crnome',
                'cr.salario as crsalario',
                'fg.nome as fgnome',
                'f.id as fid',
                'f.dt_inicio as fdti'
            )
            ->get();


        if ($hist_base_salarial->isEmpty()) {
            return redirect()->route('IncluirBaseSalarial', ['idf' => $idf]);
        } else {
            $salarioatual = DB::table('base_salarial as bs')
                ->leftJoin('cargos as cr', 'bs.cargo', '=', 'cr.id')
                ->leftJoin('cargos as fg', 'bs.funcao_gratificada', '=', 'fg.id')
                ->leftJoin('funcionarios as f', 'f.id', '=', 'bs.id_funcionario')
                ->where('bs.id_funcionario', $idf)
                ->select(
                    'bs.id as bsid',
                    'bs.anuenio as bsanuenio',
                    'bs.dt_inicio as bsdti',
                    'bs.id_funcionario as bsidf',
                    'cr.id as crid',
                    'cr.nome as crnome',
                    'cr.salario as crsalario',
                    'fg.id as fgid',
                    'fg.nome as fgnome',
                    'fg.salario as fgsalario',
                    'f.id as fid',
                    'f.id_pessoa  as fidp',
                    'f.dt_inicio as fdti'
                )->first();

            $funcionario = DB::table('pessoas as p')
                ->where('p.id', $salarioatual->fidp)
                ->join('funcionarios as f', 'f.id_pessoa', '=', 'p.id')
                ->first();


            foreach ($hist_base_salarial as $basesalarials) {
                $dataDeHoje = Carbon::now();
                $dataDaContratacao = Carbon::parse($basesalarials->fdti);
                $basesalarials->anuenio = intval(($dataDeHoje->diffInDays($dataDaContratacao)) / 365);
            }


            return view('basesalarial.gerenciar-base-salarial', compact('hist_base_salarial', 'salarioatual', 'funcionario', 'idf'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idf)
    {
        $funcionario = DB::table('funcionarios')->join('pessoas', 'pessoas.id', '=', 'funcionarios.id_pessoa')
            ->where('funcionarios.id', '=', $idf)
            ->select([
                'pessoas.nome_completo',
                'pessoas.id as id_pessoa',
                'funcionarios.id as id_funcionario'
            ])
            ->first();
        $tp_cargo = DB::table('tp_cargo')->get();


        return view('basesalarial.cadastrar-base-salarial', compact('funcionario', 'tp_cargo', 'idf'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $idf)
    {

        $dataDeHoje = Carbon::today()->toDateString();
        $input = $request->all();


        $cargo = DB::table('cargos')->where('id', $input['cargo'])->first();


        if ($request->input('funcaog') == null) {
            DB::table('base_salarial')->insert([
                'cargo' => $input['cargo'],
                'funcao_gratificada' => null,
                'dt_inicio' => $dataDeHoje,
                'id_funcionario' => $idf
            ]);

            DB::table('hist_base_salarial')
                ->insert([
                    'id_cargo_regular' => $input['cargo'],
                    'salario_cargo' => $cargo->salario,
                    'data_inicio' => $dataDeHoje,
                    'motivo' => 'Inicio Base Salarial',
                    'id_funcionario' => $idf,
                ]);
        } else {
            $funcaoGratificada = DB::table('cargos')->where('id', $input['funcaog'])->first();

            DB::table('base_salarial')->insert([
                'cargo' => $input['cargo'],
                'funcao_gratificada' => $input['funcaog'],
                'dt_inicio' => $dataDeHoje,
                'id_funcionario' => $idf

            ]);
            DB::table('hist_base_salarial')->insert([
                'id_cargo_regular' => $input['cargo'],
                'salario_cargo' => $cargo->salario,
                'id_funcao_gratificada' => $funcaoGratificada->id,
                'salario_funcao_gratificada' => $funcaoGratificada->salario,
                'data_inicio' => $dataDeHoje,
                'motivo' => 'Inicio Base Salarial',
                'id_funcionario' => $idf,


            ]);
        }
        return redirect()->route('GerenciarBaseSalarialController', ['idf' => $idf]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $idf)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idf)

    {
        $base_salarial = DB::table('base_salarial as bs')->leftJoin('cargos as cr', 'bs.cargo', '=', 'cr.id')
            ->leftJoin('cargos as fg', 'bs.funcao_gratificada', '=', 'fg.id')
            ->leftJoin('funcionarios as f', 'f.id', '=', 'bs.id_funcionario')
            ->where('bs.id_funcionario', $idf)
            ->select(
                'bs.id as base_salarial_id',
                'bs.anuenio as base_salarial_anuenio',
                'bs.dt_inicio as base_salarial_data_de_inicio',
                'bs.id_funcionario as base_salarial_id_funcionario',
                'cr.id as cargo_regular_id',
                'cr.nome as cargo_regular_nome',
                'cr.salario as salario_cargo_regular',
                'fg.id as funcao_gratificada_id',
                'fg.nome as funcao_gratificada_nome',
                'fg.salario as salario_funcao_gratificada',
                //     'f.id as funcionario',
                'f.id_pessoa  as funcionario_id_pessoa',
                'f.dt_inicio as funcionario_dt_inicio'
            )->first();


        $funcionario = DB::table('funcionarios')->join('pessoas', 'pessoas.id', '=', 'funcionarios.id_pessoa')
            ->where('funcionarios.id', '=', $base_salarial->base_salarial_id_funcionario)
            ->select([
                'pessoas.nome_completo',
                'pessoas.id as id_pessoa',
                'funcionarios.id as id_funcionario'
            ])
            ->first();
        $tp_cargo = DB::table('tp_cargo')->get();

        return view('basesalarial.editar-base-salarial', compact('base_salarial', 'funcionario', 'tp_cargo', 'idf'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idf)
    {
        $resultado_form = $request->all();

        dd($resultado_form);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idf)
    {
        //
    }

    public function retornaCargos(string $id)
    {

        if ($id == 2) {
            $cargos = DB::table('cargos')->where('tp_cargo', '=', 1)->get();
            return response()->json($cargos);
        } else {
            $cargos = DB::table('cargos')->where('tp_cargo', '=', $id)->get();
            return response()->json($cargos);
        }
    }

    public function retornaFG()
    {
        $funcao_gratificada = DB::table('cargos')->where('tp_cargo', '=', 2)->get();
        return response()->json($funcao_gratificada);
    }
}
