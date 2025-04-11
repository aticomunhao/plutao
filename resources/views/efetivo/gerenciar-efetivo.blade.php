@extends('layouts.app')
@section('head')
    <title>Gerenciar Efetivo</title>
@endsection
@section('content')
    <form id="gerenciarEfetivoForm" action="/gerenciar-efetivo" method="get">
        @csrf
        <div class="container-fluid"> {{-- Container completo da página  --}}
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089;">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Controle de Efetivo
                                </h5>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <label for="1">Selecione o Setor Desejado</label>
                                        <select id="idsetor" class="form-select select2" name="setor">
                                            <option></option>
                                            @foreach ($setor as $setores)
                                                <option value="{{ $setores->id_setor }}"
                                                    {{ $setores->nome == $setores->id_setor ? 'selected' : '' }}>
                                                    {{ $setores->nome }} - {{ $setores->sigla }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-12">Situação
                                        <select class="form-select custom-select"
                                            style="border: 1px solid #999999; padding: 5px;" id="4"
                                            name="statusPessoa">
                                            <option value="1" {{ $statusPessoa == '1' ? 'selected' : '' }}>Ativo
                                            </option>
                                            <option value="0" {{ $statusPessoa == '0' ? 'selected' : '' }}>Inativo
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md" style="padding-top: 24px">
                                        <button class="btn btn-light btn-sm "
                                            style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; margin-right: 5px"{{-- Botao submit do formulario de pesquisa --}}
                                            type="submit">Pesquisar
                                        </button>
                                        <a href="/gerenciar-efetivo" type="button" class="btn btn-light btn-sm"
                                            style="font-size: 1rem; box-shadow: 1px 2px 5px #000000"
                                            value="">Limpar</a>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <div style="text-align: center;">
                                    @if (!$setorId)
                                        <div
                                            style="display: inline-block; margin-right: 20px; position: relative; margin-bottom: 40px; margin-right: 200px;">
                                            <label style="margin-bottom: 5px;">Quantidade total<br>de Funcionários</label>
                                            <div
                                                style="width: 50px; height: 50px; background-color: lightblue; text-align: center; line-height: 50px; position: absolute; left: 50%; transform: translateX(-50%);">
                                                <span style="display: inline-block;">
                                                    {{ $totfunc }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    @if (!$setorId)
                                        <div
                                            style="display: inline-block; margin-right: 20px; position: relative; margin-bottom: 40px; margin-right: 200px;">
                                            <label style="margin-bottom: 5px;">Funcionários<br>Ativos</label>
                                            <div
                                                style="width: 50px; height: 50px; background-color: rgb(123, 231, 141); text-align: center; line-height: 50px; position: absolute; left: 50%; transform: translateX(-50%);">
                                                <span style="display: inline-block;">
                                                    {{ $totativo }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    @if (!$setorId)
                                        <div
                                            style="display: inline-block; margin-right: 20px; position: relative; margin-bottom: 40px; margin-right: 200px;">
                                            <label style="margin-bottom: 5px;">Funcionários<br>Inativos</label>
                                            <div
                                                style="width: 50px; height: 50px; background-color: rgb(233, 114, 110); text-align: center; line-height: 50px; position: absolute; left: 50%; transform: translateX(-50%);">
                                                <span style="display: inline-block;">
                                                    {{ $totinativo }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($setorId)
                                        <div
                                            style="display: inline-block; margin-right: 20px; position: relative; margin-bottom: 40px; margin-right: 200px;">
                                            <label style="margin-bottom: 5px;">Quantidade atual<br>de Funcionários</label>
                                            <div
                                                style="width: 50px; height: 50px; background-color: lightblue; text-align: center; line-height: 50px; position: absolute; left: 50%; transform: translateX(-50%);">
                                                <span style="display: inline-block;">
                                                    {{ $totalFuncionariosSetor }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    <div
                                        style="display: inline-block; margin-right: 20px; position: relative; margin-bottom: 40px; margin-right: 200px;">
                                        <label style="margin-bottom: 5px;">Vagas<br>Autorizadas</label>
                                        <div
                                            style="width: 50px; height: 50px; background-color: lightblue; text-align: center; line-height: 50px; position: absolute; left: 50%; transform: translateX(-50%);">
                                            <span style="display: inline-block;">
                                                {{ $totalVagasAutorizadas }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <h5 style="margin-left: 5px; color: #355089; margin-bottom: -10px">
                                    Empregados
                                </h5>
                                <div class="table" style="padding-top:20px">
                                    <table
                                        class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                                        <thead style="text-align: center;">
                                            <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                                <th class="col-4">Nome Completo</th>
                                                <th class="col-2">Cargo Regular</th>
                                                <th class="col-2">Função Gratificada</th>
                                                <th class="col-2">Data de Admissão</th>
                                                <th class="col-2">Telefone</th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-size: 15px; color:#000000;">
                                            @foreach ($base as $bases)
                                                <tr style="text-align: center">
                                                    <td scope="">
                                                        {{ $bases->nome_completo }}
                                                    </td>
                                                    <td scope="">
                                                        {{ $bases->nome_cargo_regular }}
                                                    </td>
                                                    <td scope="">
                                                        {{ $bases->nome_funcao_gratificada }}
                                                    </td>
                                                    <td scope="">
                                                        {{ \Carbon\Carbon::parse($bases->dt_inicio_funcionario)->format('d/m/Y') }}
                                                    </td>
                                                    <td scope="">
                                                        {{ $bases->celular }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div style="margin-right: 10px; margin-left: 10px">
                                {{ $base->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>





@endsection
