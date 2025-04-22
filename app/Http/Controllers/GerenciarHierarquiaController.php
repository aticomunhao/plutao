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
            // 1. Carrega todos os níveis disponíveis
        $nivel = DB::table('tp_nivel_setor')
        ->select('tp_nivel_setor.id AS id_nivel', 'tp_nivel_setor.nome as nome_nivel')
        ->get();

        // 2. Carrega setores do nível selecionado (ou todos, se nada for selecionado ainda)
        $setor = collect();
        if ($request->nivel) {
            $setor = DB::table('setor')
                ->where('id_nivel', $request->nivel)
                ->select('id AS id_setor', 'nome AS nome_setor')
                ->get();
        }

        // Parâmetros recebidos do formulário
        $nm_nivel = $request->nivel_pai;
        $nome_setor = $request->setor_pai;

        // 3. Consulta principal da grid
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
                'setor_pai.id AS id_pai',
                'substituto.sigla AS nome_substituto'
            );

            if($nome_setor > 0 ){
                $lista->where('st.id', $nome_setor)
                        ->orwhere('setor_pai.id', $nome_setor);
            }

        $lista = $lista->orderBy('tns.id', 'ASC')
                    ->orderBy('st.setor_pai', 'ASC')
                    ->paginate(12);

        // 6. Envia os dados para a view
        return view('/setores/gerenciar-hierarquia', compact('nome_setor', 'nivel', 'lista', 'nm_nivel', 'setor'));
    }



    public function obterSetoresPorNivel($id_nivel)
    {
        try {
            $setores = DB::table('setor')
                ->where('id_nivel', $id_nivel)
                ->select('id', 'nome')
                ->orderBy('nome')
                ->get();

            return response()->json($setores);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar setores.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

        public function edit(Request $request, $ids)
        {         


            $ss_pai = DB::table('setor as s')
                            ->where('s.id', $ids)->value('s.sigla');

            $sn_pai = DB::table('tp_nivel_setor as n')
                            ->leftJoin('setor AS s', 'n.id', 's.id_nivel' )
                            ->where('s.id', $ids)->value('n.nome');
                        
            if ($ids == null) {
                app('flasher')->addWarning('Selecione um setor antes de pesquisar!!!');
                return redirect()->back();

            }
            
            // 1. Setor selecionado (vai aparecer no topo da lista, desabilitado)
            $setorSelecionado = DB::table('setor as s')
            ->leftJoin('tp_nivel_setor AS n', 's.id_nivel','n.id')
            ->leftJoin('setor AS substituto', 's.substituto','substituto.id')
            ->leftJoin('setor AS setor_pai', 's.setor_pai', '=', 'setor_pai.id')
                ->select(
                    's.id AS ids',
                    's.nome AS nome_setor',
                    's.sigla AS sigla_setor',
                    's.dt_inicio',
                    'substituto.sigla AS nome_substituto',
                    's.setor_pai',
                    'setor_pai.sigla AS sigla_pai',
                    DB::raw('CASE WHEN s.dt_fim IS NULL THEN \'Ativo\' ELSE \'Inativo\' END AS status')
                )
                ->where('s.id', $ids)
                ->first();
        
            $setoresCandidatos = DB::table('setor AS s')
                ->leftJoin('tp_nivel_setor AS n', 's.id_nivel','n.id')
                ->leftJoin('setor AS substituto', 's.substituto','substituto.id')
                ->leftJoin('setor AS setor_pai', 's.setor_pai', 'setor_pai.id')
                ->select(
                    DB::raw('CASE WHEN s.dt_fim IS NULL THEN \'Ativo\' ELSE \'Inativo\' END AS status'),
                    's.id AS ids',
                    's.nome AS nome_setor',
                    's.sigla AS sigla_setor',
                    's.dt_inicio',
                    'substituto.sigla AS nome_substituto',
                    'setor_pai.sigla AS sigla_pai',
                    's.setor_pai AS id_pai'
                )
                ->where('s.id_nivel', '>', $ids)
                ->orwhere('s.id_nivel', 1)                                
                ->where('s.setor_pai', $ids)
                ->orwhereNull('s.setor_pai')
                ->get();

           // dd($setoresCandidatos);
            
            // Junta tudo em uma collection
            $result = collect([$setorSelecionado])
                ->merge($setoresCandidatos);

               // dd($result);

            return view('setores.editar-hierarquia', compact('ids','result', 'ss_pai', 'sn_pai'));

    }

    public function atualizarhierarquia(Request $request, $ids)
    {



        $checkboxesMarcados = array_filter(array_map('intval', $request->input('setores', [])));
        
        //dd($checkboxesMarcados);

        DB::table('setor AS s')
            ->whereIn('s.id', $checkboxesMarcados)
            ->update(['setor_pai' => $ids]);

    
        return redirect()->route('gerenciar-hierarquia');
        app('flasher')->addSuccess('Hierarquia(s) atualizada(s) com sucesso!!!');
    }
}
