@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@section('head')
    <title>Controle de Férias</title>
@endsection
@section('content')
    <form id="controleFeriasForm" action="/controle-ferias" method="get">
        @csrf
        <div class="container-fluid"> {{-- Container completo da página  --}}
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089;">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Controle de Férias
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md col-sm-12" style="margin-left:5px">
                                    <label for="1">Setor</label>
                                    <select id="idsetor" class="form-select select2" name="setor">
                                        @if ($setor_selecionado != null)
                                            <option value="{{ $setor_selecionado->id }}">{{ $setor_selecionado->nome }}
                                            </option>
                                        @endif
                                        <option value="">Todos</option>

                                        @foreach ($setor as $setors)
                                            <option value="{{ $setors->idSetor }}">
                                                {{ $setors->siglaSetor }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md col-sm-12">
                                        <label for="1">Limite do Período Aquisitivo</label>
                                        <select id="idmes" class="form-select select2" name="mes">
                                            @if ($mes_selecionado != null)
                                                <option
                                                        value="{{$mes_selecionado['indice']}}">{{$mes_selecionado['nome']}}</option>
                                            @endif
                                            <option value="">Todos</option>

                                            @foreach ($mes as $meses => $nome)
                                                <option value="{{ $meses }}">
                                                    {{ $nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                <div class="col-md col-sm-12">
                                    <label for="1">Mês de Férias</label>
                                    <select id="idmes" class="form-select select2" name="mes_ferias">
                                        <option value="">Todos</option>
                                        @foreach ($mes as $meses => $nome)
                                            <option value="{{ $meses }}">
                                                {{ $nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md col-sm-12">
                                    <label for="1">Nome Funcionário</label>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" id="nomeFunc" name="nomeFunc" value="">
                                </div>
                                <div class="col-md col-sm-12">
                                    <label for="1">Período Aquisitivo</label>
                                    <select id="idano" class="form-select select2" name="ano">

                                        @if ($ano_selecionado != null)
                                            <option value="{{ $ano_selecionado }}">
                                                {{ $ano_selecionado }} - {{ $ano_selecionado + 1 }}
                                            </option>
                                        @endif
                                        <option value="">Todos</option>
                                        @foreach ($ano as $anos)
                                            <option value="{{ $anos->ano_de_referencia }}">
                                                {{ $anos->ano_de_referencia }} - {{ $anos->ano_de_referencia + 1 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-sm-12">Situação
                                    <select class="form-select custom-select"
                                        style="border: 1px solid #999999; padding: 5px;" id="" name="status"
                                        type="number">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <fieldset class="border rounded p-3 position-relative" style="margin-bottom: 20px">
                                        <legend class="w-auto"
                                            style="font-size: .9rem; padding: 0 10px; position: absolute; top: -12px; left: 20px; background: white;">
                                            Por Período</legend>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <label>Data Inicial</label>
                                                <input type="date" class="form-control" name="dt_inicio_periodo"
                                                    id="dt_inicio_periodo" style="border: 1px solid #999999; padding: 5px;">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label>Data Final</label>
                                                <input type="date" class="form-control" name="dt_fim_periodo"
                                                    id="dt_fim_periodo" style="border: 1px solid #999999; padding: 5px;">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col" style="margin-top: 20px;">
                                    <a href="/controle-ferias" type="button" class="btn btn-light btn-sm" type="button"
                                        style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; margin-right: 5px"
                                        value="">Limpar</a>
                                    <button class="btn btn-light btn-sm "
                                        style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; margin:5px;"
                                        {{-- Botao submit do formulario de pesquisa --}} type="submit">Pesquisar
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive" style="height: 400px; overflow-y: auto;">
                                <table
                                    class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                                    <thead style="text-align: center; position: sticky; top: 0; z-index: 1;">
                                        <tr style="background-color: #d6e3ff; font-size: 0.7rem; color:#000000;">
                                            <th class="col-md-2 align-middle">NOME DO EMPREGADO</th>
                                            <th class="col align-middle">DATA DE ADMISSÃO</th>
                                            <th class="col align-middle">PERÍODO AQUISITIVO</th>
                                            <!--<th class="col align-middle">SITUAÇÃO DO PERÍODO AQUISITIVO</th>
                                                             <th class="col">DATA LIMITE DO GOZO DE FÉRIAS</th>-->
                                            <th class="col align-middle">PERÍODO CONCESSIVO</th>
                                            <th class="col align-middle">INÍCIO DO 1° PERÍODO</th>
                                            <th class="col align-middle">FIM DO 1° PERÍODO</th>
                                            <th class="col align-middle">INÍCIO DO 2° PERÍODO</th>
                                            <th class="col align-middle">FIM DO 2° PERÍODO</th>
                                            <th class="col align-middle">INÍCIO DO 3° PERÍODO</th>
                                            <th class="col align-middle">FIM DO 3° PERÍODO</th>
                                            <th class="col align-middle">MÊS DAS FÉRIAS</th>
                                            <th class="col align-middle">SITUAÇÃO DAS FÉRIAS</th>
                                            <th class="col align-middle">VENDEU 1/3</th>
                                            <th class="col align-middle">PEDIU 13º</th>
                                            <th class="col align-middle">SETOR/CARGO</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.6rem; color:#000000;">
                                        @foreach ($ferias as $feriass)
                                            <tr style="text-align: center">
                                                <td class="nome-item" data-nome-completo="{{ $feriass->nome_completo }}"
                                                    data-nome-resumido="{{ $feriass->nome_resumido }}">
                                                    {{ $feriass->nome_completo }}
                                                </td>
                                                <td scope="">
                                                    {{ date('d/m/Y', strtotime($feriass->dt_inicio_funcionario)) }}
                                                </td>
                                                <td scope="">
                                                    {{ $feriass->ini_aqt ? carbon::parse($feriass->ini_aqt)->format('d/m/y') : '--' }}
                                                    -
                                                    {{ $feriass->fim_aqt ? carbon::parse($feriass->fim_aqt)->format('d/m/y') : '--' }}
                                                </td>
                                                <!--<td scope="">

                                                                </td>
                                                                <td scope="">
                                                                            {{ date('d/m/Y', strtotime($feriass->dt_fim_gozo)) }}
                                                                </td>-->
                                                <td>
                                                    {{ $feriass->dt_inicio_gozo ? carbon::parse($feriass->dt_inicio_gozo)->format('d/m/y') : '--' }}
                                                    -
                                                    {{ $feriass->dt_fim_gozo ? carbon::parse($feriass->dt_fim_gozo)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $feriass->dt_ini_a ? Carbon::parse($feriass->dt_ini_a)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $feriass->dt_fim_a ? Carbon::parse($feriass->dt_fim_a)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $feriass->dt_ini_b ? Carbon::parse($feriass->dt_ini_b)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $feriass->dt_fim_b ? Carbon::parse($feriass->dt_fim_b)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $feriass->dt_ini_c ? Carbon::parse($feriass->dt_ini_c)->format('d/m/y') : '--' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $feriass->dt_fim_c ? Carbon::parse($feriass->dt_fim_c)->format('d/m/y') : '--' }}
                                                </td>
                                                <td scope="">

                                                </td>
                                                <td scope="">
                                                    {{ $feriass->nome_stf }}
                                                </td>
                                                <td scope="">
                                                    @if ($feriass->vendeu_ferias == 'true')
                                                        Sim
                                                    @else
                                                        Não
                                                    @endif
                                                </td>
                                                <td scope="">
                                                    @if ($feriass->adianta_13sal == 'true')
                                                        Sim
                                                    @else
                                                        Não
                                                    @endif
                                                </td>
                                                <td scope="">
                                                    {{ $feriass->sigla_setor }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div style="margin-right: 10px; margin-left: 10px">
                            {{ $ferias->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>


    <style>
        .highlight {
            color: red;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const nomeItems = document.querySelectorAll('.nome-item');

            nomeItems.forEach(item => {
                const nomeCompleto = item.getAttribute('data-nome-completo');
                const nomeResumido = item.getAttribute('data-nome-resumido');

                if (nomeCompleto.includes(nomeResumido)) {
                    const partes = nomeCompleto.split(nomeResumido);
                    item.innerHTML = partes.join(`<span class="highlight">${nomeResumido}</span>`);
                }
            });
        });
    </script>
@endsection
