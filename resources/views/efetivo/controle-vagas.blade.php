@extends('layouts.app')
@section('head')
    <title>Controle de Vagas</title>
@endsection
@section('content')
    <form id="controleVagasForm" action="/controle-vagas" method="get">
        @csrf
        <div class="container-fluid"> {{-- Container completo da página  --}}
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089;">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Controle de Vagas
                                </h5>
                            </div>
                            <hr>
                            <div class="card-body">
                                <label for="1">Selecione a Forma de Pesquisa Desejada</label>
                                <br>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pesquisa" value="cargo"
                                        {{ $pesquisa == 'cargo' ? 'checked' : '' }} id="pesquisaCargo">
                                    <label class="form-check-label" for="pesquisaCargo">
                                        Cargo
                                    </label>
                                </div>
                                <div class="form-check col-12">
                                    <input class="form-check-input" type="radio" name="pesquisa" value="setor"
                                        id="pesquisaSetor" {{ $pesquisa == 'setor' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pesquisaSetor">
                                        Setor
                                    </label>
                                </div>
                            </div>
                            <div class="row" style="margin-left:5px">
                                <div class="col" id="cargoSelectContainer" style="display: none">
                                    <label for="1">Selecione o Cargo Desejado</label>
                                    <br>
                                    <select id="cargoSelect" class="form-select select2"
                                        name="cargo" multiple=multiple>
                                        <option></option>
                                        @php
                                            $cargosExibidos = []; // Array para armazenar os cargos únicos já exibidos
                                        @endphp
                                        @foreach ($cargo as $cargos)
                                            @if ($cargos->nomeCargo)
                                                <!-- Exibir apenas se o nome do cargo não for nulo -->
                                                @if (!in_array($cargos->nomeCargo, $cargosExibidos))
                                                    <!-- Adicionar à lista suspensa somente se o nome do cargo não tiver sido exibido anteriormente -->
                                                    <option value="{{ $cargos->idCargo }}">{{ $cargos->nomeCargo }}</option>
                                                    @php
                                                        $cargosExibidos[] = $cargos->nomeCargo; // Adiciona o cargo ao array de cargos únicos exibidos
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col" id="setorSelectContainer" style="display: none">
                                    <label for="1">Selecione o Setor Desejado</label>
                                    <br>
                                    <select id="setorSelect" class="form-select status select2 pesquisa-select"
                                        name="setor" multiple=multiple>
                                        <option></option>
                                        @php
                                            $setoresExibidos = []; // Array para armazenar os setores únicos já exibidos
                                        @endphp
                                        @foreach ($setor as $setores)
                                            @if ($setores->nomeSetor)
                                                <!-- Exibir apenas se o nome do setor não for nulo -->
                                                @if (!in_array($setores->nomeSetor, $setoresExibidos))
                                                    <!-- Adicionar à lista suspensa somente se o nome do setor não tiver sido exibido anteriormente -->
                                                    <option value="{{ $setores->idSetor }}">
                                                        {{ $setores->nomeSetor }} - {{ $setores->sigla }}</option>
                                                    @php
                                                        $setoresExibidos[] = $setores->nomeSetor; // Adiciona o setor ao array de setores únicos exibidos
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col" style="padding-top:20px;">
                                    <a href="/controle-vagas" type="button" class="btn btn-light"
                                        style="box-shadow: 1px 2px 5px #000000; margin-right: 5px" value="">Limpar</a>
                                    <input type="submit" value="Pesquisar" class="btn btn-success btn-light"
                                        style="box-shadow: 1px 2px 5px #000000; margin-right: 5px">
                                    <input type="hidden" name="tipo_pesquisa" id="tipoPesquisa"
                                        value="{{ $pesquisa }}">
                                    {{-- Botão de incluir --}}
                                    <a href="/incluir-vagas/" class="col">
                                        <button type="button" style="box-shadow: 1px 2px 5px #000000;"
                                            class="btn btn-success">
                                            Novo+
                                        </button>
                                    </a>

                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="table" style="padding-top:20px">
                                <table
                                    class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                                    <thead style="text-align: center;">
                                        <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                            <th class="col-md-4">SETOR/CARGO</th>
                                            <th class="col-md-1">VAGAS PREENCHIDAS</th>
                                            <th class="col-md-1">TOTAL DE VAGAS AUTORIZADAS</th>
                                            <th class="col-md-1">VAGAS REMANESCENTES</th>
                                            <th class="col-md-1">TOTAL DE VAGAS DE EXCELÊNCIA</th>
                                            <th class="col-md-2">AÇÕES</th>
                                        </tr>
                                    </thead>
                                    <!-- Primeira tabela -->
                                    <tbody style="font-size: 15px; color:#000000;" id='cargoTabela'>
                                        @php
                                            $totalFuncionarios1 = 0;
                                            $totalVagas1 = 0;
                                            $cargosExibidos = [];
                                        @endphp

                                        @foreach ($cargo as $cargos)
                                            @if ($cargos->nomeCargo && !in_array($cargos->nomeCargo, $cargosExibidos))
                                                @php
                                                    $vagasTotais = 0;
                                                    $vagasExcelencia = 0;
                                                @endphp
                                                @foreach ($vaga as $v)
                                                    @if ($v->idDoCargo == $cargos->idCargo)
                                                        @php
                                                            $vagasTotais += $v->total_vagas;
                                                            $vagasExcelencia += $v->vExcelencia;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <td>{{ $cargos->nomeCargo }}</td>
                                                    <td style="text-align: center">{{ $cargos->quantidade_funcionarios }}
                                                    </td>
                                                    <td style="text-align: center">{{ $vagasTotais }}</td>
                                                    <td style="text-align: center">
                                                        {{ $vagasTotais - $cargos->quantidade_funcionarios }}</td>
                                                    <td style="text-align: center">{{ $vagasExcelencia }}</td>
                                                    <td style="text-align: center">NÃO DISPONÍVEL</td>
                                                </tr>
                                                @php
                                                    $totalFuncionarios1 += $cargos->quantidade_funcionarios;
                                                    $totalVagas1 += $vagasTotais;
                                                    $cargosExibidos[] = $cargos->nomeCargo;
                                                @endphp
                                            @endif
                                        @endforeach

                                        <!-- Total da primeira tabela -->
                                        <tr style="background-color: #d6e3ff">
                                            <td style="text-align: center"><strong>Total</strong></td>
                                            <td style="text-align: center"><strong>{{ $totalFuncionarios1 }}</strong></td>
                                            <td style="text-align: center"><strong>{{ $totalVagas1 }}</strong></td>
                                            <td style="text-align: center">
                                                <strong>{{ $totalVagas1 - $totalFuncionarios1 }}</strong>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                    </tbody>

                                    <!-- Segunda tabela -->
                                    <tbody style="font-size: 15px; color:#000000;" id='setorTabela'>
                                        @php
                                            $totalFuncionarios2 = 0;
                                            $totalVagas2 = 0;
                                        @endphp

                                        @foreach ($setor as $setores)
                                            @foreach ($setores->bola as $vagaDois)
                                                <tr>
                                                    <td>{{ $setores->nomeSetor }} / {{ $vagaDois->nomeCargo }}</td>
                                                    <td style="text-align: center">
                                                        {{ $vagaDois->gato->first()->quantidade }}
                                                    </td>
                                                    <td style="text-align: center">{{ $vagaDois->vagas }}</td>
                                                    <td style="text-align: center">
                                                        {{ $vagaDois->vagas - $vagaDois->gato->first()->quantidade }}</td>
                                                    <td style="text-align: center">{{ $vagaDois->vExcelencia }}</td>
                                                    <td style="text-align: center">
                                                        <a href="/editar-vagas/{{ $vagaDois->idVagas }}"
                                                            class="btn btn-outline-warning" data-tt="tooltip"
                                                            style="font-size: 1rem; color:#303030" data-placement="top"
                                                            title="Editar">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <a>
                                                            <button type="button"
                                                                class="btn btn-outline-danger delete-btn"
                                                                style="font-size: 1rem; color:#303030"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#A{{ $vagaDois->idVagas }}">
                                                                <i class="bi bi-trash">
                                                                </i>
                                                            </button>
                                                        </a>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="A{{ $vagaDois->idVagas }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header"
                                                                        style="background-color:#DC4C64;">
                                                                        <h5 class="modal-title" id="exampleModalLabel"
                                                                            style=" color:rgb(255, 255, 255)">Excluir
                                                                            Vagas</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body" style="text-align: center">
                                                                        Você realmente deseja excluir estas vagas?
                                                                    </div>
                                                                    <div class="modal-footer mt-2">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-bs-dismiss="modal">Cancelar</button>
                                                                        <a type="button" class="btn btn-primary"
                                                                            href="/excluir-vagas/{{ $vagaDois->idVagas }}">Confirmar
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--Fim Modal-->
                                                    </td>
                                                </tr>
                                                @php
                                                    $totalFuncionarios2 += $vagaDois->gato->first()->quantidade;
                                                    $totalVagas2 += $vagaDois->vagas;
                                                @endphp
                                            @endforeach
                                        @endforeach

                                        <!-- Total da segunda tabela -->
                                        <tr style="background-color: #d6e3ff">
                                            <td style="text-align: center"><strong>Total</strong></td>
                                            <td style="text-align: center"><strong>{{ $totalFuncionarios2 }}</strong></td>
                                            <td style="text-align: center"><strong>{{ $totalVagas2 }}</strong></td>
                                            <td style="text-align: center">
                                                <strong>{{ $totalVagas2 - $totalFuncionarios2 }}</strong>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>

                                    <!-- Contagem geral -->

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
