@extends('layouts.app')

@section('content')
    <div class="container-fluid"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Editar Salário
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">{{-- Faltando caminho para update --}}
                        <form method="post" action="{{ route('ArmazenarBaseSalarial', ['idf' => $idf]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-5">
                                    <input class="form-control" type="text" value="{{ $funcionario->nome_completo }}"
                                        id="iddt_inicio" name="dt_inicio" required="required" disabled>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col">
                                    <h5>Selecione o novo Tipo de Cargo</h5>
                                    <label for="opcoesDeTipoDeCargo">Escolha uma opção:</label>
                                    <select class="form-control" id="opcoesDeTipoDeCargo">
                                        @foreach ($tp_cargo as $tp_cargos)
                                            <option value="{{ $tp_cargos->idTpCargo }}">{{ $tp_cargos->nomeTpCargo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col">
                                    <h5>Cargo : </h5>
                                    <label for="cargos">Escolha uma opção:</label>
                                    <select name="cargo" class="form-control" id="cargos" disabled>

                                    </select>
                                </div>


                                <div class="col">
                                    <div id="funcaograt" hidden>
                                        <h5>Função Gratificada</h5>
                                        <label for="funcaogratificadaopcoes">Escolha uma opção:</label>
                                        <select class="form-control" name="funcaog" id="funcaogratificadaopcoes">

                                        </select>
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="row mt-4">
                        <div class="d-grid gap-1 col-2 mx-auto">
                            <a class="btn btn-danger btn-sm"
                                href="/gerenciar-base-salarial/{{ $funcionario->id_funcionario }}"
                                role="button">Cancelar</a>
                        </div>
                        <div class="d-grid gap-2 col-2 mx-auto">
                            <button type="submit" class="btn btn-primary btn-sm" id="sucesso">Confirmar</button>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {

            $("#opcoesDeTipoDeCargo").change(function(e) {
                var tipocargo = $(this).val();
                if (tipocargo == 2) {
                    $('#cargos').empty()
                    $.ajax({
                        type: "GET",
                        url: "/retorna-cargos-editar/" + tipocargo,
                        dataType: "json",
                        success: function(response) {
                            $('#cargos').prop('disabled', false);
                            $('#cargos').prop('required', true);;
                            $.each(response, function(index, item) {
                                $("#cargos").append("<option value =" + item.id + "> " +
                                    item.nome + "</option>");
                            });

                        }
                    });

                    $.ajax({
                        type: "GET",
                        url: "/retorna-funcao-gratificada",
                        dataType: "json",
                        success: function(response) {
                            $('#funcaograt').prop('hidden', false)
                            $.each(response, function(index, item) {
                                $("#funcaogratificadaopcoes").append("<option value =" +
                                    item.id + "> " +
                                    item.nome + "</option>");
                            });

                        }
                    });
                } else {

                    $('#cargos').empty();
                    $('#funcaograt').prop('hidden', true);
                    $.ajax({
                        type: "GET",
                        url: "/retorna-cargos-editar/" + tipocargo,
                        dataType: "json",
                        success: function(response) {
                            $("#funcaogratificadaopcoes").empty();
                            $('#cargos').prop('disabled', false);
                            $('#cargos').prop('required', true);;
                            $.each(response, function(index, item) {
                                $("#cargos").append("<option value =" + item.id + "> " +
                                    item.nome + "</option>");
                            });

                        }
                    });



                }

            });
        });
    </script>
@endsection
@php
    function formatSalary($salary)
    {
        return 'R$ ' . number_format($salary, 2, ',', '.');
    }

@endphp
