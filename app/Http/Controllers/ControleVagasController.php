<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models;
use iluminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\CollectionorderBy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use PhpParser\Node\Expr\AssignOp\ShiftLeft;

class ControleVagasController extends Controller
{
    public function index(Request $request)
    {

        //PRIMEIRA TABELA
        $cargo = DB::table('cargo AS c')
            ->leftJoin('base_salarial AS bs', 'bs.id_cargo_regular', 'c.id')
            ->select(DB::raw('COUNT(bs.id_funcionario) AS quantidade_funcionarios'), 'c.id AS idCargo', 'c.nome AS nomeCargo')
            ->groupBy('idCargo', 'nomeCargo');


        $vaga = DB::table('tp_vagas_autorizadas AS va')
            ->select(DB::raw('SUM(va.vagas_autorizadas) AS total_vagas'), 'va.id_cargo AS idDoCargo', 'va.vagas_excelencia AS vExcelencia')
            ->groupBy('idDoCargo', 'vExcelencia');



        $pesquisa = $request->input('pesquisa');

        if ($pesquisa == 'cargo') {
            $cargoId = $request->input('cargo');
            $cargo->where('c.id', $cargoId);
            $vaga->where('va.id_cargo', $cargoId);
        }

        $cargo = $cargo->get();
        $vaga = $vaga->get();
        //dd($vaga, $cargo);

        //SEGUNDA TABELA
        $setor = DB::table('setor AS s')
            ->select('s.id AS idSetor', 's.nome AS nomeSetor', 's.sigla')
            ->orderBy('nomeSetor');


        $pesquisa = $request->input('pesquisa');


        if ($pesquisa == 'setor') {
            $setorId = $request->input('setor');
            $setor->where('s.id', $setorId);
        }

        $setor = $setor->get();

        foreach ($setor as $key => $teste) {
            $vagaUm = DB::table('tp_vagas_autorizadas AS va')
                ->leftJoin('cargo AS c', 'c.id', 'va.id_cargo')
                ->select('va.vagas_autorizadas AS vagas', 'c.nome AS nomeCargo', 'c.id AS idCargo', 'va.id AS idVagas', 'va.vagas_excelencia AS vExcelencia')
                ->where('va.id_setor', $teste->idSetor)
                ->get();

            $teste->bola = $vagaUm;

            foreach ($vagaUm as $keyDois => $testeDois) {
                $base = DB::table('base_salarial AS bs')
                    ->leftJoin('funcionarios AS f', 'bs.id_funcionario', 'f.id')
                    ->leftJoin('hist_setor', 'hist_setor.id_func', 'f.id')
                    ->where('bs.id_cargo_regular', $testeDois->idCargo)
                    ->where('hist_setor.id_setor', $teste->idSetor)
                    ->select(DB::raw('COUNT(bs.id_funcionario) AS quantidade'))
                    ->get();

                $testeDois->gato = $base;
            }
        }

        return view('efetivo.controle-vagas', compact('cargo', 'setor', 'vaga', 'pesquisa'));
    }

    public function create()
    {
        $cargo = DB::table('cargo')
            ->select('cargo.id AS idCargo', 'cargo.nome AS nomeCargo')
            ->get();

        $setor = DB::table('setor')
            ->select('setor.id AS idSetor', 'setor.nome AS nomeSetor', 'setor.sigla AS siglaSetor')
            ->get();


        return view('efetivo.incluir-vagas', compact('cargo', 'setor'));
    }




    public function store(Request $request)
    {
        $cargoId = $request->input('vagasCargo');
        $setorId = $request->input('vagasSetor');

        //dd($cargoId, $setorId);

        // Verificar se já existem vagas para o cargo no setor
        $existingVagas = DB::table('tp_vagas_autorizadas')
            ->where('id_cargo', $cargoId)
            ->where('id_setor', $setorId)
            ->exists();

        if ($existingVagas) {
            app('flasher')->addError('Esse Cargo já possui vagas nesse Setor.');
            return redirect()->back()->withInput();
        } else {

            DB::table('tp_vagas_autorizadas')->insert([
                'id_cargo' => $request->input('vagasCargo'),
                'id_setor' => $request->input('vagasSetor'),
                'vagas_autorizadas' => $request->input('vTotal'),
                'vagas_excelencia' => $request->input('vExcelencia'),
            ]);
            $histData = [
                'id_cargo' => $cargoId,
                'id_setor' => $setorId,
                'vagas_autorizadas' => $request->input('vTotal'),
                'vagas_excelencia' => $request->input('vExcelencia'),
                'alteracao' => 'Criado'
            ];

            DB::table('hist_tp_vagas_autorizadas')->insert($histData);
            app('flasher')->addSuccess('O cadastro das vagas foram realizadas com sucesso.');
            return redirect()->route('indexControleVagas');
        }
    }
    public function edit(string $idVagas)
    {
        // Recupere o cargo pelo ID
        $busca = DB::table('setor')
            ->leftJoin('tp_vagas_autorizadas AS va', 'setor.id', '=', 'va.id_setor')
            ->leftJoin('cargo', 'cargo.id', '=', 'va.id_cargo')
            ->where('va.id', $idVagas)
            ->select(
                'va.id_setor AS idSetor',
                'va.id_cargo AS idCargo',
                'va.vagas_autorizadas AS vTotal',
                'cargo.nome AS nomeCargo',
                'setor.nome AS nomeSetor',
                'setor.sigla AS siglaSetor',
                'va.id AS idVagas',
                'va.vagas_excelencia AS vExcelencia'
            )
            ->first();



        return view('efetivo.editar-vagas', compact('busca'));
    }



    public function update(Request $request, $idC)
    {
        // Obtenha o ID das vagas do formulário
        $idVagas = $request->input('idVagas');

        // Obtenha o número de vagas autorizadas enviado no formulário
        $numeroVagas = $request->input('vTotal');
        $vExcelencia = $request->input('vExcelencia');

        // Atualize o número de vagas autorizadas na tabela tp_vagas_autorizadas
        DB::table('tp_vagas_autorizadas')
            ->where('id', $idVagas)
            ->update([
                'vagas_autorizadas' => $numeroVagas,
                'vagas_excelencia' => $vExcelencia,
            ]);

        $histData = [
            'id_cargo' => $request->input('vagasCargo'),
            'id_setor' => $request->input('vagasSetor'),
            'vagas_autorizadas' => $request->input('vTotal'),
            'vagas_excelencia' => $request->input('vExcelencia'),
            'alteracao' => 'Houve mudanca'
        ];

        DB::table('hist_tp_vagas_autorizadas')
            ->where('id', $idVagas)
            ->insert($histData);

        // Redirecione de volta para a página de controle de vagas
        return redirect()->route('indexControleVagas');
    }

    public function destroy(string $idC)
    {

        DB::table('tp_vagas_autorizadas')->where('id', $idC)->delete();
        DB::table('hist_tp_vagas_autorizadas')->where('id', $idC)->update(['alteracao' => 'Deletado']);

        return redirect()->route('indexControleVagas')->with('success', 'Vagas excluídas com sucesso!');
    }
}
