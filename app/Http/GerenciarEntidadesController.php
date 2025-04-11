<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GerenciarEntidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pesquisa = request('pesquisa');//Area de pesquisa, decide se algo esta sendo pesquisado ou não

        if ($pesquisa) {
            $entidades = DB::table('tp_entidades_ensino')//faz uma pesquisa no banco apenas onde os valores batem
                ->where('nome_tpentensino', 'ilike', '%' . $pesquisa . '%')
                ->get();
        } else {
            $entidades = DB::table('tp_entidades_ensino')//Busca todas as informacoes do banco
                ->orderBy('nome_tpentensino', 'desc')
                ->get();
        }


        return view('entidadesensino.gerenciar-entidades-ensino', compact('entidades', 'pesquisa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('entidadesensino.nova-entidade-ensino');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::table('tp_entidades_ensino')->insert([//Coloca informacoes no banco
            'nome_tpentensino' => $request->input('nome_ent')
        ]);
        app('flasher')->addSuccess("Entidade cadastrada com sucesso");
        return redirect()->route('IndexGerenciarEntidades');
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
        $entidade = DB::table('tp_entidades_ensino')->where('id', $id)->first();//traz informacoes do banco para editar

        return view('entidadesensino.editar-entidade-ensino', compact('entidade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        DB::table('tp_entidades_ensino')//envia as informacoes de para o banco e atualiza
            ->where('id', $id)
            ->update([
                'id' => $id,
                'nome_tpentensino' => $request->input('nome_ent')
            ]);

        app('flasher')->addInfo("Entidade editada com sucesso");
        return redirect()->route('IndexGerenciarEntidades');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $certificados = DB::select("select * from certificados where id_entidade_ensino = $id");//seleciona informacoes do banco com ID especifico

        foreach ($certificados as $certificado) {//Testa se tem algum certificado atrelado a esta entidade de ensino

            if($id == $certificado->id_entidade_ensino){
                app('flasher')->addWarning("Não foi possivel excluir a entidade pois o certificado $certificado->nome, ainda está cadastrado");
                return redirect()->back();
            }
        }
        DB::table('tp_entidades_ensino')->where('id', $id)->delete();//Apaga a entidade de ensino
        app('flasher')->addWarning("Entidade excluida com sucesso");

        return redirect()->back();
    }
}
