@extends('layouts.app')
@section('head')
    <title>Gerenciar Hierarquia</title>
@endsection
@section('content')
    
    <form id="gerenciarHierarquiaForm" action="/gerenciar-hierarquia" method="get">
        @csrf
        <div class="container-fluid"> {{-- Container completo da página  --}}
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Gerenciar Hierarquia
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            @csrf
                            <div class="row">
                                <div class="form-group  col-xl-2 col-md-5 ">
                                    <label for="1">Nível</label>
                                    <select id="idnivel" class="form-select"
                                        style="border: 1px solid #999999; padding: 5px;" name="nivel">
                                        <option></option>
                                        @foreach ($nivel as $niveis)
                                            <option value="{{ $niveis->id_nivel }}"
                                                {{ old('nivel', $nm_nivel) == $niveis->id_nivel ? 'selected' : '' }}>
                                                {{ $niveis->nome_nivel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-4 ">
                                    <label for="1">Setor</label>
                                    <select id="idsetor" class="form-select"
                                        style="border: 1px solid #999999; padding: 5px;" name="nome_setor" disabled>
                                        <option></option>
                                        @foreach ($setor as $setor)
                                            <option value="{{ $setor->id_setor }}"
                                                {{ old('nome_setor', $nome_setor) == $setor->id_setor ? 'selected' : '' }}>
                                                {{ $setor->nome_setor }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col" style="padding-top:5px">
                                    <a href="/gerenciar-hierarquia" type="button" class="btn btn-light btn-sm"
                                        style="box-shadow: 1px 2px 5px #000000; margin-top: 16px; margin-left: 2px; font-size: 1rem"
                                        value="">Limpar</a>
                                    <input type="submit" value="Pesquisar" class="btn btn-light btn-sm"
                                        style="box-shadow: 1px 2px 5px #000000; margin-top: 16px; font-size: 1rem">
                                </div>
                            </div>

    </form>
    <hr>
    <div class="table">
        <table class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
            <thead style="text-align: center;">
                <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">
                    <th class="col-1">
                        <input type="checkbox" id="masterCheckBox" name="checkbox">
                    </th>
                    <th class="col-4">Nome</th>
                    <th class="col-1">Sigla</th>
                    <th class="col-1">Data Inicio</th>
                    <th class="col-1">Status</th>
                    <th class="col-1">Substituto</th>
                    <th class="col-4">Setor Responsável</th>
                </tr>
            </thead>
            <tbody style="font-size: 15px; color:#000000;">
                @foreach ($lista as $index => $listas)
                    <tr>
                        <td scope="">
                            <center>
                                <input type="checkbox" class="checkBox" name="checkboxes[]" value="{{ $listas->ids }}"
                                    {{ $index === 0 ? 'disabled' : '' }} {{ $listas->st_pai ? 'checked' : '' }}>
                            </center>
                        </td>
                        <td scope="">
                            <center>{{ $listas->nome_setor }}</center>
                        </td>
                        <td scope="">
                            <center>{{ $listas->sigla }}</center>
                        </td>
                        <td scope="">
                            <center>{{ $listas->dt_inicio }}</center>
                        </td>
                        <td scope="">
                            <center>{{ $listas->status }}</center>
                        </td>
                        <td scope="">
                            <center>{{ $listas->nome_substituto }}</center>
                        </td>
                        <td scope="">
                            <center>{{ $listas->st_pai }}</center>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            const masterCheckBox = $('#masterCheckBox');
            const checkBoxes = $('.checkBox');

            masterCheckBox.change(function() {
                checkBoxes.prop('checked', masterCheckBox.prop('checked')).trigger('change');
            });

            checkBoxes.change(function() {
                masterCheckBox.prop('checked', checkBoxes.length === checkBoxes.filter(':checked').length);
                changeBackground($(this));
            });

            function changeBackground(input) {
                const tableRow = input.closest('tr');
                if (input.prop('checked')) {
                    tableRow.css('background', '#aaa');
                } else {
                    tableRow.css('background', '');
                }
            }

            $('#idsetor').change(function() {
                var selectedSetor = $(this).val();
                console.log("Valor selecionado no campo Setor:", selectedSetor);
                // Se precisar, faça algo com o valor selecionado aqui
            });
            $('#idnivel option:nth-child(4)').hide();
            $('#idnivel').change(function() {
                var nivel_id = $(this).val();

                $.ajax({
                    url: '/obter-setores/' + nivel_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#idsetor').removeAttr('disabled');
                        $('#idsetor').empty();
                        $.each(data, function(indexInArray, item) {
                            $('#idsetor').append('<option value="' + item.id + '">' +
                                item.nome + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Ao submeter o formulário
            $('#updateForm').submit(function(event) {
                console.log('Formulário enviado!');

                const checkboxes = $('.checkBox:checked').map(function() {
                    return $(this).val();
                }).get();

                console.log('Valores dos checkboxes:', checkboxes);

                $('#hiddenCheckboxes').val(checkboxes.join(','));
            });
        });
    </script>
    <form id="updateForm" action="/atualizar-hierarquia" method="post">
        @csrf

        <a href="/gerenciar-setor" type="button" value=""
            class="btn btn-danger col-md-3 col-2 mt-5 offset-md-1">Cancelar</a>
        <input type="hidden" name="checkboxes" id="hiddenCheckboxes">
        <input type="submit" value="Confirmar" class="btn btn-primary col-md-3 col-1 mt-5 offset-md-3">

    </form>
@endsection
