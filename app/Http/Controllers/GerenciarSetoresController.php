<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\CollectionorderBy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use iluminate\Support\Facades\Route;


class GerenciarSetoresController extends Controller
{
    public function index(Request $request)
    {

        $lista_setor = DB::table('tp_nivel_setor AS tns')
            ->leftJoin('setor AS s', 'tns.id', 's.id_nivel')
            ->leftJoin('setor AS substituto', 's.substituto', 'substituto.id')
            ->leftJoin('setor AS setor_pai', 's.setor_pai', 'setor_pai.id')
            ->select(
                DB::raw('CASE WHEN s.dt_fim IS NULL THEN \'Ativo\' ELSE \'Inativo\' END AS status'),
                's.id AS ids',
                's.nome',
                's.sigla',
                's.dt_inicio',
                's.dt_fim',
                'setor_pai.nome AS setor_pai',
                'substituto.sigla AS nome_substituto'
            );



        $ids = $request->ids;

        $nome = $request->nome;

        $sigla = $request->sigla;

        $dt_inicio = $request->dt_inicio;

        $dt_fim = $request->dt_fim;

        $setor_pai = $request->setor_pai;

        $nome_substituto = $request->nome_substituto;

        if ($request->nome) {
            $lista_setor->whereRaw("UNACCENT(LOWER(s.nome)) ILIKE UNACCENT(LOWER(?))", ["%{$request->nome}%"]);
        }

        if ($request->sigla) {
            $lista_setor->whereRaw("UNACCENT(LOWER(s.sigla)) ILIKE UNACCENT(LOWER(?))", ["%{$request->sigla}%"]);
        }

        if ($request->nome_substituto) {
            $lista_setor->whereRaw("UNACCENT(LOWER(s.substituto)) ILIKE UNACCENT(LOWER(?))", ["%{$request->nome_substituto}%"]);
        }

        if ($request->dt_inicio) {
            $lista_setor->whereDate('s.dt_inicio', '=', $request->dt_inicio);
        }

        if ($request->dt_fim) {
            $lista_setor->whereDate('s.dt_fim', '=', $request->dt_fim);
        }

        if ($request->status == 1) {
            $lista_setor->whereDate('s.dt_fim', null);
        } elseif ($request->status == 2) {
            $lista_setor->whereNotNull('s.dt_fim');
        }

        $lista_setor = $lista_setor->orderBy('status', 'asc')->orderBy('s.nome', 'asc')->paginate(100);


        return view('/setores/gerenciar-setor', compact('lista_setor', 'nome', 'dt_inicio', 'dt_fim', 'sigla', 'ids', 'nome_substituto', 'setor_pai'));
    }


    public function create(Request $request)
    {
        $nivel = DB::select('select id AS idset, nome from tp_nivel_setor');


        // dd($nivel);

        return view('/setores/incluir-setor', compact('nivel'));
    }


    public function store(Request $request)
    {

        DB::table('setor')
            ->insert([
                'nome' => $request->input('nome_setor'),
                'sigla' => $request->input('sigla'),
                'dt_inicio' => $request->input('dt_inicio'),
                'id_nivel' => $request->input('nivel'),


            ]);


        app('flasher')->addSuccess('Setor cadastrado com Sucesso!');

        return redirect('/gerenciar-setor');
    }


    public function edit($ids)
    {

        $editar = DB::table('setor AS s')
            ->leftJoin('tp_nivel_setor AS tns', 's.id_nivel', '=', 'tns.id')
            ->select('s.id AS ids', 's.sigla', 's.nome', 's.dt_inicio', 's.dt_fim', 's.id_nivel', 'tns.nome AS nome_nivel')
            ->where('s.id', $ids)->get();

        $nivel = DB::select('select id AS idset, nome from tp_nivel_setor');


        return view('/setores/editar-setor', compact('editar', 'nivel'));
    }

    public function show($ids)
    {
        $query = "
    WITH RECURSIVE cte AS (
        SELECT id, nome, substituto, dt_inicio, dt_fim
        FROM setor
        WHERE substituto = :rootId
        UNION ALL
        SELECT s.id, s.nome, s.substituto, s.dt_inicio, s.dt_fim
        FROM setor s
        INNER JOIN cte ON cte.id = s.substituto
    )
    SELECT * FROM cte;
";
        $setores_anteriores = DB::select($query, ['rootId' => $ids]);

        $setores_subordinados = DB::table('setor')->where('setor_pai', $ids)->get();
        $setor = DB::table('setor')->where('id', $ids)->first();

        return view('setores.visualizar-setor', compact('setor', 'setores_anteriores', 'setores_subordinados'));
    }

    public function carregar_dados($ids)
    {

        $setor = DB::table('setor')->get();

        Session::flash('ids', $ids);

        $nome_setor = DB::table('setor')->where('setor.id', $ids)->get();

        return view('/setores/substituir-setor', compact('setor', 'nome_setor'));
    }

    public function subst(Request $request, string $ids)
    {

        $ids = session('ids');
        $up = $request->input('setor_substituto');

        $alterar_setor_pai = DB::table('setor')
            ->where('setor_pai', $ids)
            ->update([
                'setor_pai' => $up,
            ]);
        $alterar_setor_substituto = DB::table('setor')
            ->where('id', $ids)
            ->update([
                'substituto' => $up,
            ]);

        $dataFim = DB::table('setor')->where('id', $up)->get();

        DB::table('setor')->where('id', $ids)->update(['dt_fim' => $dataFim[0]->dt_inicio]);

        app('flasher')->addSuccess('Setor foi substituído com sucesso.');
        return redirect()->action([GerenciarSetoresController::class, 'index']);
    }

    public function update(Request $request, $ids)
    {

        DB::table('setor')
            ->where('id', $ids)
            ->update([
                'nome' => $request->input('nome'),
                'sigla' => $request->input('sigla'),
                'dt_inicio' => $request->input('dt_inicio'),
                'dt_fim' => $request->input('dt_fim'),
                'id_nivel' => $request->input('nivel')
            ]);

        app('flasher')->addSuccess('Edição feita com Sucesso!');

        return redirect()->action([GerenciarSetoresController::class, 'index']);
    }

    public function delete($ids)
    {

        $deletar = DB::table('setor AS s')->where('s.id', $ids)->delete();

        app('flasher')->addSuccess('O cadastro do Setor foi Removido com Sucesso.');
        return redirect()->action([GerenciarSetoresController::class, 'index']);
    }
}
