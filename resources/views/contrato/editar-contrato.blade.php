@extends('layouts.app')

@section('head')
    <title>Editar Contrato</title>
@endsection

@section('content')
    <form method="post" action="/atualizar-contrato/{{ $contrato->id }}" enctype="multipart/form-data">
        @csrf

        <div class="container-fluid">
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="row">
                                <h5 class="col-12" style="color: #355089">Editar Contrato</h5>
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Nome do Funcionário (apenas leitura) --}}
                            <div class="row mb-3">
                                <div class="col-5">
                                    <label>Funcionário</label>
                                    <input type="text" class="form-control" value="{{ $funcionario->nome_completo }}"
                                        disabled>
                                </div>
                            </div>

                            <hr>

                            {{-- Linha principal dos inputs --}}
                            <div class="form-group row mt-2 justify-content-between" id="linha_contrato">

                                {{-- Matrícula --}}
                                <div class="form-group col-md-2 col-sm-12">
                                    <label for="matricula">Número de Matrícula</label>
                                    <input id="matricula" name="matricula" class="form-control" type="text"
                                        maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                        value="{{ old('matricula', $contrato->matricula) }}" required>
                                </div>

                                {{-- Tipo de Contrato --}}
                                <div class="form-group col-md-2 col-sm-12">
                                    <label for="id_tipo_contrato">Tipo de Contrato</label>
                                    <select id="id_tipo_contrato" name="tipo_contrato" class="form-select" required>
                                        @foreach ($tipocontrato as $tipo)
                                            <option value="{{ $tipo->id }}"
                                                {{ $tipo->id == $contrato->tp_contrato ? 'selected' : '' }}>
                                                {{ $tipo->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Data de Início --}}
                                <div class="form-group col-md-2 col-sm-12">
                                    <label for="iddt_inicio">Data de Início</label>
                                    <input id="iddt_inicio" name="dt_inicio" class="form-control" type="date"
                                        value="{{ old('dt_inicio', $contrato->dt_inicio) }}" required>
                                </div>

                                {{-- Data de Fim (padrão) --}}
                                <div class="form-group col-md-2 col-sm-12" id="id_dt_fim">
                                    <label for="iddt_fim">Data de Fim</label>
                                    <input id="iddt_fim" name="dt_fim" class="form-control" type="date"
                                        value="{{ old('dt_fim', $contrato->dt_fim) }}">
                                </div>

                                {{-- Arquivo Atual --}}
                                <div class="form-group col-md-1 col-sm-12 text-center">
                                    <label>Arquivo Atual</label>
                                    @if ($contrato->caminho)
                                        <p>
                                            <a href="{{ asset($contrato->caminho) }}" target="_blank">
                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            </a>
                                        </p>
                                    @else
                                        <p class="text-muted small">—</p>
                                    @endif
                                </div>

                                {{-- Novo Arquivo --}}
                                <div class="form-group col-md-2 col-sm-12">
                                    <label for="idficheiro">Novo Arquivo</label>
                                    <input id="idficheiro" name="ficheiroNovo" class="form-control form-control-sm"
                                        type="file">
                                </div>

                                {{-- Aqui será injetado o “Fim Aprendiz” --}}
                                <div id="campo_aprendiz_dinamico"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="row">
            <div class="col-md-3">
                <a href="/gerenciar-contrato/{{ $funcionario->id }}" class="btn btn-danger w-100">Cancelar</a>
            </div>
            <div class="col-md-3 offset-md-6">
                <button type="submit" class="btn btn-primary w-100">Confirmar</button>
            </div>
        </div>
    </form>

    {{-- Script para controlar o campo “Fim Aprendiz” --}}
    <script>
        $(document).ready(function() {
            function atualizaAprendiz() {
                var tipo = $("#id_tipo_contrato").val();
                // Remove se já existia
                $("#campo_data_fim_aprendiz").remove();

                // Se for Jovem Aprendiz (id = 5), insere o campo na row
                if (tipo == 5) {
                    var valorPrevisto =
                        "{{ old('data_fim_contrato_jovem_aprendiz', $contrato->data_fim_prevista) }}";
                    $("#campo_aprendiz_dinamico").html(
                        '<div class="form-group col-md-2 col-sm-12" id="campo_data_fim_aprendiz">' +
                        '<label for="data_fim_contrato_jovem_aprendiz">Fim Aprendiz</label>' +
                        '<input id="data_fim_contrato_jovem_aprendiz" ' +
                        'name="data_fim_contrato_jovem_aprendiz" ' +
                        'class="form-control" type="date" ' +
                        (valorPrevisto ? 'value="' + valorPrevisto + '"' : '') +
                        '>' +
                        '</div>'
                    );
                }
            }

            // Ao mudar o select
            $("#id_tipo_contrato").on('change', atualizaAprendiz);

            // No load, para preencher em caso de edição
            atualizaAprendiz();
        });
    </script>
@endsection
