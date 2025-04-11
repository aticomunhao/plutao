<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GerenciarCertificadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idf)
    {
        $funcionario = DB::select("select
                            f.id,
                            p.nome_completo
                            from funcionarios f
                            left join pessoas p on f.id_pessoa = p.id
                            where f.id = $idf");

        $certificados = DB::select("select cert.id, cert.nome,cert.dt_conclusao,ga.nome_grauacad,tpne.nome_tpne,tpee.nome_tpee,tp_ent_e.nome_tpentensino from certificados as cert
        join grau_academico as ga on ga.id= cert.id_grau_acad
        join tp_nivel_ensino as tpne on tpne.id = cert.id_nivel_ensino
        join tp_etapas_ensino as tpee on tpee.id = cert.id_etapa
        join tp_entidades_ensino as tp_ent_e on tp_ent_e.id=cert.id_entidade_ensino
        where cert.id_funcionario =$idf
        ORDER BY id DESC;");



        return view(
            'certificados.gerenciar-certificados ',
            compact('certificados', 'funcionario')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idf)
    {
        $funcionario = DB::select("select
                            f.id,
                            p.nome_completo
                            from funcionarios f
                            left join pessoas p on f.id_pessoa = p.id
                            where f.id = $idf");

        $graus_academicos = DB::select("select * from grau_academico");

        $tp_niveis_ensino = DB::select("select * from tp_nivel_ensino");

        $tp_etapas_ensino = DB::select("select * from tp_etapas_ensino");
        $tp_entidades_ensino = DB::select("select * from tp_entidades_ensino");


        return view('certificados.incluir-certificados', compact('funcionario', 'graus_academicos', 'tp_niveis_ensino', 'tp_etapas_ensino', 'tp_entidades_ensino'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $idf)
    {

        $funcionario = DB::select("select f.id, p.dt_nascimento, p.nome_completo from funcionarios f left join pessoas p on f.id_pessoa = p.id where f.id = $idf");

        DB::table('certificados')->insert([
            'dt_conclusao' => $request->input('dtconc_cert'),
            'id_nivel_ensino' => $request->input('nivel_ensino'),
            'id_grau_acad' => $request->input('grau_academico'),
            'id_etapa' => $request->input('etapa_ensino'),
            'id_entidade_ensino' => $request->input('entidade_ensino'),
            'id_funcionario' => $idf,
            'nome' => $request->input('nome_curso')

        ]);
        app('flasher')->addInfo('O cadastro do Certificado foi realizado com sucesso.');
        return redirect()->route('viewGerenciarCertificados', ['id' => $idf]);
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
    public function edit($id)
    {

        $certificado = DB::table('certificados')->where('id', $id)->first();


        $funcionario = DB::select("select
                            f.id,
                            p.nome_completo
                            from funcionarios f
                            left join pessoas p on f.id_pessoa = p.id
                            where f.id = $certificado->id_funcionario");

        $graus_academicos = DB::select("select * from grau_academico");

        $tp_niveis_ensino = DB::select("select * from tp_nivel_ensino");

        $tp_etapas_ensino = DB::select("select * from tp_etapas_ensino");
        $tp_entidades_ensino = DB::select("select * from tp_entidades_ensino");


        return view('certificados.editar-certificado', compact('certificado', 'funcionario', 'graus_academicos', 'tp_niveis_ensino', 'tp_etapas_ensino', 'tp_entidades_ensino'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $certificado = $certificado = DB::table('certificados')->where('id', $id)->first();;


        $idf = DB::table('certificados')->where('id', $id)->select('id_funcionario')->first();

        $funcionario = DB::select("select
                            f.id,
                            p.nome_completo
                            from funcionarios f
                            left join pessoas p on f.id_pessoa = p.id
                            ");
        DB::table('certificados')
            ->where('id', $id)
            ->update([
                'id' => $id,
                'dt_conclusao' => $request->input('dtconc_cert'),
                'id_nivel_ensino' => $request->input('nivel_ensino'),
                'id_grau_acad' => $request->input('grau_academico'),
                'id_etapa' => $request->input('etapa_ensino'),
                'id_entidade_ensino' => $request->input('entidade_ensino'),
                'id_funcionario' => $idf->id_funcionario,
                'nome' => $request->input('nome_curso')
            ]);

        app('flasher')->addWarning('O cadastro do Certificado alterado com Sucesso.');
        return redirect()->route('viewGerenciarCertificados', ['id' => $idf->id_funcionario]);
    }

    public function destroy(string $id)
    {
        DB::table('certificados')->where('id', $id)->delete();
        app('flasher')->addWarning('O cadastro do Certificado foi Removido com Sucesso.');
        return redirect()->back();
    }
}
