@extends('layouts.app')

@section('head')
    <title>Novo Contrato</title>
@endsection

@section('content')
    <form method="post" action="/armazenar-contrato/{{ $funcionario->id }}" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Novo Contrato
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <input class="form-control" type="text" value="{{ $funcionario->nome_completo }}"
                                        id="iddt_inicio" name="dt_inicio" required="required" disabled>
                                </div>
                            </div>
                            <br>
                            <hr>

                            {{-- Linha principal dos inputs --}}
                            <div class="form-group row  justify-content-between" id="linha_contrato">
                                <div class="col-md-2 col-sm-12">Número de Matrícula
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="numeric" maxlength="11"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="1" name="matricula" value="{{ old('matricula') }}" required="required">
                                </div>
                                <div class="form-group col-md-2 col-sm-12">Tipo de Contrato
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        name="tipo_contrato" required="required" id="id_tipo_contrato">
                                        @foreach ($tipocontrato as $tiposcontrato)
                                            <option value="{{ $tiposcontrato->id }}">{{ $tiposcontrato->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2 col-sm-12">Data de Início
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="" id="iddt_inicio" name="dt_inicio" required="required">
                                </div>
                                <div class="form-group col-md-2 col-sm-12" id="id_dt_fim">Data de Fim
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="" id="iddt_fim" name="dt_fim">
                                </div>
                                <div class="form-group col-md-2 col-sm-12">Arquivo de Anexo
                                    <input type="file" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control form-control-sm mb-2" name="ficheiro" id="idficheiro">
                                </div>
                                {{-- Novo campo será inserido aqui --}}
                                <span id="campo_aprendiz_dinamico"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div>
            <a class="btn btn-danger col-md-3 col-2 mt-4 offset-md-1" href="/gerenciar-contrato/{{ $funcionario->id }}"
                role="button">Cancelar</a>

            <button type="submit" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-3" id="sucesso">
                Confirmar
            </button>
        </div>
    </form>

    {{-- Script para adicionar o campo dinamicamente na mesma linha --}}
    <script>
        $(document).ready(function() {
            $("#id_tipo_contrato").change(function(e) {
                e.preventDefault();
                var tipo_contrato = $(this).val();

                // Remove o campo se ele já estiver na tela
                $("#campo_data_fim_aprendiz").remove();

                if (tipo_contrato == 5) {
                    $('#campo_aprendiz_dinamico').html(
                        '<div class="form-group col-md-2 col-sm-12" id="campo_data_fim_aprendiz">' +
                        '<label for="data_fim_contrato_jovem_aprendiz">Fim Aprendiz</label>' +
                        '<input class="form-control" style="border: 1px solid #999999; padding: 5px;" ' +
                        'type="date" name="data_fim_contrato_jovem_aprendiz" id="data_fim_contrato_jovem_aprendiz" required> ' +
                        '</div>'
                    );
                }
            });
            // Executa a mudança automaticamente se for um "editar"
            $("#id_tipo_contrato").trigger('change');
        });
    </script>
@endsection
