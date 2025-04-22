<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GerenciarPerfil extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $perfis = DB::table('tp_perfil');

        if($request->nome_pesquisa){
            $perfis =$perfis->where('nome', 'ilike', "%$request->nome_pesquisa%");
        }

        $perfis = $perfis->orderBy('nome')->get();
        return view('perfis.gerenciar-perfil', compact('perfis'));



        }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $rotas = DB::table('tp_rotas_jupiter')->orderBy('tp_rotas_jupiter.nome', 'ASC')->get();
        return view('perfis.criar-perfil', compact('rotas'));


        }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $perfil = DB::table('tp_perfil')->insertGetId([
            'nome' => $request->nome
        ]);

        foreach($request->rotas as $rota){
            DB::table('tp_rotas_perfil')->insert([
                'id_perfil' => $perfil,
                'id_rotas' => $rota
            ]);
        }


        return redirect('/gerenciar-perfis');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {


        $perfil = DB::table('tp_perfil')->where('id',$id)->first();
        $rotas = DB::table('tp_rotas_perfil')->leftJoin('tp_rotas_jupiter', 'tp_rotas_perfil.id_rotas', 'tp_rotas_jupiter.id')->where('id_perfil',$id)->orderBy('tp_rotas_jupiter.nome', 'ASC')->get();

        return view('perfis.visualizar-perfil', compact('perfil', 'rotas'));


        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $perfil = DB::table('tp_perfil')->where('id',$id)->first();

        $rotas = DB::table('tp_rotas_jupiter')->get();
        $rotasSelecionadas = DB::table('tp_rotas_perfil')->leftJoin('tp_rotas_jupiter', 'tp_rotas_perfil.id_rotas', 'tp_rotas_jupiter.id')->where('id_perfil',$id)->orderBy('tp_rotas_jupiter.nome', 'ASC')->pluck('id_rotas');


        return view('perfis.editar-perfil', compact('perfil', 'rotas', 'rotasSelecionadas'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
        DB::table('tp_perfil')->where('id', $id)->update([
            'nome' => $request->nome
        ]);

        DB::table('tp_rotas_perfil')->where('id_perfil', $id)->delete();

        foreach($request->rotas as $rota){

            DB::table('tp_rotas_perfil')->where('id_perfil', $id)->insert([
                'id_perfil' => $id,
                'id_rotas' => $rota
            ]);
        }


        return redirect('/gerenciar-perfis');

    }
    catch(\Exception $e){

        $code = $e->getCode( );
        return view('administrativo-erro.erro-inesperado', compact('code'));
            }
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        DB::table('tp_rotas_perfil')->where('id_perfil', $id)->delete();
        DB::table('tp_perfil')->where('id', $id)->delete();
        return redirect('/gerenciar-perfis');

    }

}
