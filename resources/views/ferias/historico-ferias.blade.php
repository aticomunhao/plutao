@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <br>
    <div class="container">
        <div class="card">
            <h5 class="card-header">
                <div class="row d-flex justify-content-between">
                    <div class="col-md-5">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <strong> {{ $funcionario->nome_completo }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <strong>Status do Pedido de Férias:</strong>
                                @switch($periodo_de_ferias->status_pedido_ferias)
                                    @case(1)
                                        <span class="badge bg-success">Liberado</span>
                                    @break

                                    @case(2)
                                        <span class="badge bg-warning text-dark">Em Elaboração</span>
                                    @break

                                    @case(3)
                                        <span class="badge bg-info">Aguardando Envio</span>
                                    @break

                                    @case(4)
                                        <span class="badge bg-primary">Enviado</span>
                                    @break

                                    @case(5)
                                        <span class="badge bg-danger">Ajustar</span>
                                    @break

                                    @case(6)
                                        <span class="badge bg-success">Aprovado</span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary">Desconhecido</span>
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </h5>
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="inicio_gozo_ferias">Data de Início do Período Concessivo de Ferias:</label>
                                <input type="text" id="inicio_gozo_ferias" class="form-control"
                                    value="{{ Carbon::parse($periodo_de_ferias->dt_inicio_periodo_de_licenca)->format('d/m/Y') }}"
                                    readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fim_possivel_gozo_ferias">Data de Término do Período Concessivo de
                                    Ferias:</label>
                                <input type="text" id="fim_possivel_gozo_ferias" class="form-control"
                                    value="{{ Carbon::parse($periodo_de_ferias->dt_fim_periodo_de_licenca)->format('d/m/Y') }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="row">
                            @if ($periodo_de_ferias->dt_ini_b == null)
                                <div class="col-md-4 mb-3">
                                    <label for="inicio_primeiro_periodo_ferias">Data de Início do 1° Período de
                                        Férias:</label>
                                    <input type="date" id="inicio_primeiro_periodo_ferias" class="form-control"
                                        value="{{ isset($periodo_de_ferias->dt_ini_a) ? $periodo_de_ferias->dt_ini_a : '--' }}"
                                        readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fim_primeiro_periodo_ferias">Data de Término do 1° Período de
                                        Férias:</label>
                                    <input type="date" id="fim_primeiro_periodo_ferias" class="form-control"
                                        value="{{ isset($periodo_de_ferias->dt_fim_a) ? $periodo_de_ferias->dt_fim_a : '--' }}"
                                        readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="dias_primeiro_periodo_ferias">Dias do 1° Período de Férias:</label>
                                    <input type="text" id="dias_primeiro_periodo_ferias" class="form-control"
                                        value="{{ isset($periodo_de_ferias->nr_dias_per_a) ? $periodo_de_ferias->nr_dias_per_a : '--' }}"
                                        readonly>
                                </div>
                            @else
                                <div class="col-md-4 mb-3">
                                    <label for="inicio_primeiro_periodo_ferias">Data de Início do 1° Período de
                                        Férias:</label>
                                    <input type="date" id="inicio_primeiro_periodo_ferias" class="form-control"
                                        value="{{ isset($periodo_de_ferias->dt_ini_a) ? $periodo_de_ferias->dt_ini_a : '--' }}"
                                        readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fim_primeiro_periodo_ferias">Data de Término do 1° Período de
                                        Férias:</label>
                                    <input type="date" id="fim_primeiro_periodo_ferias" class="form-control"
                                        value="{{ isset($periodo_de_ferias->dt_fim_a) ? $periodo_de_ferias->dt_fim_a : '--' }}"
                                        readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="dias_primeiro_periodo_ferias">Dias do 1° Período de Férias:</label>
                                    <input type="text" id="dias_primeiro_periodo_ferias" class="form-control"
                                        value="{{ isset($periodo_de_ferias->nr_dias_per_a) ? $periodo_de_ferias->nr_dias_per_a + 1 : '--' }}"
                                        readonly>
                                </div>
                            @endif

                        </div>

                        @if (!empty($periodo_de_ferias->dt_fim_b))
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="inicio_primeiro_periodo_ferias">Data de Início do 2° Período de
                                        Férias:</label>
                                    <input type="date" id="inicio_primeiro_periodo_ferias" class="form-control"
                                        value="{{ $periodo_de_ferias->dt_ini_b }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fim_primeiro_periodo_ferias">Data de Término do 2° Período de
                                        Férias:</label>
                                    <input type="date" id="fim_primeiro_periodo_ferias" class="form-control"
                                        value="{{ $periodo_de_ferias->dt_fim_b }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="dias_primeiro_periodo_ferias">Dias do 2° Período de Férias:</label>
                                    <input type="text" id="dias_primeiro_periodo_ferias" class="form-control"
                                        value="{{ $periodo_de_ferias->nr_dias_per_b + 1 }}" readonly>
                                </div>
                            </div>
                        @endif
                        @if (!empty($periodo_de_ferias->dt_fim_c))
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="inicio_primeiro_periodo_ferias">Data de Início do 3° Período de
                                        Férias:</label>
                                    <input type="date" id="inicio_primeiro_periodo_ferias" class="form-control"
                                        value="{{ $periodo_de_ferias->dt_ini_c }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="fim_primeiro_periodo_ferias">Data de Término do 3° Período de
                                        Férias:</label>
                                    <input type="date" id="fim_primeiro_periodo_ferias" class="form-control"
                                        value="{{ $periodo_de_ferias->dt_fim_c }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="dias_primeiro_periodo_ferias">Dias do 3° Período de Férias:</label>
                                    <input type="text" id="dias_primeiro_periodo_ferias" class="form-control"
                                        value="{{ $periodo_de_ferias->nr_dias_per_c + 1 }}" readonly>
                                </div>
                            </div>
                        @endif

                        <div class="row justify-content-around">

                            <div class="col-md-4 mb-3">
                                <label for="inicio_primeiro_periodo_ferias">Adiantou Décimo Terceiro ?</label>
                                <input type="text" id="adiantou_decimo" class="form-control"
                                    value="{{ $periodo_de_ferias->adianta_13sal ? 'Sim' : 'Não' }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="inicio_primeiro_periodo_ferias">Data Limite Periodo Concessivo:</label>
                                <input type="text" id="dia_limite_gozzo" class="form-control"
                                    value="{{ Carbon::parse($periodo_de_ferias->dia_limite_para_gozo_de_ferias)->format('d/m/Y') }}"
                                    readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="inicio_primeiro_periodo_ferias">Vendeu o período de férias ?</label>
                                @if ($periodo_de_ferias->venda_um_terco == 0)
                                    <input type="text" id="adiantou_decimo" class="form-control"
                                        value="Não foi vendido nenhum periodo de Ferias" readonly>
                                @endif

                                @if ($periodo_de_ferias->venda_um_terco == 1)
                                    <input type="text" id="adiantou_decimo" class="form-control"
                                        value="Vendidos os 10 primeiros dias" readonly>
                                @endif
                                @if ($periodo_de_ferias->venda_um_terco == 2)
                                    <input type="text" id="adiantou_decimo" class="form-control"
                                        value="Vendidos os 10 ultimos dias" readonly>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered border-secondary table-hover align-middle"
                        style="margin-top:10px;">
                        <thead style="text-align: center;">
                            <tr style="background-color: #d6e3ff; color:#000000">
                                <th class="text-align: center">Data de Acontecimento</th>
                                <th class="text-align: center">Motivo Acontecimento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historico_recusa_ferias as $recusa_de_ferias)
                                <tr>
                                    <td class="text-align: center">
                                        {{ Carbon::parse($recusa_de_ferias->data_de_acontecimento)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-align: center">{{ $recusa_de_ferias->motivo_retorno }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <br>
                <div class="row justify-content-center">
                    <div class="col-5">
                        <a href="{{ url()->previous() }}">
                            <button class="btn btn-outline-primary" style="width: 100%">Voltar para a página
                                anterior</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
