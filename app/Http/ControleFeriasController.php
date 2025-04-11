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

use function Laravel\Prompts\select;

class ControleFeriasController extends Controller
{
    public function index(Request $request)
    {

        $ferias = DB::table('ferias AS fe')
            ->leftJoin('status_pedido_ferias AS stf', 'fe.status_pedido_ferias', 'stf.id')
            ->leftJoin('funcionarios AS f', 'fe.id_funcionario', 'f.id')
            ->leftJoin('pessoas AS p', 'f.id_pessoa', 'p.id')
            ->join('hist_setor', function ($join) {
                $join->on('hist_setor.id_func', '=', 'f.id')
                    ->where(function ($query) {
                        $query->whereNull('hist_setor.dt_fim')
                            ->orWhereRaw('fe.dt_ini_a BETWEEN hist_setor.dt_inicio AND hist_setor.dt_fim')
                            ->orWhereRaw('fe.dt_ini_b BETWEEN hist_setor.dt_inicio AND hist_setor.dt_fim')
                            ->orWhereRaw('fe.dt_ini_c BETWEEN hist_setor.dt_inicio AND hist_setor.dt_fim');
                    });
            })
            ->join('setor as s', 'hist_setor.id_setor', '=', 's.id')

            // ->leftJoin('base_salarial AS bs', 'f.id', 'bs.id_funcionario')
            // ->leftJoin('cargos AS c', 'bs.cargo', 'c.id')
            ->select(
                'p.id AS id_pessoa',
                'fe.id AS id_ferias',
                'f.dt_inicio AS dt_inicio_funcionario',
                'fe.ano_de_referencia AS ano_de_referencia',
                'f.id AS id_funcionario',
                'stf.id AS id_stf',
                // 'f.id_setor AS id_setor',
                's.id as id_setor',
                'p.nome_completo AS nome_completo',
                'p.nome_resumido AS nome_resumido',
                'p.status',
                'fe.inicio_periodo_aquisitivo AS ini_aqt',
                'fe.fim_periodo_aquisitivo AS fim_aqt',
                'stf.nome AS nome_stf',
                's.nome AS nome_setor',
                // 'c.nome AS nome_cargo',
                's.sigla AS sigla_setor',
                'fe.dt_ini_a AS dt_ini_a',
                'fe.dt_fim_a AS dt_fim_a',
                'fe.dt_ini_b AS dt_ini_b',
                'fe.dt_fim_b AS dt_fim_b',
                'fe.dt_ini_c AS dt_ini_c',
                'fe.dt_fim_c AS dt_fim_c',
                'fe.adianta_13sal AS adianta_13sal',
                'fe.vendeu_ferias AS vendeu_ferias',
                'fe.dt_inicio_periodo_de_licenca AS dt_inicio_gozo',
                'fe.dt_fim_periodo_de_licenca AS dt_fim_gozo',
            );


        $status = $request->status;
        $setor_selecionado = null;
        $mes_selecionado = null;
        $ano_selecionado = null;
        $mes_ferias = null;
        $setor_selecionado = $request->setor;
        $mes_selecionado = $request->mes_ferias;
        $ano_selecionado = $request->ano;
        $mes_ferias = $request->input('mes_ferias');
        $mes = [
            1 => "Janeiro",
            2 => "Fevereiro",
            3 => "Março",
            4 => "Abril",
            5 => "Maio",
            6 => "Junho",
            7 => "Julho",
            8 => "Agosto",
            9 => "Setembro",
            10 => "Outubro",
            11 => "Novembro",
            12 => "Dezembro"
        ];
        if ($request->setor) {
            $ferias->where('s.id', $setor_selecionado);
            $setor_selecionado = DB::table('setor')
                ->where('id', '=', $setor_selecionado)
                ->first();

        }
        if ($request->nomeFunc) {
            $ferias->where('p.nome_completo', 'ILIKE', '%' . $request->nomeFunc . '%');

        }
        // if ($request->input('mes_gozo_ferias')) {
        //     $ferias->whereMonth('fim_aqt', '=', $mes_gozo_ferias);

        // }
        if ($request->mes_ferias) {
            $mes_selecionado = [
                'indice' => $request->input('mes_ferias'),
                'nome' => $mes[$request->input('mes_ferias')]
            ];

            // Aplica o filtro para buscar registros onde o mês em qualquer um dos campos é igual ao mês selecionado
            $ferias->where(function ($query) use ($mes_selecionado) {
                $query->whereMonth('fe.dt_ini_a', $mes_selecionado['indice'])
                    ->orWhereMonth('fe.dt_ini_b', $mes_selecionado['indice'])
                    ->orWhereMonth('fe.dt_ini_c', $mes_selecionado['indice']);
            });

        }

        if ($request->ano) {

            $ferias->where('fe.ano_de_referencia', $ano_selecionado);
            $ano_selecionado = $request->input('ano');

        }

        if ($request->status === null) {
            $ferias->where('p.status', 1);
        } elseif ($request->status === '1') {
            $ferias->where('p.status', 1);
        } elseif ($request->status === '0') {
            $ferias->where('p.status', 0);
        }

        if ($request->dt_inicio_periodo || $request->dt_fim_periodo) {
            $ferias->where(function ($query) use ($request) {
                if ($request->dt_inicio_periodo && $request->dt_fim_periodo) {
                    // Filtra registros entre as duas datas
                    $query->whereBetween('fe.dt_ini_a', [$request->dt_inicio_periodo, $request->dt_fim_periodo])
                        ->orWhereBetween('fe.dt_ini_b', [$request->dt_inicio_periodo, $request->dt_fim_periodo])
                        ->orWhereBetween('fe.dt_ini_c', [$request->dt_inicio_periodo, $request->dt_fim_periodo]);
                } elseif ($request->dt_inicio_periodo) {
                    // Filtra registros a partir da data inicial
                    $query->where('fe.dt_ini_a', '>=', $request->dt_inicio_periodo)
                        ->orWhere('fe.dt_ini_b', '>=', $request->dt_inicio_periodo)
                        ->orWhere('fe.dt_ini_c', '>=', $request->dt_inicio_periodo);
                } elseif ($request->dt_fim_periodo) {
                    // Filtra registros até a data final
                    $query->where('fe.dt_ini_a', '<=', $request->dt_fim_periodo)
                        ->orWhere('fe.dt_ini_b', '<=', $request->dt_fim_periodo)
                        ->orWhere('fe.dt_ini_c', '<=', $request->dt_fim_periodo);
                }
            });
        }

        $ferias = $ferias->orderBy('nome_completo')->paginate(50)->appends($request->query());

        $ano = DB::table('ferias AS fe')
            ->select('fe.ano_de_referencia AS ano_de_referencia')
            ->orderBy('ano_de_referencia')
            ->distinct()
            ->get();

        $setor = DB::table('setor AS s')
            ->select(
                's.nome AS nome_setor',
                's.id AS idSetor',
                's.sigla AS siglaSetor',
            )
            ->orderBy('nome_setor',)
            ->distinct()
            ->get();
        //dd($setor);
        //dd($ano);
        // dd($mes_selecionado);
        //  dd($setor_selecionado != null);
        return view('ferias.controle-ferias', compact('ferias', 'mes', 'ano', 'setor', 'setor_selecionado', 'mes_ferias', 'mes_selecionado', 'ano_selecionado'));
    }
}
