@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@section('head')
    <title>Período de Férias</title>
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
                                Período de Férias
                            </h5>
                        </div>
                    </div>
                    <br>
                    <div class="card-body">
                        <form action="{{ route('IndexGerenciarFerias') }}">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="idnomefuncionario" class="form-label">Nome do Funcionário</label>
                                        <input type="text" class="form-control" id="idnomefuncionario"
                                            name="nomefuncionario"
                                            @if (!is_null($nome_funcionario)) value="{{ $nome_funcionario }}" @endif>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="mb-3">
                                        <label for="idanoconsulta" class="form-label">Selecione o Período
                                            Aquisitivo</label>
                                        <select class="form-select" id="idanoconsulta" name="anoconsulta">
                                            @if (!is_null($ano_consulta))
                                                <option value="{{ $ano_consulta }}">{{ $ano_consulta }}
                                                    - {{ $ano_consulta + 1 }}</option>
                                            @endif
                                            <option value="*">Todos</option>
                                            @foreach ($anos_possiveis as $ano_possivel)
                                                <option value="{{ $ano_possivel->ano_de_referencia }}">
                                                    {{ $ano_possivel->ano_de_referencia }} -
                                                    {{ $ano_possivel->ano_de_referencia + 1 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="mb-3">
                                        <label for="idanoconsulta" class="form-label">Selecione o Status</label>
                                        <select class="form-select" id="idstatusconsulta" name="statusconsulta">
                                            @if ($status_consulta_atual)
                                                <option value="{{ $status_consulta_atual->id }}">
                                                    {{ $status_consulta_atual->nome }}
                                                </option>
                                            @endif
                                            <option value="">Todos</option>

                                            @foreach ($status_ferias as $status)
                                                <option value="{{ $status->id }}">
                                                    {{ $status->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <button type="submit" class="btn btn-outline-secondary"
                                        style="margin-top: 11%; width:100%">Pesquisar
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                    <br>
                    <hr>
                    <br>
                    @if (!empty($periodo_aquisitivo))
                        <div class="container-fluid">
                            <div class="table-responsive">
                                <form action="{{ route('enviar-ferias') }}" method="post">
                                    <table
                                        class="table table-sm table-striped table-bordered border-secondary table-hover align-middle"
                                        style="margin-top:10px;">
                                        <thead style="text-align: center;">
                                            <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                                <th scope="col">

                                                    <p>Todos: <input type="checkbox" id="toggleCheckbox"
                                                            class="form-check-input"></p>
                                                </th>
                                                <th scope="col">Nome do Funcionário</th>
                                                <th scope="col">Periodo de Aquisitivo</th>
                                                <th scope="col">Início 1</th>
                                                <th scope="col">Fim 1</th>
                                                <th scope="col">Início 2</th>
                                                <th scope="col">Fim 2</th>
                                                <th scope="col">Início 3</th>
                                                <th scope="col">Fim 3</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Motivo</th>
                                                <th scope="col">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="idtable">
                                            @foreach ($periodo_aquisitivo as $periodos_aquisitivos)
                                                <tr @if ($periodos_aquisitivos->em_conflito) class="table-warning" @endif>
                                                    <td class="text-center align-middle">
                                                        @if ($periodos_aquisitivos->id_status_pedido_ferias == 3 or $periodos_aquisitivos->id_status_pedido_ferias == 5)
                                                            <div
                                                                class="form-check form-switch d-flex justify-content-center">
                                                                <input class="form-check-input checkbox-trigger"
                                                                    type="checkbox"
                                                                    id="id_checkbox_{{ $periodos_aquisitivos->id_ferias }}"
                                                                    name="checkbox[]"
                                                                    value="{{ $periodos_aquisitivos->id_ferias }}">
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center">
                                                        {{ $periodos_aquisitivos->nome_completo_funcionario ?? 'N/A' }}
                                                    </td>
                                                    <td style="text-align: center">
                                                        {{ $periodos_aquisitivos->ano_de_referencia }}
                                                        - {{ $periodos_aquisitivos->ano_de_referencia + 1 }}
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
                                                        {{ $periodos_aquisitivos->status_pedido_ferias }}
                                                    </td>
                                                    <td style="text-align: center">
                                                        {{ $periodos_aquisitivos->motivo_retorno }}
                                                    </td>

                                                    <td>
                                                        @if (
                                                            $periodos_aquisitivos->id_status_pedido_ferias == 1 or
                                                                $periodos_aquisitivos->id_status_pedido_ferias == 3 or
                                                                $periodos_aquisitivos->id_status_pedido_ferias == 5)
                                                            <a href="{{ route('CriarFerias', ['id' => $periodos_aquisitivos->id_ferias]) }}"
                                                                class="btn btn-outline-success" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Incluir">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('HistoricoRecusaFerias', ['id' => $periodos_aquisitivos->id_ferias]) }}"
                                                            class="btn btn-outline-secondary" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Histórico Férias"
                                                            style="font-size: 1rem; color:#0e0b16;">
                                                            <i class="bi bi-search"></i>
                                                        </a>
                                                        @if ($periodos_aquisitivos->id_status_pedido_ferias == 3)
                                                            <a href="{{ route('EnviaFeriasIndividual', ['id' => $periodos_aquisitivos->id_ferias]) }}"
                                                                class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Enviar Ferias "
                                                                style="font-size: 1rem; color:#0e0b16;">
                                                                <i class="bi bi-arrow-right-square"></i>
                                                            </a>
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
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                            </div>
                        </div>
                    @endif
                    <div class="row d-flex justify-content-center">
                        @csrf
                        <br>
                        <div class="col-sm-12 col-md-4">
                            <button type="submit" class="btn btn-success col-md-3 col-12 mt-5 mt-md-0"
                                style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; margin:5px; width:100%">
                                Enviar Férias
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#toggleCheckbox').change(function() {
                // Verifica o estado do checkbox de controle
                const isChecked = $(this).is(':checked');
                // Alterna o estado dos checkboxes
                $('.checkbox-trigger').prop('checked', isChecked);
            });
        });
    </script>


    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

@endsection
