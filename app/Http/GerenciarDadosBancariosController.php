<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GerenciarDadosBancariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {


        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->select('funcionarios.id', 'funcionarios.id_pessoa', 'pessoas.nome_completo')
            ->where('funcionarios.id', "$id")
            ->first();



        $contasBancarias = DB::table('rel_dados_bancarios')
            ->join('desc_ban', 'rel_dados_bancarios.id_desc_banco', '=', 'desc_ban.id_db')
            ->join('tp_banco_ag', 'rel_dados_bancarios.id_banco_ag', '=', 'tp_banco_ag.id')
            ->join('tp_conta', 'rel_dados_bancarios.id_tp_conta', '=', 'tp_conta.id')
            ->join('tp_sub_tp_conta', 'rel_dados_bancarios.id_subtp_conta', '=', 'tp_sub_tp_conta.id')
            ->select('rel_dados_bancarios.id_funcionario', 'rel_dados_bancarios.nmr_conta', 'rel_dados_bancarios.dt_inicio',
                'rel_dados_bancarios.dt_fim', 'desc_ban.nome', 'tp_banco_ag.desc_agen', 'tp_banco_ag.agencia', 'tp_conta.nome_tipo_conta',
                'tp_sub_tp_conta.descricao', 'rel_dados_bancarios.id')
            ->where('rel_dados_bancarios.id_funcionario', '=', "$funcionario->id")
            ->get();


        return view('dadosBancarios.gerenciar-dados-bancarios', compact('funcionario', 'contasBancarias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idf)
    {
        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->select('funcionarios.id', 'funcionarios.id_pessoa', 'pessoas.nome_completo')
            ->where('funcionarios.id', "$idf")
            ->first();

        //$desc_bancos = DB::select('select * from desc_ban order by id_db');
        $desc_bancos = DB::table('desc_ban')
            ->orderBy('id_db')
            ->get();
        //$tp_banco_ags = DB::select("SELECT * FROM public.tp_banco_ag order by agencia");
        $tp_banco_ags = DB::table('tp_banco_ag')
            ->orderBy('agencia')
            ->get();
        //$tp_contas = DB::select('select * from tp_conta');
        $tp_contas = DB::table('tp_conta')
            ->get();

        $tp_sub_tp_contas = DB::table('tp_sub_tp_conta')
            ->get();

        return view('dadosBancarios.incluir-dados-bancarios', compact('funcionario', 'tp_banco_ags', 'desc_bancos', 'tp_contas', 'tp_sub_tp_contas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $idf)
    {
        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->select('funcionarios.id', 'funcionarios.id_pessoa', 'pessoas.nome_completo', 'funcionarios.dt_inicio')
            ->where('funcionarios.id', "$idf")
            ->first();


            DB::table('rel_dados_bancarios')->insert([
                'id_funcionario' => $idf,
                'id_banco_ag' => $request->input('tp_banco_ag'),
                'dt_inicio' => $request->input('dt_inicio'),
                'dt_fim' => $request->input('dt_fim'),
                'nmr_conta' => $request->input('nmr_conta'),
                'id_desc_banco' => $request->input('desc_banco'),
                'id_tp_conta' => $request->input('tp_conta'),
                'id_subtp_conta' => $request->input('tp_sub_tp_conta')
            ]);
            app('flasher')->addSuccess('O Dado Bancario foi cadastrado com sucesso');
            return redirect()->route('index.dadosbancarios.funcionario', ['id' => $idf]);

    }

    /**
     * Display the specified resource.
     */
    public
    function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(string $id)
    {

        $contaBancaria = DB::table('rel_dados_bancarios')
            ->leftJoin('desc_ban', 'rel_dados_bancarios.id_desc_banco', '=', 'desc_ban.id_db')
            ->join('tp_banco_ag', 'rel_dados_bancarios.id_banco_ag', '=', 'tp_banco_ag.id')
            ->join('tp_conta', 'rel_dados_bancarios.id_tp_conta', '=', 'tp_conta.id')
            ->join('tp_sub_tp_conta', 'rel_dados_bancarios.id_subtp_conta', '=', 'tp_sub_tp_conta.id')
            ->select(
                'rel_dados_bancarios.id_funcionario AS id_funcionario_db',
                'rel_dados_bancarios.nmr_conta AS numeroconta',
                'rel_dados_bancarios.dt_inicio',
                'rel_dados_bancarios.dt_fim',
                'tp_banco_ag.desc_agen', // Corrected alias
                'tp_banco_ag.agencia',
                'tp_banco_ag.id AS tpbag',
                'tp_conta.id AS tp_conta_id',
                'tp_conta.nome_tipo_conta',
                'tp_sub_tp_conta.descricao AS stpcontadesc',
                'tp_sub_tp_conta.id AS stpcontaid',
                'tp_conta.id AS tpcid',
                'rel_dados_bancarios.id',
                'desc_ban.id_db',
                'rel_dados_bancarios.id_banco_ag'
            )
            ->where('rel_dados_bancarios.id', '=', $id)
            ->first();


        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->select('funcionarios.id', 'funcionarios.id_pessoa', 'pessoas.nome_completo')
            ->where('funcionarios.id', "$contaBancaria->id_funcionario_db")
            ->first();

        $desc_bancos = DB::select('select * from desc_ban order by id_db');
        $tp_banco_ags = DB::select("SELECT * FROM public.tp_banco_ag order by agencia");
        $tp_contas = DB::select('select * from tp_conta');
        $tp_sub_tp_contas = DB::select('select * from tp_sub_tp_conta');

        return view('dadosBancarios.editar-dados-bancarios', compact('contaBancaria', 'desc_bancos',
            'tp_contas', 'tp_sub_tp_contas', 'funcionario', 'tp_banco_ags'));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        DB::table('rel_dados_bancarios')
            ->where('id', $id)
            ->update([
                'id_banco_ag' => $request->input('tp_banco_ag'),
                'dt_inicio' => $request->input('dt_inicio'),
                'dt_fim' => $request->input('dt_fim'),
                'nmr_conta' => $request->input('nmr_conta'),
                'id_desc_banco' => $request->input('desc_banco'),
                'id_tp_conta' => $request->input('tp_conta'),
                'id_subtp_conta' => $request->input('tp_sub_tp_conta')
            ]);

        $dados_bancarios = DB::table('rel_dados_bancarios')
            ->where('id', $id)
        ->first();


        app('flasher')->addWarning('O Dado Bancario foi editado com sucesso');
        return redirect()->route('index.dadosbancarios.funcionario', ['id' => $dados_bancarios->id_funcionario]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(string $idf)
    {
        DB::table('rel_dados_bancarios')->where('id', $idf)->delete();
        app('flasher')->addWarning('O dado Bancario Foi deletado.');
        return redirect()->back();
    }

    public function agencias($id)
    {
        $agenciasdoselect = DB::table('tp_banco_ag')
            ->where('banco', $id)
            ->orderBy('agencia')
            ->get();
        return response()->json($agenciasdoselect);
    }

}
