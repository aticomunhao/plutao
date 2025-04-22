@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@section('head')
    <title>Gerenciar Férias</title>
@endsection
@section('content')
    <div class="container-fluid"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089;">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Gerenciar Férias
                            </h5>
                        </div>
                    </div>
                    <br>
                    <div class="card-body">
                        <form action="/administrar-ferias" method="GET">
                            <div class="row justify-content-between">
                                <div class="col-md-3 col-sm-12">
                                    <h5>Nome do Funcionário</h5>
                                    <input type="text" name="nomefuncionario" id="idnomefuncionario"
                                        @if ($nome_funcionario != null) value="{{ $nome_funcionario }}" @endif
                                        class="form-control">
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <h5>Período Aquisitivo</h5>
                                    <select class="form-select" aria-label="Ano" name="anoconsulta" id="idanoconsulta">
                                        @if ($ano_consulta != null)
                                            <option value="{{ $ano_consulta }}">{{ $ano_consulta }} -
                                                {{ $ano_consulta + 1 }}
                                            </option>
                                        @endif
                                        <option value="*">Todos</option>
                                        @foreach ($anos_possiveis as $ano_possivel)
                                            <option value="{{ $ano_possivel->ano_de_referencia }}">
                                                {{ $ano_possivel->ano_de_referencia }}
                                                -{{ $ano_possivel->ano_de_referencia + 1 }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <h5>Setor</h5>
                                    <select class="form-select select2" aria-label="Ano" name="setorconsulta" id="idsetorconsulta">
                                        
                                        @foreach ($setores_unicos as $setor_unico)
                                            <option @if (request('setorconsulta') == $setor_unico->id) {{ 'selected="selected"' }} @endif
                                            value="{{ $setor_unico->id }}">{{ $setor_unico->sigla }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <h5>Selecione o Status</h5>
                                    <select class="form-select" aria-label="Ano" name="statusconsulta"
                                        id="idstatusconsulta">
                                        @if ($status_consulta_atual != null)
                                            <option value="{{ $status_consulta_atual->id }}">
                                                {{ $status_consulta_atual->nome }}
                                            </option>
                                        @endif
                                        <option value="">Todos</option>
                                        @foreach ($status_ferias as $id_status_ferias)
                                            <option value="{{ $id_status_ferias->id }}">
                                                {{ $id_status_ferias->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-12">
                                    <button type="submit" class="btn btn-submit btn-primary"
                                        style="width:100%; margin-top: 20%">Pesquisar</button>
                                </div>
                        </form>

                        <div class="col-md-1">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#modalCriarFerias"
                                style="font-size: 1rem; box-shadow: 1px 2px 5px #000000;margin-top: 20%"
                                data-toggle="tooltip" data-placement="top" title="Texto ao passar o mouse">
                                <i class="bi bi-plus-square"></i>
                            </button>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="modalCriarFerias" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Gerar novo período de Férias
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('AbreFerias') }}">
                                            @csrf
                                            <h3>Periodo Aquisitivo</h3>
                                            <select class="form-select custom-select" aria-label="Ano" name="ano_referencia"
                                                id="ano" style="color: #0e0b16">
                                                @foreach ($listaAnos as $ano)
                                                    <option value="{{ $ano }}">{{ $ano }}
                                                        - {{ $ano + 1 }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                            style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; ">Close</button>
                                        <button type="submit" class="btn btn-success"
                                            style="box-shadow: 1px 2px 5px #0e0b16;">Gerar Novo Período
                                        </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim Modal-->
                    </div>
                    <br>
                    <div id="notificacao">

                    </div>
                    <hr>
                    <div class="row d-flex justify-content-evenly ">
                        @foreach ($contagemStatus as $contagem_individual_status)
                            <div class="col-auto">
                                <div class="card shadow-sm border-0" style="width: 8rem; text-align: center;">
                                    <div class="card-body p-2" style=" box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.2);">
                                        <h5 class="card-title  " style="font-size: 1.1rem;">
                                            {{ $contagem_individual_status->status_pedido_ferias }}:
                                            {{ $contagem_individual_status->total }}
                                        </h5>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>



                    <br>


                    @if (!empty($periodo_aquisitivo))
                        <div style="max-height: 600px; overflow-y: auto;">
                            <div class="table-reponsive">
                                <table
                                    class="table table-sm table-striped table-bordered border-secondary table-hover align-middle"
                                    style="margin-block-start:10px; table-layout: auto;" id="ferias-table">
                                    <thead
                                        style="text-align: center; position: sticky; top: 0; z-index: 1; background-color: #d6e3ff;">
                                        <tr style="font-size:17px; color:#000000;">
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Nome do Funcionário
                                            </th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Setor</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;" colspan="2">
                                                Periodo
                                                Aquisitivo</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;" colspan="2">
                                                Periodo
                                                Concessivo</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Data Limite <br>
                                                Inicio de Férias
                                            </th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Início 1</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Fim 1</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Início 2</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Fim 2</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Início 3</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Fim 3</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Status</th>
                                            <th scope="col-md-auto" style="position: sticky; top: 0;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="idtable">
                                        @foreach ($periodo_aquisitivo as $periodos_aquisitivos)
                                            <tr>
                                                <td style="text-align: center">
                                                    {{ $periodos_aquisitivos->nome_completo_funcionario ?? 'N/A' }}</td>
                                                <td style="text-align: center">
                                                    {{ $periodos_aquisitivos->sigla_do_setor ?? 'N/A' }}</td>
                                                <td style="text-align: center">
                                                    {{ Carbon::parse($periodos_aquisitivos->inicio_periodo_aquisitivo)->format('d/m/y') }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ Carbon::parse($periodos_aquisitivos->fim_periodo_aquisitivo)->format('d/m/y') }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ Carbon::parse($periodos_aquisitivos->dt_inicio_periodo_de_licenca)->format('d/m/y') }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ Carbon::parse($periodos_aquisitivos->dt_fim_periodo_de_licenca)->format('d/m/y') }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ Carbon::parse($periodos_aquisitivos->dia_limite_para_gozo_de_ferias)->format('d/m/y') }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $periodos_aquisitivos->dt_ini_a ? Carbon::parse($periodos_aquisitivos->dt_ini_a)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $periodos_aquisitivos->dt_fim_a ? Carbon::parse($periodos_aquisitivos->dt_fim_a)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $periodos_aquisitivos->dt_ini_b ? Carbon::parse($periodos_aquisitivos->dt_ini_b)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $periodos_aquisitivos->dt_fim_b ? Carbon::parse($periodos_aquisitivos->dt_fim_b)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $periodos_aquisitivos->dt_ini_c ? Carbon::parse($periodos_aquisitivos->dt_ini_c)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $periodos_aquisitivos->dt_fim_c ? Carbon::parse($periodos_aquisitivos->dt_fim_c)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $periodos_aquisitivos->status_pedido_ferias }}</td>
                                                <td style="text-align: center">
                                                    <!-- Adicionar lógica para botões de ações -->
                                                    @if ($periodos_aquisitivos->id_status_pedido_ferias == 4)
                                                        <a href="{{ route('autorizarFerias', ['id' => $periodos_aquisitivos->id_ferias]) }}"
                                                            class="btn btn-outline-success" title="Autorizar Férias"><i
                                                                class="bi bi-check2"></i></a>
                                                        <a href="{{ route('FormularioFerias', ['id' => $periodos_aquisitivos->id_ferias]) }}"
                                                            class="btn btn-outline-danger" title="Recusar Férias"><i
                                                                class="bi bi-x"></i></a>
                                                    @else
                                                        <a class="btn btn-outline-secondary disabled"
                                                            aria-label="Close"><i class="bi bi-check2"></i></a>
                                                        <a class="btn btn-outline-secondary disabled"
                                                            aria-label="Close"><i class="bi bi-x"></i></a>
                                                    @endif
                                                    @if ($periodos_aquisitivos->id_status_pedido_ferias == 6)
                                                        <button type="button" class="btn btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal{{ $periodos_aquisitivos->id_ferias }}"
                                                            title="Reabrir Formulário"><i
                                                                class="bi bi-arrow-counterclockwise"></i></button>
                                                        <!-- Modal -->
                                                        <div class="modal fade"
                                                            id="exampleModal{{ $periodos_aquisitivos->id_ferias }}"
                                                            tabindex="-1" role="dialog">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary text-white">
                                                                        <h2 class="modal-title">Reabrir Formulário</h2>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Deseja Reabrir as férias do funcionário: <span
                                                                            class="text-primary">{{ $periodos_aquisitivos->nome_completo_funcionario ?? 'N/A' }}</span>?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-bs-dismiss="modal">Cancelar</button>
                                                                        <a
                                                                            href="{{ route('ReabrirFormulario', ['id' => $periodos_aquisitivos->id_ferias]) }}"><button
                                                                                type="button"
                                                                                class="btn btn-primary">Confirmar</button></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <a href="{{ route('HistoricoRecusaFerias', ['id' => $periodos_aquisitivos->id_ferias]) }}"
                                                        class="btn btn-outline-secondary" title="Histórico"><i
                                                            class="bi bi-search"></i></a>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal{{ $periodos_aquisitivos->id_ferias }}">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade"
                                                        id="exampleModal{{ $periodos_aquisitivos->id_ferias }}"
                                                        tabindex="-1"
                                                        aria-labelledby="exampleModal{{ $periodos_aquisitivos->id_ferias }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-danger">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                                                        Confirmar Exclusão
                                                                    </h5>
                                                                    <button type="button"
                                                                        class="btn-close btn-close-black"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <p class="text-danger fw-bold">
                                                                        Tem certeza de que deseja excluir este período
                                                                        aquisitivo? Esta ação não pode ser desfeita.
                                                                    </p>
                                                                    <p><strong>Funcionario: </strong>
                                                                        {{ $periodos_aquisitivos->nome_completo_funcionario }}
                                                                    </p>
                                                                    <p><strong>Periodo Aquisitivo: </strong>
                                                                        {{ $periodos_aquisitivos->ano_de_referencia }}</p>
                                                                </div>
                                                                <div class="modal-footer d-flex justify-content-center">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">
                                                                        <i class="bi bi-arrow-left-circle"></i> Cancelar
                                                                    </button>
                                                                    <a
                                                                        href="{{ route('ExcluiFerias', [$periodos_aquisitivos->id_ferias]) }}">
                                                                        <button type="button" class="btn btn-danger">
                                                                            <i class="bi bi-trash-fill"></i> Excluir
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    </div>
    <script></script>
    {{-- <script>
        $(document).ready(function() {
            $('#idsetorconsulta').select2();
        });
    </script> --}}

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tt="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    <script>
        $(document).ready(function() {
            var listaDePeriodoDeFerias = @json($periodo_aquisitivo);
            var numeroDePeriodosParaAprovar = 0;

            $.each(listaDePeriodoDeFerias, function(index, periodo) {
                if (periodo.id_status_pedido_ferias == 4) {
                    numeroDePeriodosParaAprovar++;
                }
            });

            console.log(numeroDePeriodosParaAprovar);

            if (numeroDePeriodosParaAprovar > 0) {
                $('#notificacao').append(
                    `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Atenção!</strong> Existem ${numeroDePeriodosParaAprovar} períodos de férias para aprovação.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`
                );
            }
        });
    </script>
@endsection
