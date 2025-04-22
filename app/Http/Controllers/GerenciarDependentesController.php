<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GerenciarDependentesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idf)
    {
        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'pessoas.id', '=', 'funcionarios.id_pessoa')
            ->where('funcionarios.id', $idf)
            ->select('pessoas.cpf', 'pessoas.nome_completo', 'funcionarios.id')->first();
        //dd($funcionario);

        $identificadorDep = DB::select("select nome_dependente from dependentes where id_funcionario = $idf");


        $dependentes = DB::select("select
                            d.id,
                            d.id_funcionario,
                            d.id_parentesco,
                            d.nome_dependente,
                            d.dt_nascimento,
                            d.cpf,
                            tpp.nome
                            from dependentes d
                            left join tp_parentesco tpp on d.id_parentesco = tpp.id
                            where d.id_funcionario = $idf");


        return view('dependentes.gerenciar-dependentes', compact('funcionario', 'dependentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {

        $funcionario_atual = DB::select("select f.id, p.nome_completo from funcionarios f left join pessoas p on f.id_pessoa = p.id where f.id = $id");
        $tp_relacao = DB::select("select * from tp_parentesco");

        return view('dependentes.incluir-dependente', compact('funcionario_atual', 'tp_relacao'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'pessoas.id', '=', 'funcionarios.id_pessoa')
            ->where('funcionarios.id', $id)
            ->select('pessoas.cpf', 'pessoas.nome_completo', 'funcionarios.id', 'pessoas.dt_nascimento')
            ->first();

        $dependentes = DB::table('dependentes')->get();



        foreach ($dependentes as $dependente) {
            if ($request->input('cpf_dep') == $dependente->cpf) {
                app('flasher')->addError('Existe outro cadastro usando este número de CPF');
                return redirect()->route('IndexGerenciarDependentes', ['id' => $id]);
            } elseif ($request->input('relacao_dep') == 6 && Carbon::parse($request->input('dtnasc_dep'))->format('Y-m-d')
                <= Carbon::parse($funcionario->dt_nascimento)->format('Y-m-d')) {
                app('flasher')->addError('A data de nascimento do dependente é mais nova ou igual à do funcionário');
                return redirect()->route('IndexGerenciarDependentes', ['id' => $id]);
            }

        }

        DB::table('dependentes')->insert([
            'nome_dependente' => $request->input('nomecomp_dep'),
            'dt_nascimento' => $request->input('dtnasc_dep'),
            'cpf' => $request->input('cpf_dep'),
            'id_funcionario' => $id,
            'id_parentesco' => $request->input('relacao_dep')
        ]);

        app('flasher')->addInfo('O cadastro do dependente foi realizado com sucesso.');
        return redirect()->route('IndexGerenciarDependentes', ['id' => $id]);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dependente = DB::table('dependentes')
            ->join('tp_parentesco', 'dependentes.id_parentesco', '=', 'tp_parentesco.id')
            ->where('dependentes.id', $id)
            ->select(
                'dependentes.id_funcionario',
                'dependentes.id_parentesco',
                'dependentes.id',
                'tp_parentesco.id as id_tp_parentesco',
                'tp_parentesco.nome',
                'dependentes.nome_dependente',
                'dt_nascimento',
                'dependentes.cpf'
            )
            ->first();
        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'pessoas.id', '=', 'funcionarios.id_pessoa')
            ->where('funcionarios.id', $dependente->id_funcionario)
            ->select('pessoas.cpf', 'pessoas.nome_completo', 'funcionarios.id')->first();
        // $funcionario = DB::select("select f.id, p.nome_completo from funcionarios f left join pessoas p on f.id_pessoa = p.id where f.id = $dependente->id_funcionario");

        $tp_relacao = DB::select("select * from tp_parentesco");


        return view('dependentes.editar-dependentes', compact('dependente', 'tp_relacao', 'funcionario', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $dependente = Db::table('dependentes')->where('id', $id)->first();

        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'pessoas.id', '=', 'funcionarios.id_pessoa')
            ->where('funcionarios.id', $dependente->id_funcionario)
            ->select('pessoas.cpf', 'pessoas.nome_completo', 'funcionarios.id')->first();


        if ($request->input('cpf_dep') <> $dependente->cpf) {
            $dependentes = DB::select('select * from dependentes');
            foreach ($dependentes as $dependente) {

                if (intval($request->input('cpf_dep')) == $dependente->cpf) {
                    app('flasher')->addError('Existe outro cadastro usando este número de CPF');
                    return redirect()->route('IndexGerenciarDependentes', ['id' => $funcionario->id]);
                } elseif (intval($request->input('relacao_dep')) == 6 && intval($request->input('dtnasc_dep')) <= intval($funcionario->dt_nascimento)) {
                    app('flasher')->addError('A data do Filho cadastrado é mais velha ou igual a do funcionario');
                    return redirect()->route('IndexGerenciarDependentes', ['id' => $funcionario->id]);
                }
            }
        }


        $idf = DB::table('dependentes AS d')->where('id', $id)->select('id_funcionario')->value('id_funcionario');

        DB::table('dependentes')
            ->where('id', $id)
            ->update([
                'nome_dependente' => $request->input('nomecomp_dep'),
                'cpf' => $request->input('cpf_dep'),
                'dt_nascimento' => $request->input('dtnasc_dep'),
                'id_parentesco' => $request->input('relacao_dep')
            ]);
        app('flasher')->addWarning('O cadastro do Dependente  foi alterado com Sucesso.');
        return redirect()->route('IndexGerenciarDependentes', ['id' => $funcionario->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy($id)
    {

        DB::table('dependentes')
            ->where('id', $id)
            ->delete();
        app('flasher')
            ->addWarning('O cadastro do Dependente foi Removido com Sucesso.');
        return redirect()->back();
    }
}
