@extends('layouts.app')
@section('head')
    <title>Editar Dados Bancarios</title>
@endsection

{{-- @dd($desc_bancos) --}}
@section('content')
    <form method="post" action="{{ route('update.dadosbancarios.funcionario', ['id' => $contaBancaria->id]) }}"
        enctype="multipart/form-data">
        @csrf
        <div class="container-fluid"> {{-- Container completo da página  --}}
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Editar Dados Bancários
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
                            <div class="row">
                                <div class="form-group  col-xl-2 col-md-6 ">
                                    <label for="Banco">
                                        Banco
                                    </label>
                                    <select id="idbanco" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" name="desc_banco" required="required">

                                        @foreach ($desc_bancos as $desc_banco)
                                            <option value="{{ $desc_banco->id_db }}">
                                                {{ str_pad($desc_banco->id_db, 3, '0', STR_PAD_LEFT) }} -  {{ $desc_banco->nome}}

                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-xl-4 col-md-6 mt-3 mt-md-0">
                                    <label for="agencia">
                                        Agencia
                                    </label>
                                    <select id="idagencia" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" name="tp_banco_ag" required="required">
                                        <option value="{{ $contaBancaria->tpbag }}" selected>
                                            {{ $contaBancaria->agencia }} - {{ $contaBancaria->desc_agen }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-xl-3 col-md-6 mt-3 mt-xl-0">Data de Inicio
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="{{ $contaBancaria->dt_inicio }}" id="3"
                                        name="dt_inicio" required="required">
                                </div>
                                <div class="form-group col-xl-3 col-md-6 mt-3 mt-xl-0">Data de Fim
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="{{ $contaBancaria->dt_fim }}" id="3" name="dt_fim">
                                </div>
                                <div class="form-group col-xl-4 col-md-4 mt-3 ">Numero da Conta
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="40" name="nmr_conta"
                                        value="{{ $contaBancaria->numeroconta }}" required="required">
                                </div>
                                <div class="form-group col-xl-4 col-md-4 mt-3">
                                    <label for="tconta">
                                        Tipo de Conta
                                    </label>
                                    <select id="tconta" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" aria-label="Default select example" name="tp_conta" required>
                                        <option value="{{ $contaBancaria->tp_conta_id }}">
                                            {{ $contaBancaria->nome_tipo_conta }}
                                        </option>
                                        @foreach ($tp_contas as $tp_conta)
                                            <option value="{{ $tp_conta->id }}">
                                                {{ $tp_conta->nome_tipo_conta }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-xl-4 col-md-4 mt-3">
                                    <label for="sbconta">
                                        Subtipo de Conta
                                    </label>
                                    <select id="sbconta" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" aria-label="Default select example" name="tp_sub_tp_conta">
                                        <option value="{{ $contaBancaria->stpcontaid }}">
                                            {{ $contaBancaria->stpcontadesc }}
                                        </option>
                                        @foreach ($tp_sub_tp_contas as $tp_sub_tp_conta)
                                            <option value="{{ $tp_sub_tp_conta->id }}">
                                                {{ $tp_sub_tp_conta->descricao }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        </div>
        <br>
        <div>
            <a class="btn btn-danger col-md-3 col-2 mt-4 offset-md-1"
                href="/gerenciar-dados-bancarios/{{ $funcionario->id }}" role="button">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-3" id="sucesso">
                Confirmar
            </button>
        </div>
    </form>

    <!--JQUERY-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>-->
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('#idbanco').select2({
                theme: 'bootstrap-5',
               // Initialize the select2 with disabled state
            });
            $('#idagencia').select2({
                theme: 'bootstrap-5',
                disabled: true, // Initialize the select2 with disabled state
            });
            $('#idbanco').change(function(e) {
                var idbanco = $(this).val();
                e.preventDefault();
                $('#idagencia').removeAttr('disabled');

                $.ajax({
                    type: "GET",
                    url: "/recebe-agencias/" + idbanco,
                    dataType: "json",
                    success: function(response) {
                        $('#idagencia').empty();

                        // Função para adicionar zeros à esquerda
                        function padWithZeros(number, length) {
                            return number.toString().padStart(length, '0');
                        }

                        $.each(response, function(index, item) {
                            var agenciaFormatted = padWithZeros(item.agencia,
                                4); // Formatando agência
                            $('#idagencia').append("<option value='" + item.id + "'>" +
                                agenciaFormatted + " - " + item.desc_agen +
                                "</option>");
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
