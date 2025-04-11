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

class GerenciarHierarquiaController extends Controller
{
    public function index(Request $request)
    {
        $nivel = DB::table('tp_nivel_setor')
            ->select('tp_nivel_setor.id AS id_nivel', 'tp_nivel_setor.nome as nome_nivel')
            ->get();

        $setor = DB::table('setor')
            ->leftJoin('setor AS substituto', 'setor.substituto', '=', 'substituto.id')
            ->select('setor.id AS id_setor', 'setor.nome AS nome_setor', 'setor.sigla', 'setor.dt_inicio', 'substituto.sigla AS nome_substituto')
            ->get();


        $lista = DB::table('tp_nivel_setor AS tns')
            ->leftJoin('setor AS st', 'tns.id', '=', 'st.id_nivel')
            ->leftJoin('setor AS substituto', 'st.substituto', '=', 'substituto.id')
            ->leftJoin('setor AS setor_pai', 'st.setor_pai', '=', 'setor_pai.id')
            ->select(
                DB::raw('CASE WHEN st.dt_fim IS NULL THEN \'Ativo\' ELSE \'Inativo\' END AS status'),
                'st.id AS ids',
                'st.nome',
                'st.sigla',
                'st.dt_inicio',
                'st.dt_fim',
                'st.id_nivel',
                'st.nome AS nome_setor',
                'setor_pai.nome AS st_pai',
                'substituto.sigla AS nome_substituto'
            );


        $nm_nivel = $request->nivel;
        $nome_setor = $request->nome_setor;
        $id_nivel = $request->id_nivel;
        $st_pai = $request->st_pai;




        if ($nome_setor == 1) {
            $lista->where('st.id_nivel', '>=', 2)->whereNull('st.setor_pai')->orwhere('st.id', $request->nome_setor);



            if ($nm_nivel == 1) {
                $lista->orWhere('st.setor_pai', '=', $request->nome_setor);
            }

            if ($nm_nivel == 2) {
                $lista->orWhere('st.setor_pai', '=', $request->nome_setor);
            }
        }
            else{
                $lista->where('st.id_nivel', '>', 2)->whereNull('st.setor_pai')->orwhere('st.id', $request->nome_setor);


                if ($nm_nivel == 1) {
                    $lista->orWhere('st.setor_pai', '>=', $request->nome_setor);
                }

                if ($nm_nivel == 2) {
                    $lista->orWhere('st.setor_pai', '=', $request->nome_setor);
                }

            }




        $lista = $lista->orderby('tns.id', 'ASC')->orderBy('st.setor_pai', 'ASC')->get();

        Session::flash('listas', $lista);

        Session::flash('nome_setor', $nome_setor);


        return view('/setores/Gerenciar-hierarquia', compact('nome_setor', 'nivel', 'lista', 'st_pai', 'nm_nivel', 'setor'));
    }



    public function obterSetoresPorNivel($id_nivel)
    {
        $set = DB::table('setor as s')
            ->where('id_nivel', $id_nivel)
            ->select('s.nome', 's.id')
            ->get();

        if ($set->isNotEmpty()) {
            return response()
                ->json($set);
        }

        return response()->json(['message' => 'Nenhum setor encontrado para o ID de nível fornecido']);
    }

    public function show(Request $request)
    {

        $nivel = $request->input('nivel');
        $nome_set = $request->input('nome_setor');


        $setor = DB::table('setor')->get();

        $id_nivel_setor = DB::table('setor')
            ->where('id', $nome_set)
            ->value('id_nivel');



        foreach ($setor as $setores) {
            if ($nome_set == $setores->id or $nome_set == $setores->setor_pai) {
                $lista1 = DB::table('tp_nivel_setor AS tns')
                    ->leftJoin('setor AS s', 'tns.id', '=', 's.id_nivel')
                    ->leftJoin('setor AS substituto', 's.substituto', '=', 'substituto.id')
                    ->leftJoin('setor AS setor_pai', 's.setor_pai', '=', 'setor_pai.id')
                    ->where('s.id',  $nome_set)
                    ->select(
                        's.id AS ids',
                        's.nome',
                        's.sigla',
                        's.dt_inicio',
                        's.dt_fim',
                        's.status',
                        's.setor_pai',
                        'substituto.sigla AS nome_substituto'
                    )->get();


                $lista2 = DB::table('setor AS s')->where('s.setor_pai', $nome_set)->get();


                $lista3 = DB::table('setor AS s')
                    ->join('tp_nivel_setor AS n', 's.id_nivel', '=', 'n.id')
                    ->where('n.id', '>', $id_nivel_setor)
                    ->whereNull('s.setor_pai')
                    ->select('s.*')->get();


                $lista = [
                    $lista1,
                    $lista2,
                    $lista3,
                ];


                dd($lista);

                $id_nivel = $request->id_nivel;
                $id_setor = $request->id_setor;
                $sigla = $request->sigla;
                $status = $request->status;
                $dt_inicio = $request->dt_inicio;
                $nome_substituto = $request->nome_substituto;
                $nome_nivel = $request->nome_nivel;
                $nome_setor = $request->nome_setor;

                if ($request->id_setor) {
                    $setor->where('id_setor', $request->id_setor);
                }

                if ($request->id_nivel) {
                    $nivel->where('id_nivel', $request->id_nivel);
                }

                if ($request->status) {
                    $status->where('status', $request->status);
                }

                if ($request->sigla) {
                    $sigla->where('sigla', $request->sigla);
                }

                if ($request->nome_nivel) {
                    $nivel->where('nome_nivel',  'LIKE', '%' . $request->nome_nivel . '%');
                }

                if ($request->nome_setor) {
                    $setor->where('nome_setor',  'LIKE', '%' . $request->nome_nivel . '%');
                }

                $nivel = $nivel->orderBy('id_nivel', 'asc')->orderBy('nome_nivel');
                $lista = $setor->orderBy('status', 'asc')->orderBy('nome_setor');


                return view('/setores/Gerenciar-hierarquia', compact('nome_nivel', 'nivel', 'lista', 'nome_setor', 'id_nivel', 'id_setor', 'sigla', 'dt_inicio', 'status', 'nome_substituto'));
            }
        }
    }

    public function atualizarhierarquia(Request $request)
    {

        $nome_setor = session('nome_setor');
        $lista = session('listas') ?? [];
        $checkboxesMarcados = $request->input('checkboxes');

        $checkboxesArray = explode(',', $checkboxesMarcados);

        // Converte os valores para inteiros no array original
        foreach ($checkboxesArray as &$valor) {
            $valor = intval($valor);
        }

        foreach ($lista as $index => $listaItem) {
            // Verifica se o ID está presente nos checkboxes marcados
            $idPresente = in_array($listaItem->ids, $checkboxesArray);

            // Atualiza setor_pai com base nas condições
            if ($index !== 0) {
                DB::table('setor')
                    ->where('id', $listaItem->ids)
                    ->update([
                        'setor_pai' => $idPresente ? $nome_setor : null
                    ]);
            }
        }





        return redirect('/gerenciar-hierarquia')->with('success', 'Setores atualizados com sucesso!');
    }
}
