<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GerenciarFeriasController extends Controller
{
    // Adicione isso no topo do seu arquivo se ainda não estiver incluído

    public function index(Request $request)
    {
        // Recupera os registros de férias com as devidas junções
        $periodo_aquisitivo = DB::table('ferias')
            ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
            ->leftJoin('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->leftJoin('status_pedido_ferias', 'ferias.status_pedido_ferias', '=', 'status_pedido_ferias.id')
            ->join('hist_setor', function ($join) {
                $join->on('hist_setor.id_func', '=', 'funcionarios.id')
                    ->where(function ($query) {
                        $query->whereNull('hist_setor.dt_fim')
                            ->orWhereRaw('ferias.dt_ini_a BETWEEN hist_setor.dt_inicio AND hist_setor.dt_fim')
                            ->orWhereRaw('ferias.dt_ini_b BETWEEN hist_setor.dt_inicio AND hist_setor.dt_fim')
                            ->orWhereRaw('ferias.dt_ini_c BETWEEN hist_setor.dt_inicio AND hist_setor.dt_fim');
                    });
            })
            ->join('setor', 'hist_setor.id_setor', '=', 'setor.id')
            ->select(
                'pessoas.nome_completo as nome_completo_funcionario',
                'pessoas.id as id_pessoa',
                'ferias.dt_ini_a',
                'ferias.dt_fim_a',
                'ferias.dt_ini_b',
                'ferias.dt_fim_b',
                'ferias.dt_ini_c',
                'ferias.dt_fim_c',
                'ferias.motivo_retorno',
                'ferias.id as id_ferias',
                'ferias.venda_um_terco',
                'ferias.ano_de_referencia',
                'ferias.id_funcionario',
                'status_pedido_ferias.id as id_status_pedido_ferias',
                'status_pedido_ferias.nome as status_pedido_ferias',
                'setor.id as id_setor',
                'setor.nome as nome_setor',
                'hist_setor.dt_inicio as dt_inicio_setor',
                'hist_setor.dt_fim as dt_fim_setor'
            )
            ->whereIn('setor.id', session('usuario.setor', [])) // Verifica se a sessão retorna um array válido
            ->orderBy('pessoas.nome_completo') // Ordena pelo nome completo
            ->orderBy('pessoas.id') // Garantia de ordenação única
        ;
        // ->get();



        //    dd($periodo_aquisitivo);

        $ano_consulta = null;
        $nome_funcionario = null;
        $status_consulta_atual = null;
        //Começo a filtrar por periodo e setor interno

        // $periodo_aquisitivo = $periodo_aquisitivo->;




        // Filtros
        $ano_referente = $request->input('anoconsulta');
        if ($ano_referente === '*') {
            $ano_consulta = null;
        } else {
            $ano_referente = $ano_referente ?? $periodo_aquisitivo->max('ferias.ano_de_referencia');
            $periodo_aquisitivo->where('ferias.ano_de_referencia', $ano_referente);
            $ano_consulta = $ano_referente;
        }

        // Filtrando por nome do funcionário (caso fornecido)
        if ($request->filled('nomefuncionario')) {
            $nome_funcionario = $request->input('nomefuncionario');
            $periodo_aquisitivo->where('pessoas.nome_completo', 'ilike', '%' . $nome_funcionario . '%');
        }

        // Filtrando por status da consulta (caso fornecido)
        $status_consulta = $request->input('statusconsulta');
        if ($status_consulta) {
            $periodo_aquisitivo->where('status_pedido_ferias.id', $status_consulta);
            $status_consulta_atual = DB::table('status_pedido_ferias')->where('id', $status_consulta)->first();
        }

        // Debug da consulta


        $periodo_aquisitivo = $periodo_aquisitivo->get();

        // Verifica conflitos de períodos
        foreach ($periodo_aquisitivo as $p1) {
            $p1->em_conflito = false;
            foreach ($periodo_aquisitivo as $p2) {
                // Verifique se as datas de $p2 não são nulas e se $p1 tem datas válidas
                if ($p1->id_ferias != $p2->id_ferias && $this->hasValidDates($p1) && $this->hasValidDates($p2)) {
                    // Verifique conflitos para todos os períodos de $p1
                    if ($this->hasDateConflict($p1, $p2)) {
                        $p1->em_conflito = true;
                        $p2->em_conflito = true;
                        break; // Se já encontrou um conflito, não precisa verificar mais
                    }
                }
            }
        }




        // Recupera anos e status possíveis
        $anos_possiveis = DB::table('ferias')->select('ano_de_referencia')->groupBy('ano_de_referencia')->get();
        $status_ferias = DB::table('status_pedido_ferias')->get();


        return view('ferias.gerenciar-ferias', compact(
            'periodo_aquisitivo',
            'anos_possiveis',
            'status_ferias',
            'ano_consulta',
            'nome_funcionario',
            'status_consulta_atual',

        ));
    }

    private function hasDateConflict($p1, $p2)
    {
        // Verificar conflito entre todos os períodos possíveis de $p1 e $p2
        return ($this->isPeriodInConflict($p1->dt_ini_a, $p1->dt_fim_a, $p2->dt_ini_a, $p2->dt_fim_a, $p2->dt_ini_b, $p2->dt_fim_b, $p2->dt_ini_c, $p2->dt_fim_c) ||
            $this->isPeriodInConflict($p1->dt_ini_b, $p1->dt_fim_b, $p2->dt_ini_a, $p2->dt_fim_a, $p2->dt_ini_b, $p2->dt_fim_b, $p2->dt_ini_c, $p2->dt_fim_c) ||
            $this->isPeriodInConflict($p1->dt_ini_c, $p1->dt_fim_c, $p2->dt_ini_a, $p2->dt_fim_a, $p2->dt_ini_b, $p2->dt_fim_b, $p2->dt_ini_c, $p2->dt_fim_c));
    }


    private function hasValidDates($periodo)
    {
        return !is_null($periodo->dt_ini_a) && !is_null($periodo->dt_fim_a) ||
            !is_null($periodo->dt_ini_b) && !is_null($periodo->dt_fim_b) ||
            !is_null($periodo->dt_ini_c) && !is_null($periodo->dt_fim_c);
    }

    function isPeriodInConflict($periodStart, $periodEnd, $start1, $end1, $start2, $end2, $start3, $end3)
    {
        // Verifica se algum dos períodos é nulo e, se for, ignora a verificação para esse período
        if (is_null($start1) || is_null($end1)) $start1 = $end1 = null;
        if (is_null($start2) || is_null($end2)) $start2 = $end2 = null;
        if (is_null($start3) || is_null($end3)) $start3 = $end3 = null;

        // Se o período em questão é nulo, não pode haver conflito
        if (is_null($periodStart) || is_null($periodEnd)) {
            return false;
        }

        // Se o período é menor do que qualquer um dos intervalos válidos, não há conflito
        if (($start1 && $periodEnd < $start1) || ($start2 && $periodEnd < $start2) || ($start3 && $periodEnd < $start3)) {
            return false;
        }

        // Se o período começa depois do fim de qualquer intervalo válido, não há conflito
        if (($end1 && $periodStart > $end1) || ($end2 && $periodStart > $end2) || ($end3 && $periodStart > $end3)) {
            return false;
        }

        // Verifica conflito com os intervalos válidos
        $conflict = false;
        if ($start1 && $end1) {
            $conflict = $periodStart <= $end1 && $periodEnd >= $start1;
        }
        if ($start2 && $end2) {
            $conflict = $conflict || ($periodStart <= $end2 && $periodEnd >= $start2);
        }
        if ($start3 && $end3) {
            $conflict = $conflict || ($periodStart <= $end3 && $periodEnd >= $start3);
        }

        return $conflict;
    }

    /**
     * Show the form for creating a new resource.
     */
    public
    function create($id_ferias)
    {
        try {
            $ano_referente = Carbon::now()->year - 1;
            $periodo_aquisitivo = DB::table('ferias')
                ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
                ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
                ->join('status_pedido_ferias', 'ferias.status_pedido_ferias', '=', 'status_pedido_ferias.id')
                ->select(
                    'pessoas.nome_completo as nome_completo_funcionario',
                    'pessoas.id as id_pessoa',
                    'ferias.dt_ini_a',
                    'ferias.dt_fim_a',
                    'ferias.dt_ini_b',
                    'ferias.dt_fim_b',
                    'ferias.dt_ini_c',
                    'ferias.dt_fim_c',
                    'ferias.id as id_ferias',
                    'ferias.motivo_retorno',
                    'funcionarios.dt_inicio',
                    'ferias.ano_de_referencia',
                    'ferias.id_funcionario',
                    'status_pedido_ferias.id as id_status_pedido_ferias',
                    'status_pedido_ferias.nome as status_pedido_ferias',
                    'ferias.dt_fim_periodo_de_licenca',
                    'ferias.dt_inicio_periodo_de_licenca',
                    'ferias.inicio_periodo_aquisitivo',
                    'ferias.fim_periodo_aquisitivo'
                )
                ->where('ferias.id', $id_ferias)
                ->first();

            return view('ferias.incluir-ferias', compact('ano_referente', "periodo_aquisitivo", 'id_ferias'));
        } catch (Exception $e) {
            DB::rollBack();
            app('flasher')->addError("Houve um erro inesperado: #" . $e->getCode());
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public
    function store(Request $request, $id) {}

    /**
     * Display the specified resource.
     */
    /*Historico de Devoluções*/
    public
    function show(string $id)
    {
        try {
            $ano_referencia = Carbon::now()->year - 1;

            $periodo_de_ferias = DB::table('ferias')->where('id', '=', $id)->first();



            $funcionario = DB::table('funcionarios')
                ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
                ->where('funcionarios.id', '=', $periodo_de_ferias->id_funcionario)
                ->select(
                    'pessoas.id as id_pessoa',
                    'funcionarios.id as id_funcionario',
                    'pessoas.nome_completo',
                    'funcionarios.dt_inicio'
                )->first();


            $historico_recusa_ferias = DB::table('hist_recusa_ferias')->where('id_periodo_de_ferias', '=', $id)->get();

            if (empty($historico_recusa_ferias)) {
                app('flasher')->addInfo("Não há nenhuma informação das férias do funcionário:  $funcionario->nome_completo.");
                return redirect()->back();
            }
            //
            // dd($periodo_de_ferias);


            $dias_limite_para_periodo_de_ferias = DB::table('hist_dia_limite_de_ferias')->where('data_fim', '=', null)->first();
            // dd($dias_limite_para_periodo_de_ferias);
            $periodo_de_ferias->dia_limite_para_gozo_de_ferias = Carbon::parse($periodo_de_ferias->dt_inicio_periodo_de_licenca)->addDays($dias_limite_para_periodo_de_ferias->dias)->toDateString();

            return view('ferias.historico-ferias', compact('periodo_de_ferias', 'historico_recusa_ferias', 'funcionario', 'dias_limite_para_periodo_de_ferias'));
        } catch (Exception $exception) {
            DB::rollBack();
            app('flasher')->addError($exception->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(string $id)
    {

        $periodo_de_ferias_funcionario = DB::table('ferias')
            ->leftJoin('funcionarios as f', 'ferias.id_funcionario', '=', 'f.id')
            ->leftJoin('pessoas as p', 'f.id_pessoa', '=', 'p.id')
            ->where('ferias.id', $id)
            ->select(
                'ferias.ano_de_referencia as ano',
                'p.nome_completo as nome'
            )

            ->first();
        DB::table('hist_recusa_ferias')
            ->where('id_periodo_de_ferias', $id)
            ->delete();
        DB::table('ferias')->where('id', $id)->delete();

        app('flasher')->addSuccess('Ferias do Funcionario: ' . $periodo_de_ferias_funcionario->nome . ', referentes ao ano de ' . $periodo_de_ferias_funcionario->ano);
        return redirect()->back();
    }

    public function InsereERetornaFuncionarios(Request $request)
    {
        $ano_referencia = $request->input('ano_referencia', Carbon::now()->year - 1);

        $dia_do_ultimo_ano = Carbon::createFromDate($ano_referencia, 12, 31)->toDateString();

        $funcionarios = DB::table('funcionarios')
            ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->join('contrato as c', 'funcionarios.id', '=', 'c.id_funcionario')
            ->leftJoin('afastamento', 'funcionarios.id', '=', 'afastamento.id_funcionario')
            ->where('c.dt_inicio', '<', $dia_do_ultimo_ano)
            ->whereIn('c.tp_contrato',  [1, 5])
            ->whereNull('c.dt_fim')
            ->where(function ($query) {
                $query->whereNotNull('afastamento.dt_inicio')
                    ->orWhereNull('afastamento.dt_inicio');
            })
            ->select(
                'pessoas.id as id_pessoa',
                'funcionarios.id as id_funcionario',
                'c.dt_inicio as data_de_inicio',
                'pessoas.nome_completo'
            )
            ->orderByRaw('COALESCE(pessoas.nome_completo, \' \') ASC')
            ->get();

        DB::beginTransaction();



        foreach ($funcionarios as $funcionario) {
            $periodo_de_ferias_do_funcionario = DB::table('ferias')
                ->where('id_funcionario', '=', $funcionario->id_funcionario)
                ->where('ano_de_referencia', '=', $ano_referencia)
                ->first();

            if (is_null($periodo_de_ferias_do_funcionario)) {
                // dd($funcionario);

                $data_inicio = Carbon::parse($funcionario->data_de_inicio);
                $dias_afastado = $this->calcularDiasDeAfastamento($funcionario->id_funcionario);

                $data_inicio = $data_inicio->addDays($dias_afastado);

                $data_inicio_periodo_aquisitivo = $data_inicio->copy()->subYear()->year($ano_referencia)->toDateString();
                $data_fim_periodo_aquisitivo = $data_inicio->copy()->subYear()->year($ano_referencia + 1)->subDay()->toDateString();

                $funcionario->data_inicio_periodo_aquisitivo = $data_inicio_periodo_aquisitivo;
                $funcionario->data_fim_periodo_aquisitivo = $data_fim_periodo_aquisitivo;
                $funcionario->data_inicio_periodo_de_gozo = $data_inicio->copy()->addYear()->year($ano_referencia + 1)->toDateString();
                $funcionario->data_fim_periodo_de_gozo = $data_inicio->copy()->addYear()->year($ano_referencia + 2)->subDay()->toDateString();

                $id_ferias = DB::table('ferias')->insertGetId([
                    'ano_de_referencia' => $ano_referencia,
                    'inicio_periodo_aquisitivo' => $funcionario->data_inicio_periodo_aquisitivo,
                    'fim_periodo_aquisitivo' => $funcionario->data_fim_periodo_aquisitivo,
                    'status_pedido_ferias' => 1,
                    'id_funcionario' => $funcionario->id_funcionario,
                    'dt_inicio_periodo_de_licenca' => $funcionario->data_inicio_periodo_de_gozo,
                    'dt_fim_periodo_de_licenca' => $funcionario->data_fim_periodo_de_gozo
                ]);

                DB::table('hist_recusa_ferias')->insert([
                    'id_periodo_de_ferias' => $id_ferias,
                    'motivo_retorno' => "Criação do Formulario de Férias",
                    'data_de_acontecimento' => Carbon::now()->toDateString()
                ]);

                $esta_afastado = DB::table('afastamento')
                    ->where('id_funcionario', '=', $funcionario->id_funcionario)
                    ->whereNull('dt_fim')
                    ->exists();

                if ($esta_afastado == true) {

                    DB::table('ferias')->where('id', '=', $id_ferias)->update([
                        'status_pedido_ferias' => 7
                    ]);
                    DB::table('hist_recusa_ferias')->insert([
                        'id_periodo_de_ferias' => $id_ferias,  // Usando o nome correto da coluna
                        'motivo_retorno' => 'Funcionario está afastado',
                        'data_de_acontecimento' => Carbon::now()->toDateString()
                    ]);
                }
            }
        }

        DB::commit();

        // Formatação da mensagem de sucesso
        app('flasher')->addSuccess("Periodo Aquisitivo dos anos {$ano_referencia} - " . ($ano_referencia + 1) . " foi criado");


        return redirect()->route('AdministrarFerias');
    }
    // Função auxiliar para calcular os dias de afastamento
    private function calcularDiasDeAfastamento($id_funcionario)
    {
        $afastamentos_suspensao_de_contrato = DB::table('afastamento')
            ->where('id_funcionario', '=', $id_funcionario)
            ->where('id_tp_afastamento', '=', 16)
            ->get();

        $dias_afastado = 0;
        foreach ($afastamentos_suspensao_de_contrato as $afastamento) {
            $dias_de_afastamento = Carbon::parse($afastamento->dt_inicio)->diffInDays($afastamento->dt_fim);
            $dias_afastado += $dias_de_afastamento;
        }

        return $dias_afastado;
    }

    public
    function administraferias(Request $request)
    {


        if (!empty($request->input('search'))) {
            $ano_referente = $request->input('search');
        } else {
            $ano_referente = DB::table('ferias')->max('ano_de_referencia');
        }

        $periodo_aquisitivo = DB::table('ferias')
            ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
            ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->join('status_pedido_ferias', 'ferias.status_pedido_ferias', '=', 'status_pedido_ferias.id')
            ->join('hist_setor', function ($join) {
                $join->on('hist_setor.id_func', '=', 'funcionarios.id')
                    ->where(function ($query) {
                        $query->whereNull('hist_setor.dt_fim')
                            ->orWhereRaw('ferias.dt_ini_a BETWEEN hist_setor.dt_inicio AND hist_setor.dt_fim')
                            ->orWhereRaw('ferias.dt_ini_b BETWEEN hist_setor.dt_inicio AND hist_setor.dt_fim')
                            ->orWhereRaw('ferias.dt_ini_c BETWEEN hist_setor.dt_inicio AND hist_setor.dt_fim');
                    });
            })
            ->join('setor as s', 'hist_setor.id_setor', '=', 's.id')
            ->join('contrato', 'contrato.id_funcionario', '=', 'funcionarios.id')
            ->select(
                'pessoas.nome_completo as nome_completo_funcionario',
                'pessoas.id as id_pessoa',
                'ferias.dt_ini_a',
                'ferias.dt_fim_a',
                'ferias.dt_ini_b',
                'ferias.dt_fim_b',
                'ferias.dt_ini_c',
                'ferias.dt_fim_c',
                'ferias.id as id_ferias',
                'ferias.motivo_retorno',
                'funcionarios.dt_inicio',
                'ferias.ano_de_referencia',
                'ferias.id_funcionario',
                'status_pedido_ferias.id as id_status_pedido_ferias',
                'status_pedido_ferias.nome as status_pedido_ferias',
                'ferias.dt_fim_periodo_de_licenca',
                'ferias.dt_inicio_periodo_de_licenca',
                'ferias.inicio_periodo_aquisitivo',
                'ferias.fim_periodo_aquisitivo',
                's.id as id_setor', // Alterado de 'setor.id' para 's.id'
                'hist_setor.dt_inicio as dt_inicio_setor',
                'hist_setor.dt_fim as dt_fim_setor',
                's.nome as nome_setor', // Alterado de 'setor.nome' para 's.nome'
                's.sigla as sigla_do_setor' // Alterado de 'setor.sigla' para 's.sigla'
            )
            ->whereIn('contrato.tp_contrato', [1, 5, 4])
            ->whereNull('contrato.dt_fim');




        $ano_consulta = null;
        $nome_funcionario = $request->input('nomefuncionario');
        $status_consulta = $request->input('statusconsulta');
        $setor = $request->input('setorconsulta');
        $status_consulta_atual = null;

        // Obtenção de dados para possíveis seleções
        $anos_possiveis = DB::table('ferias')
            ->select('ano_de_referencia')
            ->groupBy('ano_de_referencia')
            ->orderBy('ano_de_referencia')
            ->get();
        $status_ferias = DB::table('status_pedido_ferias');

        // Aplicação de filtros
        if ($nome_funcionario) {
            $periodo_aquisitivo->where('pessoas.nome_completo', 'ilike', '%' . $nome_funcionario . '%');
        }

        $ano_referente = $request->input('anoconsulta');
        if ($ano_referente && $ano_referente != '*') {
            $periodo_aquisitivo->where('ferias.ano_de_referencia', '=', $ano_referente);
            $ano_consulta = $ano_referente;
        } elseif ($ano_referente == '*') {
            $ano_consulta = null;
        } else {
            $ano_consulta = $periodo_aquisitivo->max('ano_de_referencia');
            $periodo_aquisitivo->where('ferias.ano_de_referencia', '=', $ano_consulta);
        }

        if ($status_consulta) {
            $periodo_aquisitivo->where('status_pedido_ferias.id', '=', $status_consulta);
            $status_consulta_atual = DB::table('status_pedido_ferias')->where('id', '=', $status_consulta)->first();
        }

        if ($setor) {
            $periodo_aquisitivo->where('s.id', '=', $setor);
            $setor = DB::table('setor')->where('id', '=', $setor)->first();
        }



        $setores_unicos = DB::table('funcionarios as f')
        ->leftJoin('hist_setor as hs', 'f.id', 'hs.id_func')
        ->leftJoin('setor as s', 'hs.id_setor', 's.id')
        ->select('hs.id_setor as id', 's.sigla' )->distinct('id')->get();
        //dd($setores_unicos);

        $anos_possiveis = DB::table('ferias')->select('ano_de_referencia')->groupBy('ano_de_referencia')->get();
        $anos_inicial = DB::table('ferias')->select('ano_de_referencia')->groupBy('ano_de_referencia')->first();
        $anos_final = DB::table('ferias')->select('ano_de_referencia')->groupBy('ano_de_referencia')->orderBy('ano_de_referencia', 'desc')->first();
    
        $status_ferias = DB::table('status_pedido_ferias')->get();
        if (!empty($anos_inicial)) {
            $anoAnterior = intval($anos_inicial->ano_de_referencia) - 2;
            $doisAnosFrente = intval($anos_final->ano_de_referencia) + 5;
        } else {
            $anoAnterior = intval(Carbon::now()->subYear(2)->toDateString());
            $doisAnosFrente = intval(Carbon::now()->addYear(5)->toDateString());
        };

        $listaAnos = range($anoAnterior, $doisAnosFrente);
        $contagemStatus = (clone $periodo_aquisitivo)
            ->groupBy('status_pedido_ferias.id')
            ->select(
                'status_pedido_ferias.id as id_status_pedido_ferias',
                'status_pedido_ferias.nome as status_pedido_ferias',
                DB::raw('COUNT(ferias.id) as total')
            )
            ->get();

        $periodo_aquisitivo = $periodo_aquisitivo->orderBy('pessoas.nome_completo')->orderBy('pessoas.id')->get();

        // $periodo_aquisitivo = $periodo_aquisitivo->get();


        $dias_limite_para_periodo_de_ferias = DB::table('hist_dia_limite_de_ferias')->where('data_fim', '=', null)->first();
        $dias_limite_para_periodo_de_ferias = DB::table('hist_dia_limite_de_ferias')->where('data_fim', '=', null)->first();

        foreach ($periodo_aquisitivo as $periodo_de_ferias) {
            $periodo_de_ferias->dia_limite_para_gozo_de_ferias =  Carbon::parse($periodo_de_ferias->dt_inicio_periodo_de_licenca)->addDays($dias_limite_para_periodo_de_ferias->dias)->toDateString();
        }

        // dd($contagemStatus);
        return view('ferias.administrar-ferias', compact(
            'periodo_aquisitivo',
            'anos_possiveis',
            'listaAnos',
            'ano_consulta',
            'setores_unicos',
            'status_ferias',
            'nome_funcionario',
            'ano_referente',
            'setor',
            'periodo_aquisitivo',
            'status_consulta',
            'status_consulta_atual',
            'contagemStatus'

        ));
    }

    public
    function autorizarferias($id)
    {
        try {
            $periodo_de_ferias = DB::table('ferias')->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')->join('status_pedido_ferias', 'ferias.status_pedido_ferias', '=', 'status_pedido_ferias.id')->select(
                'pessoas.nome_completo as nome_completo_funcionario',
            )->where('ferias.id', '=', $id)->first();
            DB::table('ferias')->where('id', '=', $id)->update(['status_pedido_ferias' => 6]);
            DB::table('hist_recusa_ferias')->insert(['id_periodo_de_ferias' => $id, 'motivo_retorno' => "Férias e Autoridas e Homologadas.", 'data_de_acontecimento' => Carbon::today()->toDateString()]);
            app('flasher')->addSuccess("As ferias do funcionario $periodo_de_ferias->nome_completo_funcionario foram autorizadas.");
            return redirect()->back();
        } catch (Exception $exception) {
            DB::rollBack();
            app('flasher')->addError($exception->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request, string $id)
    {


        // Obtém os dados do formulário de férias
        $formulario_de_ferias = $request->all();


        $adiantar_decimo_terceiro = false;
        $ferias = DB::table('ferias')->where('id', $id)->first();

        $dias_limite_de_ferias = DB::table('hist_dia_limite_de_ferias')->where('data_fim', '=', null)->first();
        $dia_limite_para_inicio_do_periodo_de_ferias = Carbon::parse($ferias->dt_fim_periodo_de_licenca)->subDays((365 - $dias_limite_de_ferias->dias))->toDateString();


        // Obtém o ano de referência
        $ano_referente = $ferias->ano_de_referencia;

        // Obtém informações do funcionário
        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->select('pessoas.id as id_pessoa', 'funcionarios.id as id_funcionario', 'pessoas.nome_completo', 'funcionarios.dt_inicio')
            ->first();
        //




        // Calcula os dias de direito do funcionário (exemplo: 30 dias de férias - dias de falta)
        $faltas = 0;

        $atestados = DB::table('afastamento')
            ->join('tp_afastamento', 'afastamento.id_tp_afastamento', '=', 'tp_afastamento.id')
            ->where('dt_inicio', '>=', $ferias->inicio_periodo_aquisitivo)
            ->where('dt_fim', '<=', $ferias->fim_periodo_aquisitivo)
            ->whereNotIn('id_tp_afastamento', [16, 17, 18, 19]) // Corrigido para whereNotIn
            ->select('tp_afastamento.limite', 'afastamento.qtd_dias')
            ->get();

        foreach ($atestados as $atestado) {
            $faltas += $atestado->qtd_dias - $atestado->limite;
        }

        if ($faltas <= 5) {
            $diasDeDireitoDoFuncionario = 30;
        } elseif ($faltas >= 6 && $faltas <= 14) {
            $diasDeDireitoDoFuncionario = 24;
        } elseif ($faltas >= 15 && $faltas <= 23) {
            $diasDeDireitoDoFuncionario = 18;
        } elseif ($faltas >= 24 && $faltas <= 32) {
            $diasDeDireitoDoFuncionario = 12;
        } else {
            $diasDeDireitoDoFuncionario = 0;

            DB::table('ferias')->where('id', '=', $ferias->id)->update(['status_pedido_ferias' => 6, 'motivo_retorno' => "O funcionario não possui direito a  por ter faltado mais de 32 dias sem abono"]);
            DB::table('hist_recusa_ferias')->insert(['id_periodo_de_ferias' => $id, 'motivo_retorno' => "O funcionario não possui direito a  por ter faltado mais de 32 dias sem abono", 'data_de_acontecimento' => Carbon::today()->toDateString()]);
            app('flasher')->addError('O funcionario não possui direito a  por ter faltado mais de 32 dias sem abono');
            return redirect()->route('IndexGerenciarFerias');
        }




        // Verifica o número de períodos de férias
        if ($formulario_de_ferias['numeroPeriodoDeFerias'] == 1) {

            // Condições para um único período de férias
            $data_inicio_formulario = Carbon::parse($formulario_de_ferias["data_inicio_0"]);
            $data_fim_formulario = Carbon::parse($formulario_de_ferias["data_fim_0"]);
            $dias_de_ferias_utilizadas = $data_inicio_formulario->diffInDays($data_fim_formulario) + 1;
            $data_de_saida_para_as_ferias = Carbon::parse($data_inicio_formulario)->dayOfWeek;



            // Verifica se o número de dias utilizados excede os dias de direito do funcionário
            if ($dias_de_ferias_utilizadas > $diasDeDireitoDoFuncionario) {
                $dias_excedentes = $dias_de_ferias_utilizadas - $diasDeDireitoDoFuncionario;
                app('flasher')->addError("Foram utilizados $dias_excedentes dias a mais. Deveria assim retornar dia " . Carbon::parse($data_inicio_formulario->addDays(29))->format('d-m-y'));
            } // Verifica se o número de dias utilizados é inferior aos dias de direito do funcionário
            elseif ($dias_de_ferias_utilizadas < $diasDeDireitoDoFuncionario) {
                $dias_restantes = $diasDeDireitoDoFuncionario - $dias_de_ferias_utilizadas;
                app('flasher')->addError("Não foram utilizados todos os dias que tem direito, ainda é preciso utilizar $dias_restantes dias. Deveria assim retornar dia " . Carbon::parse($data_inicio_formulario->addDays(29))->format('d-m-y'));
            } // Verifica se a data de início das férias é anterior ao início do período de licença
            elseif ($data_inicio_formulario < $ferias->dt_inicio_periodo_de_licenca) {
                app('flasher')->addError('A data inicial do período de férias é inferior ao início do seu período de licença que começa no dia ' . Carbon::parse($ferias->fim_periodo_aquisitivo)->format('d/M/Y'));
            } //Verifica se a data final é depois do periodo de licensa
            elseif ($data_fim_formulario > $ferias->dt_fim_periodo_de_licenca) {
                app('flasher')->addError('Data Final depois do periodo de licensa');
            } // Verifica se a data de fim é anterior à data de início
            elseif ($data_fim_formulario < $data_inicio_formulario) {
                app('flasher')->addError('Você colocou uma data de fim excedendo a data de início');
            } //Verifica se a data inicia das ferias ocore em uma sexta-feira
            elseif ($data_de_saida_para_as_ferias == 5) {
                app('flasher')->addError("Sua data de saida, antecede dois dias antes do repouso semanal remunerado");
            } elseif ($dia_limite_para_inicio_do_periodo_de_ferias <= $data_inicio_formulario) {
                app('flasher')->addError('Uma das datas iniciais que selecionou ultrapassa a data limite para inicio das férias: ' . $dia_limite_para_inicio_do_periodo_de_ferias);
            } //Insere no banco e coloca no historico
            else {
                DB::table('ferias')
                    ->where('id', $ferias->id)
                    ->update([
                        'dt_ini_a' => $data_inicio_formulario,
                        'dt_fim_a' => $data_fim_formulario,
                        'dt_ini_b' => null,
                        'dt_fim_b' => null,
                        'dt_ini_c' => null,
                        'dt_fim_c' => null,
                        'motivo_retorno' => null,
                        'adianta_13sal' => $request->boolean('adiantaDecimoTerceiro'),
                        'status_pedido_ferias' => 3,
                        'nr_dias_per_a' => $data_inicio_formulario->diffInDays($data_fim_formulario) + 1,
                        'vendeu_ferias' => isset($formulario_de_ferias["vendeFerias"]) ? $formulario_de_ferias["vendeFerias"] : null,
                        'venda_um_terco' => (int)$request->input('periodoDeVendaDeFerias')

                    ]);
                DB::table('hist_recusa_ferias')->insert(['id_periodo_de_ferias' => $ferias->id, 'motivo_retorno' => 'Preenchimento do Formulario', 'data_de_acontecimento' => Carbon::today()->toDateString()]);

                $ferias = DB::table('ferias')
                    ->where('ferias.id', $ferias->id)
                    ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
                    ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
                    ->select('pessoas.nome_completo')->first();


                app('flasher')->addCreated($ferias->nome_completo . ' teve férias adicionadas com sucesso.');
                return redirect()->route('IndexGerenciarFerias');
            }
        } //Condicoes para segundo caso
        elseif ($formulario_de_ferias['numeroPeriodoDeFerias'] == 2) {
            // Condições para dois períodos de férias
            $data_inicio_primeiro_periodo = Carbon::parse($formulario_de_ferias["data_inicio_0"]);
            $data_fim_primeiro_periodo = Carbon::parse($formulario_de_ferias["data_fim_0"]);
            $data_inicio_segundo_periodo = Carbon::parse($formulario_de_ferias["data_inicio_1"]);
            $data_fim_segundo_periodo = Carbon::parse($formulario_de_ferias["data_fim_1"]);
            $dia_da_semana_de_saida_do_primeiro_periodo = Carbon::parse($data_inicio_primeiro_periodo)->dayOfWeek;
            $dia_da_semana_de_saida_do_segundo_periodo = Carbon::parse($data_inicio_segundo_periodo)->dayOfWeek;
            $dias_de_ferias_utilizadas = ($data_inicio_primeiro_periodo->diffInDays($data_fim_primeiro_periodo) + 1) + ($data_inicio_segundo_periodo->diffInDays($data_fim_segundo_periodo) + 1);
            $data_inicio_periodo_concessivo = Carbon::parse($ferias->dt_inicio_periodo_de_licenca);
            $data_fim_periodo_concessivo = Carbon::parse($ferias->dt_fim_periodo_de_licenca);

            // Verifica se a data inicial do primeiro período é maior que a data final do primeiro período
            if ($data_inicio_primeiro_periodo > $data_fim_primeiro_periodo) {
                app('flasher')->addError('A Data Inicial do Primeiro Período é maior que a Data Final do Primeiro Período');
            } // Verifica se a data inicial do segundo período é maior que a data final do segundo período
            elseif ($data_inicio_segundo_periodo > $data_fim_segundo_periodo) {
                app('flasher')->addError('A Data Inicial do Segundo Período é maior que a Data Final do Segundo Período');
            } // Verifica se a data final do segundo período é anterior à data de início ou à data final do primeiro período
            elseif ($data_fim_segundo_periodo < $data_inicio_primeiro_periodo || $data_fim_segundo_periodo < $data_fim_primeiro_periodo) {
                app('flasher')->addError('A Data Final do Segundo Período é anterior ao Primeiro Período');
            } // Verifica se a data inicial do segundo período está dentro do primeiro período
            elseif ($data_inicio_segundo_periodo > $data_inicio_primeiro_periodo && $data_inicio_segundo_periodo < $data_fim_primeiro_periodo) {
                app('flasher')->addError('A Data Inicial do Segundo Período entra em conflito com o Primeiro Período');
            } // Verifica se a data final do segundo período está dentro do primeiro período
            elseif ($data_fim_segundo_periodo > $data_inicio_primeiro_periodo && $data_fim_segundo_periodo < $data_fim_primeiro_periodo) {
                app('flasher')->addError('A Data Final do Segundo Período entra em conflito com o Primeiro Período');
            } // Verifica se o segundo período está completamente dentro do primeiro período
            elseif ($data_inicio_segundo_periodo < $data_inicio_primeiro_periodo && $data_fim_segundo_periodo > $data_fim_primeiro_periodo) {
                app('flasher')->addError('O Segundo Período está completamente dentro do Primeiro Período');
            } // Verifica se o segundo período inicia e termina antes do primeiro período
            elseif ($data_inicio_segundo_periodo < $data_inicio_primeiro_periodo && $data_fim_segundo_periodo < $data_fim_primeiro_periodo) {
                app('flasher')->addError('O Segundo Período inicia antes e termina antes do Primeiro Período');
            } // Verifica se o segundo período inicia durante e termina após o primeiro período
            elseif ($data_inicio_segundo_periodo > $data_inicio_primeiro_periodo && $data_inicio_segundo_periodo < $data_fim_primeiro_periodo && $data_fim_segundo_periodo > $data_fim_primeiro_periodo) {
                app('flasher')->addError('O Segundo Período inicia durante o Primeiro Período e termina após o seu término');
            } // Verifica se o segundo período inicia antes e termina antes do primeiro período
            elseif ($data_inicio_segundo_periodo < $data_inicio_primeiro_periodo && $data_fim_segundo_periodo < $data_fim_primeiro_periodo) {
                app('flasher')->addError('O Segundo Período inicia antes do Primeiro Período e termina antes do seu término');
            } // Verifica se o segundo período inicia durante e termina antes do primeiro período
            elseif ($data_inicio_segundo_periodo > $data_inicio_primeiro_periodo && $data_inicio_segundo_periodo < $data_fim_primeiro_periodo && $data_fim_segundo_periodo < $data_fim_primeiro_periodo) {
                app('flasher')->addError('O Segundo Período inicia durante o Primeiro Período e termina antes do seu término');
            } //Verifica se o funcionario usou o seu tempo de ferias corretamente para menos
            elseif ($dias_de_ferias_utilizadas < $diasDeDireitoDoFuncionario) {
                app('flasher')->addError('Ainda não utilizou todos os dias de férias. Utilizou:' . ($data_inicio_primeiro_periodo->diffInDays($data_fim_primeiro_periodo) + 1) . 'no primeiro período.<br> E ' . ($data_inicio_segundo_periodo->diffInDays($data_fim_segundo_periodo) + 1) . ' no segundo período');
            } //Verifica se o funcionario utilizou dias a mais
            elseif ($dias_de_ferias_utilizadas > $diasDeDireitoDoFuncionario) {
                app('flasher')->addError('Utilizou dias de férias a mais. <br> Utilizou:' . ($data_inicio_primeiro_periodo->diffInDays($data_fim_primeiro_periodo) + 1) . 'no primeiro período.<br> E ' . ($data_inicio_segundo_periodo->diffInDays($data_fim_segundo_periodo) + 1) . ' no segundo período');
            } //Verifica se a data inicia do primeiro periodo de ferias ocorre em uma sexta-feira
            elseif ($dia_da_semana_de_saida_do_primeiro_periodo == 5) {
                app('flasher')->addError('A data inicial do primeiro periodo ocorre dois dias antes do descanso semanal remunerado');
            } //Verifica se a data inicia do primeiro periodo de ferias ocorre em uma sexta-feira
            elseif ($dia_da_semana_de_saida_do_segundo_periodo == 5) {
                app('flasher')->addError('A data inicial do segundo periodo ocorre dois dias antes do descanso semanal remunerado');
            } //Verifica se o primeiro periodo é menor que cinco dias
            elseif (Carbon::parse($data_inicio_primeiro_periodo)->diffInDays($data_fim_primeiro_periodo) < 5) {
                app('flasher')->addError('O primeiro periodo é inferior a 5 dias');
            } //Verifica se o primeiro periodo é menor que cinco dias
            elseif (Carbon::parse($data_inicio_segundo_periodo)->diffInDays($data_fim_segundo_periodo) < 5) {
                app('flasher')->addError('O segundo periodo é inferior a 5 dias');
            } elseif ($dia_limite_para_inicio_do_periodo_de_ferias < $data_inicio_primeiro_periodo or $dia_limite_para_inicio_do_periodo_de_ferias < $data_inicio_segundo_periodo) {
                app('flasher')->addError('Uma das datas iniciais que selecionou ultrapassa a data limite para inicio das férias: ' . $dia_limite_para_inicio_do_periodo_de_ferias);
            } elseif (
                $data_inicio_primeiro_periodo < $data_inicio_periodo_concessivo ||
                $data_fim_primeiro_periodo < $data_inicio_periodo_concessivo ||
                $data_inicio_segundo_periodo < $data_inicio_periodo_concessivo ||
                $data_fim_segundo_periodo < $data_inicio_periodo_concessivo
            ) {

                app('flasher')->addError('Uma das datas que selecionou é anterior ao seu Período Concessivo que inicia: ' . $data_inicio_periodo_concessivo->format('d/m/Y'));
            } elseif (
                $data_inicio_primeiro_periodo > $data_fim_periodo_concessivo ||
                $data_fim_primeiro_periodo > $data_fim_periodo_concessivo ||
                $data_inicio_segundo_periodo > $data_fim_periodo_concessivo ||
                $data_fim_segundo_periodo > $data_fim_periodo_concessivo
            ) {
                app('flasher')->addError('Uma das datas que selecionou é posterior ao seu Período Concessivo que termina: ' . $data_fim_periodo_concessivo->format('d/m/Y'));
            } else {
                DB::table('ferias')->where('id', $ferias->id)->update([
                    'dt_ini_a' => $data_inicio_primeiro_periodo,
                    'dt_fim_a' => $data_fim_primeiro_periodo,
                    'dt_ini_b' => $data_inicio_segundo_periodo,
                    'dt_fim_b' => $data_fim_segundo_periodo,
                    'dt_ini_c' => null,
                    'dt_fim_c' => null,
                    'motivo_retorno' => null,
                    'adianta_13sal' => $request->boolean('adiantaDecimoTerceiro'),
                    'status_pedido_ferias' => 3,
                    'nr_dias_per_a' => $data_inicio_primeiro_periodo->diffInDays($data_fim_primeiro_periodo),
                    'nr_dias_per_b' => $data_inicio_segundo_periodo->diffInDays($data_fim_segundo_periodo),
                    'vendeu_ferias' => isset($formulario_de_ferias["vendeFerias"]) ? $formulario_de_ferias["vendeFerias"] : null,
                    'venda_um_terco' => (int)$request->input('periodoDeVendaDeFerias')
                ]);
                DB::table('hist_recusa_ferias')->insert(['id_periodo_de_ferias' => $ferias->id, 'motivo_retorno' => 'Preenchimento do Formulario', 'data_de_acontecimento' => Carbon::today()->toDateString()]);
                $ferias = DB::table('ferias')
                    ->where('ferias.id', $ferias->id)
                    ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
                    ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
                    ->select('pessoas.nome_completo')->first();
                app('flasher')->addCreated($ferias->nome_completo . ' teve férias adicionadas com sucesso.');
                return redirect()->route('IndexGerenciarFerias');
            }
        } elseif ($formulario_de_ferias['numeroPeriodoDeFerias'] == 3) {
            // Condições para três períodos de férias
            $data_inicio_primeiro_periodo = Carbon::parse($formulario_de_ferias["data_inicio_0"]);
            $data_fim_primeiro_periodo = Carbon::parse($formulario_de_ferias["data_fim_0"]);
            $data_inicio_segundo_periodo = Carbon::parse($formulario_de_ferias["data_inicio_1"]);
            $data_fim_segundo_periodo = Carbon::parse($formulario_de_ferias["data_fim_1"]);
            $data_inicio_terceiro_periodo = Carbon::parse($formulario_de_ferias["data_inicio_2"]);
            $data_fim_terceiro_periodo = Carbon::parse($formulario_de_ferias["data_fim_2"]);

            $dia_da_semana_de_saida_do_primeiro_periodo = Carbon::parse($data_inicio_primeiro_periodo)->dayOfWeek;
            $dia_da_semana_de_saida_do_segundo_periodo = Carbon::parse($data_inicio_segundo_periodo)->dayOfWeek;
            $dia_da_semana_de_saida_do_terceiro_periodo = Carbon::parse($data_inicio_terceiro_periodo)->dayOfWeek;
            $data_inicio_periodo_concessivo = Carbon::parse($ferias->dt_inicio_periodo_de_licenca);
            $data_fim_periodo_concessivo = Carbon::parse($ferias->dt_fim_periodo_de_licenca);
            $dias_de_ferias_utilizadas = $data_inicio_primeiro_periodo->diffInDays($data_fim_primeiro_periodo) + $data_inicio_segundo_periodo->diffInDays($data_fim_segundo_periodo) + $data_inicio_terceiro_periodo->diffInDays($data_fim_terceiro_periodo) + 3;

            // Verifica se a data inicial do primeiro período é maior que a data final do primeiro período
            if ($data_inicio_primeiro_periodo > $data_fim_primeiro_periodo) {
                app('flasher')->addError('A Data Inicial do Primeiro Período é maior que a Data Final do Primeiro Período');
            } // Verifica se a data inicial do segundo período é maior que a data final do segundo período
            elseif ($data_inicio_segundo_periodo > $data_fim_segundo_periodo) {
                app('flasher')->addError('A Data Inicial do Segundo Período é maior que a Data Final do Segundo Período');
            } // Verifica se a data inicial do terceiro período é maior que a data final do terceiro período
            elseif ($data_inicio_terceiro_periodo > $data_fim_terceiro_periodo) {
                app('flasher')->addError('A Data Inicial do Terceiro Período é maior que a Data Final do Terceiro Período');
            } // Verifica se a data final do segundo período é anterior à data de início ou à data final do primeiro período
            elseif ($data_fim_segundo_periodo < $data_inicio_primeiro_periodo || $data_fim_segundo_periodo < $data_fim_primeiro_periodo) {
                app('flasher')->addError('A Data Final do Segundo Período é anterior ao Primeiro Período');
            } // Verifica se a data inicial do segundo período está dentro do primeiro período
            elseif ($data_inicio_segundo_periodo > $data_inicio_primeiro_periodo && $data_inicio_segundo_periodo < $data_fim_primeiro_periodo) {
                app('flasher')->addError('A Data Inicial do Segundo Período entra em conflito com o Primeiro Período');
            } // Verifica se a data final do segundo período está dentro do primeiro período
            elseif ($data_fim_segundo_periodo > $data_inicio_primeiro_periodo && $data_fim_segundo_periodo < $data_fim_primeiro_periodo) {
                app('flasher')->addError('A Data Final do Segundo Período entra em conflito com o Primeiro Período');
            } // Verifica se a data inicial do terceiro período é anterior à data final do segundo período
            elseif ($data_inicio_terceiro_periodo < $data_fim_segundo_periodo) {
                app('flasher')->addError('A Data Inicial do Terceiro Período é menor que a Data Final do Segundo Período');
            } // Verifica se a data final do terceiro período é anterior à data de início ou à data final do segundo período
            elseif ($data_fim_terceiro_periodo < $data_inicio_segundo_periodo || $data_fim_terceiro_periodo < $data_fim_segundo_periodo) {
                app('flasher')->addError('A Data Final do Terceiro Período é anterior ao Segundo Período');
            } // Verifica se a data inicial do terceiro período está dentro do segundo período
            elseif ($data_inicio_terceiro_periodo > $data_inicio_segundo_periodo && $data_inicio_terceiro_periodo < $data_fim_segundo_periodo) {
                app('flasher')->addError('A Data Inicial do Terceiro Período entra em conflito com o Segundo Período');
            } // Verifica se a data final do terceiro período está dentro do segundo período
            elseif ($data_fim_terceiro_periodo > $data_inicio_segundo_periodo && $data_fim_terceiro_periodo < $data_fim_segundo_periodo) {
                app('flasher')->addError('A Data Final do Terceiro Período entra em conflito com o Segundo Período');
            } //Verifica se o funcionario utilizou dias a mais
            elseif ($dias_de_ferias_utilizadas > $diasDeDireitoDoFuncionario) {
                app('flasher')->addError('Utilizou dias de férias a mais. <br> Utilizou:' . ($data_inicio_primeiro_periodo->diffInDays($data_fim_primeiro_periodo) + 1) . 'no primeiro período.<br> E ' . ($data_inicio_segundo_periodo->diffInDays($data_fim_segundo_periodo) + 1) . ' no segundo . <br>' . ($data_inicio_terceiro_periodo->diffInDays($data_fim_terceiro_periodo) + 1) . " no terceiro período");
            } //Verifica se o funcionario utililizou dias a menos
            elseif ($dias_de_ferias_utilizadas < $diasDeDireitoDoFuncionario) {
                app('flasher')->addError('Ainda não utilizou todos os dias de ferías. <br> Utilizou:' . ($data_inicio_primeiro_periodo->diffInDays($data_fim_primeiro_periodo) + 1) . 'no primeiro período.<br> E ' . ($data_inicio_segundo_periodo->diffInDays($data_fim_segundo_periodo) + 1) . ' no segundo . <br>' . ($data_inicio_terceiro_periodo->diffInDays($data_fim_terceiro_periodo) + 1) . " no terceiro período");
            } //Verifica se o primeiro periodo ocore em uma sexta
            elseif ($dia_da_semana_de_saida_do_primeiro_periodo == 5) {
                app('flasher')->addError('A data inicial do primeiro periodo ocorre dois dias antes do descanso semanal remunerado');
            } //Verifica se o segundo periodo ocore em uma sexta
            elseif ($dia_da_semana_de_saida_do_segundo_periodo == 5) {
                app('flasher')->addError('A data inicial do segundo periodo ocorre dois dias antes do descanso semanal remunerado');
            } //Verifica se o terceiro periodo ocore em uma sexta
            elseif ($dia_da_semana_de_saida_do_terceiro_periodo == 5) {
                app('flasher')->addError('A data inicial do terceiro período ocorre dois dias antes do descanso semanal remunerado');
            } elseif ($dia_limite_para_inicio_do_periodo_de_ferias <= $data_inicio_primeiro_periodo or $dia_limite_para_inicio_do_periodo_de_ferias <= $data_inicio_segundo_periodo or $dia_limite_para_inicio_do_periodo_de_ferias <= $data_inicio_terceiro_periodo) {
                app('flasher')->addError('Uma das datas iniciais que selecionou ultrapassa a data limite para inicio das férias: ' . $dia_limite_para_inicio_do_periodo_de_ferias);
            } elseif (
                $data_inicio_primeiro_periodo < $data_inicio_periodo_concessivo ||
                $data_fim_primeiro_periodo < $data_inicio_periodo_concessivo ||
                $data_inicio_segundo_periodo < $data_inicio_periodo_concessivo ||
                $data_fim_segundo_periodo < $data_inicio_periodo_concessivo ||
                $data_inicio_terceiro_periodo < $data_inicio_periodo_concessivo ||
                $data_fim_terceiro_periodo < $data_inicio_periodo_concessivo
            ) {

                app('flasher')->addError('Uma das datas que selecionou é anterior ao seu Período Concessivo que inicia: ' . $data_inicio_periodo_concessivo->format('d/m/Y'));
            } elseif (
                $data_inicio_primeiro_periodo > $data_fim_periodo_concessivo ||
                $data_fim_primeiro_periodo > $data_fim_periodo_concessivo ||
                $data_inicio_segundo_periodo > $data_fim_periodo_concessivo ||
                $data_fim_segundo_periodo > $data_fim_periodo_concessivo ||
                $data_inicio_terceiro_periodo > $data_fim_periodo_concessivo ||
                $data_fim_terceiro_periodo > $data_fim_periodo_concessivo
            ) {
                app('flasher')->addError('Uma das datas que selecionou é posterior ao seu Período Concessivo que termina: ' . $data_fim_periodo_concessivo->format('d/m/Y'));
            } elseif (($data_inicio_primeiro_periodo->diffInDays($data_fim_primeiro_periodo) + 1 >= 15) || ($data_inicio_segundo_periodo->diffInDays($data_fim_segundo_periodo) + 1 >= 15) || ($data_inicio_terceiro_periodo->diffInDays($data_fim_terceiro_periodo) + 1) >= 15) {
                DB::table('ferias')->where('id', $ferias->id)->update([
                    'dt_ini_a' => $data_inicio_primeiro_periodo,
                    'dt_fim_a' => $data_fim_primeiro_periodo,
                    'dt_ini_b' => $data_inicio_segundo_periodo,
                    'dt_fim_b' => $data_fim_segundo_periodo,
                    'dt_ini_c' => $data_inicio_terceiro_periodo,
                    'dt_fim_c' => $data_fim_terceiro_periodo,
                    'adianta_13sal' => $request->boolean('adiantaDecimoTerceiro'),
                    'motivo_retorno' => null,
                    'status_pedido_ferias' => 3,
                    'nr_dias_per_a' => $data_inicio_primeiro_periodo->diffInDays($data_fim_primeiro_periodo),
                    'nr_dias_per_b' => $data_inicio_segundo_periodo->diffInDays($data_fim_segundo_periodo),
                    'nr_dias_per_c' => $data_inicio_terceiro_periodo->diffInDays($data_fim_terceiro_periodo),
                    'vendeu_ferias' => isset($formulario_de_ferias["vendeFerias"]) ? $formulario_de_ferias["vendeFerias"] : null,
                    'venda_um_terco' => (int)$request->input('periodoDeVendaDeFerias')
                ]);
                DB::table('hist_recusa_ferias')->insert(['id_periodo_de_ferias' => $ferias->id, 'motivo_retorno' => 'Preenchimento do Formulario', 'data_de_acontecimento' => Carbon::today()->toDateString()]);

                $ferias = DB::table('ferias')
                    ->where('ferias.id', $ferias->id)
                    ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
                    ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
                    ->select('pessoas.nome_completo')->first();


                app('flasher')->addCreated($ferias->nome_completo . ' teve férias adicionadas com sucesso.');
                return redirect()->route('IndexGerenciarFerias');
            } else {
                app('flasher')->addError('É essencial que pelo menos um periodos tenha o minimo de 15 dias.');
            }
        }
        return redirect()->route('IndexGerenciarFerias');
    }

    public
    function formulario_recusa_periodo_de_ferias($id)
    {
        try {
            $periodos_aquisitivos = DB::table('ferias')->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')->join('status_pedido_ferias', 'ferias.status_pedido_ferias', '=', 'status_pedido_ferias.id')->select('pessoas.nome_completo as nome_completo_funcionario', 'pessoas.id as id_pessoa', 'ferias.dt_ini_a', 'ferias.dt_fim_a', 'ferias.dt_ini_b', 'ferias.dt_fim_b', 'ferias.dt_ini_c', 'ferias.dt_fim_c', 'ferias.motivo_retorno', 'ferias.id as id_ferias', 'funcionarios.dt_inicio', 'ferias.ano_de_referencia', 'ferias.id_funcionario', 'status_pedido_ferias.id as id_status_pedido_ferias', 'status_pedido_ferias.nome as status_pedido_ferias')->where('ferias.id', '=', $id)->first();

            return view('ferias.recusa-ferias', compact('periodos_aquisitivos', 'id'));
        } catch (Exception $exception) {
            DB::rollBack();
            app('flasher')->addError("Houve um erro inesperado: #" . $exception->getCode());
            return redirect()->route('IndexGerenciarFerias');
        }
    }

    public
    function recusa_pedido_de_ferias(Request $request, $id)
    {
        try {
            $request->input('motivo_da_recusa');

            DB::table('ferias')->where('id', $id)->update(['motivo_retorno' => $request->input('motivo_da_recusa'), 'status_pedido_ferias' => 5]);

            DB::table('hist_recusa_ferias')->insert(['id_periodo_de_ferias' => $id, 'motivo_retorno' => $request->input('motivo_da_recusa'), 'data_de_acontecimento' => Carbon::today()->toDateString()]);
            app('flasher')->addSuccess('Recusado com sucesso.');
        } catch (Exception $exception) {
            app('flasher')->addError("Houve um erro inesperado: #" . $exception->getCode());
            DB::rollBack();
        }

        return redirect()->route('AdministrarFerias');
    }

    public
    function enviarFerias(Request $request)
    {
        try {
            $numeros_checkboxes = $request->input('checkbox');

            if (empty($request->input('checkbox'))) {
                app('flasher')->addError("Nenhum periodo de ferias foi selecionado para ser enviado.");

                return redirect()->back();
            }

            $ferias_funcionarios = DB::table('ferias')
                ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
                ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
                ->join('status_pedido_ferias', 'ferias.status_pedido_ferias', '=', 'status_pedido_ferias.id')
                ->select(
                    'pessoas.nome_completo as nome_completo_funcionario',
                    'pessoas.id as id_pessoa',
                    'ferias.dt_ini_a',
                    'ferias.dt_fim_a',
                    'ferias.dt_ini_b',
                    'ferias.dt_fim_b',
                    'ferias.dt_ini_c',
                    'ferias.dt_fim_c',
                    'ferias.motivo_retorno',
                    'ferias.id as id_ferias',
                    'funcionarios.dt_inicio',
                    'ferias.ano_de_referencia',
                    'ferias.id_funcionario',
                    'status_pedido_ferias.id as id_status_pedido_ferias',
                    'status_pedido_ferias.nome as status_pedido_ferias'
                )->WhereIn('ferias.id', $numeros_checkboxes)->get();

            foreach ($ferias_funcionarios as $ferias_funcionario) {
                if (empty($ferias_funcionario->dt_ini_a)) {
                    app('flasher')->addError('Foi feita uma tentativa de enviar as ferias de ' . $ferias_funcionario->nome_completo_funcionario . ', porém as mesmas não foram preenchidas.');
                    return redirect()->route('IndexGerenciarFerias');
                }
            }
            foreach ($ferias_funcionarios as $ferias_funcionario) {
                DB::table('ferias')->where('id', '=', $ferias_funcionario->id_ferias)->update(['status_pedido_ferias' => 4]);
                DB::table('hist_recusa_ferias')->insert([
                    'id_periodo_de_ferias' =>  $ferias_funcionario->id_ferias,
                    'motivo_retorno' => 'Envio do Fomulario',
                    'data_de_acontecimento' => Carbon::today()->toDateString()
                ]);
            }
            DB::commit();
            app('flasher')->addSuccess("Ferias Enviadas com Sucesso!");
            return redirect()->back();
        } catch (Exception $exception) {
            app('flasher')->addError("Houve um erro inesperado: #" . $exception->getCode());
            DB::rollBack();
            return redirect()->back();
        }
    }

    public
    function reabrirFormulario($id)
    {
        try {
            DB::table('ferias')->where('id', $id)->update(['status_pedido_ferias' => 1]);
            $nome = DB::table('ferias')
                ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
                ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
                ->where('ferias.id', '=', $id)
                ->select('pessoas.nome_completo', 'ferias.ano_de_referencia')->first();

            DB::table('hist_recusa_ferias')->insert(['id_periodo_de_ferias' => $id, 'motivo_retorno' => 'Solicitada Reabertura do Formulario pelo funcionário', 'data_de_acontecimento' => Carbon::today()->toDateString()]);
            app('flasher')->addInfo('Ferias Do funcionario' . $nome->nome_completo . ' referentes a ' . (intval($nome->ano_de_referencia) + 1) . '-' . (intval($nome->ano_de_referencia) + 2) . ' foram reabertas');
            return redirect()->back();
        } catch (Exception $exception) {
            DB::rollBack();
            app('flasher')->addError("Houve um erro inesperado: #" . $exception->getCode());
            return redirect()->back();
        }
    }

    public function EnviaPeriodoDeFeriasIndividualmente($id)
    {
        try {
            $ferias_funcionario = DB::table('ferias')
                ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
                ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
                ->join('status_pedido_ferias', 'ferias.status_pedido_ferias', '=', 'status_pedido_ferias.id')
                ->select(
                    'pessoas.nome_completo as nome_completo_funcionario',
                    'pessoas.id as id_pessoa',
                    'ferias.dt_ini_a',
                    'ferias.dt_fim_a',
                    'ferias.dt_ini_b',
                    'ferias.dt_fim_b',
                    'ferias.dt_ini_c',
                    'ferias.dt_fim_c',
                    'ferias.motivo_retorno',
                    'ferias.id as id_ferias',
                    'funcionarios.dt_inicio',
                    'ferias.ano_de_referencia',
                    'ferias.id_funcionario',
                    'status_pedido_ferias.id as id_status_pedido_ferias',
                    'status_pedido_ferias.nome as status_pedido_ferias'
                )->where('ferias.id', '=', $id)->first();
            DB::table('hist_recusa_ferias')->insert(['id_periodo_de_ferias' =>    $ferias_funcionario->id_ferias, 'motivo_retorno' => 'Envio do Fomulario', 'data_de_acontecimento' => Carbon::today()->toDateString()]);
            if ($ferias_funcionario->dt_ini_a == null) {
                app('flasher')->addError('Foi feita uma tentativa de enviar as ferias de ' .  $ferias_funcionario->nome_completo_funcionario . ', porém as mesmas não foram preenchidas.');
                return redirect()->route('IndexGerenciarFerias');
            } else {
                DB::table('ferias')->where('id', '=',   $ferias_funcionario->id_ferias)->update(['status_pedido_ferias' => 4]);
                DB::commit();
                app('flasher')->addSuccess("Ferias Enviadas com Sucesso!");
                return redirect()->back();
            }
        } catch (Exception $exception) {
            app('flasher')->addError("Houve um erro inesperado: #" . $exception->getCode());
            DB::rollBack();
            return redirect()->back();
        }
    }

    public
    function retornaPeriodoFerias($ano, $nome, $setor, $status)
    {
        // Realize sua lógica para buscar os dados com base nos parâmetros recebidos
        $periodo_aquisitivo = DB::table('ferias')
            ->leftJoin('funcionarios', 'ferias.id_funcionario', '=', 'funcionarios.id')
            ->join('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->join('status_pedido_ferias', 'ferias.status_pedido_ferias', '=', 'status_pedido_ferias.id')
            ->join('setor as s', 's.id', '=', 'id_setor')
            ->select(
                'pessoas.nome_completo as nome_completo_funcionario',
                'pessoas.id as id_pessoa',
                'ferias.dt_ini_a',
                'ferias.dt_fim_a',
                'ferias.dt_ini_b',
                'ferias.dt_fim_b',
                'ferias.dt_ini_c',
                'ferias.dt_fim_c',
                'ferias.motivo_retorno',
                'ferias.id as id_ferias',
                'ferias.venda_um_terco',
                'funcionarios.dt_inicio',
                'ferias.ano_de_referencia',
                'ferias.id_funcionario',
                'ferias.adianta_13sal',
                'status_pedido_ferias.id as id_status_pedido_ferias',
                'status_pedido_ferias.nome as status_pedido_ferias',
                's.sigla as sigla_do_setor',
                's.id as id_do_setor'
            );

        if ($nome !== "null") {
            $periodo_aquisitivo = $periodo_aquisitivo->where('pessoas.nome_completo', 'ilike', '%' . $nome . '%');
        }

        if ($ano !== "null") {
            $periodo_aquisitivo = $periodo_aquisitivo->where('ferias.ano_de_referencia', '=', $ano);
        }
        if ($setor !== "null") {
            $periodo_aquisitivo = $periodo_aquisitivo->where('s.id', '=', $setor);
        }
        if ($status !== "null") {
            $periodo_aquisitivo = $periodo_aquisitivo->where('status_pedido_ferias.id', '=', $status);
        }

        $periodo_aquisitivo = $periodo_aquisitivo->get();
        return response()->json($periodo_aquisitivo);
    }
}
