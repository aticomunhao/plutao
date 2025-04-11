@extends('layouts.app')

@section('content')
<br>
<div class="container">
    <legend style="color:rgb(16, 19, 241); font-size:15px;">Dados Bancários Associado</legend>
    <div class="border border-primary" style="border-radius: 5px;" <div class="card">
        <div class="card-header">
            Dados Pessoais
        </div>
        <div class="card-body">
            <form class="form-horizontal mt-4" method='POST' action="/atualizar-dados-bancarios-associado/{{ $dados_bancarios_associado[0]->ida}}/{{ $dados_bancarios_associado[0]->idt}}/{{ $dados_bancarios_associado[0]->idb}}/{{ $dados_bancarios_associado[0]->idc}}">
                @csrf

                <div class="container-fluid">
                    <div class="row d-flex justify-content-around">

                        <div class="col-md-2 col-sm-12">
                            <label for="2">Valor</label>
                            <input type="text" class="form-control" name="valor" style="border: 1px solid #999999; padding: 5px;" placeholder="R$ 0,00" maxlength="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="{{ $dados_bancarios_associado[0]->valor}}">
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label for="4">Data de Vencimento</label>
                            <input type="text" class="form-control" name="dt_vencimento" style="border: 1px solid #999999; padding: 5px;" id="2" maxlength="2" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="{{ $dados_bancarios_associado[0]->dt_vencimento}}">
                        </div>
                    </div>
                    <br>
                    <div class="card-header">
                        <center>Tesouraria</center>
                    </div>
                    <br>
                    <div class="d-flex justify-content-around">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="dinheiro" value="dinheiro" {{ $dados_bancarios_associado[0]->dinheiro ? 'checked' : '' }}>
                            <label class="form-check-label" for="dinheiro">
                                Dinheiro
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="cheque" value="cheque" {{ $dados_bancarios_associado[0]->cheque ? 'checked' : '' }}>
                            <label class="form-check-label" for="cheque">
                                Cheque
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="ct_de_debito" value="ct_de_debito" {{ $dados_bancarios_associado[0]->ct_de_debito ? 'checked' : '' }}>
                            <label class="form-check-label" for="ct_de_debito">
                                Cartão de Débito
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="ct_de_credito" value="ct_de_credito" {{ $dados_bancarios_associado[0]->ct_de_credito ? 'checked' : '' }}>
                            <label class="form-check-label" for="ct_de_credito">
                                Cartão de Crédito
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="card-header">
                        <center>Boleto</center>
                    </div>
                    <br>
                    <div class="d-flex justify-content-around">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="mensal" value="mensal" {{ $dados_bancarios_associado[0]->mensal ? 'checked' : '' }}>
                            <label class="form-check-label" for="mensal">
                                Mensal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="trimestral" value="trimestral" {{ $dados_bancarios_associado[0]->trimestral ? 'checked' : '' }}>
                            <label class="form-check-label" for="trimestral">
                                Trimestral
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="semestral" value="semestral" {{ $dados_bancarios_associado[0]->semestral ? 'checked' : '' }}>
                            <label class="form-check-label" for="semestral">
                                Semestral
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="anual" value="anual" {{ $dados_bancarios_associado[0]->anual ? 'checked' : '' }}>
                            <label class="form-check-label" for="anual">
                                Anual
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="card-header">
                        <center>Autorização em Débito em conta</center>
                    </div>
                    <br>
                    <div class="d-flex justify-content-around">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="banco_do_brasil" value="banco_do_brasil" {{ $dados_bancarios_associado[0]->banco_do_brasil ? 'checked' : '' }}>
                            <label class="form-check-label" for="banco_do_brasil">
                                Banco do Brasil
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tesouraria" id="brb" value="brb" {{ $dados_bancarios_associado[0]->brb ? 'checked' : '' }}>
                            <label class="form-check-label" for="brb">
                                BRB
                            </label>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <div id="collapseExample">
                    <div class="row d-flex justify-content-around">
                        <div class="col-md-4 col-sm-12">
                            <label for="Banco">
                                Banco
                            </label>
                            <select id="idbanco" style="border: 1px solid #999999; padding: 5px;" class="form-select" aria-label="Default select example" name="desc_banco" required>
                                <option value="{{ $tpbanco->id_banco }}">{{$tpbanco->banco}}</option>
                                @foreach ($desc_bancos as $desc_banco)
                                <option value="{{ $desc_banco->id_db }}">
                                    {{ str_pad($desc_banco->id_db, 3, '0', STR_PAD_LEFT) }} -
                                    {{ $desc_banco->nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="agencia">
                                Agência
                            </label>
                            <select id="idagencia" style="border: 1px solid #999999; padding: 5px;" class="form-select" aria-label="Default select example" name="tp_banco_ag" required>
                                <option value="{{ $tpagencia->id_agencia }}">{{ $tpagencia->descricao }}</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label for="2">Conta Corrente</label>
                            <input type="text" class="form-control" name="conta_corrente" style="border: 1px solid #999999;padding: 5px;" maxlength="6" value="{{ $dados_bancarios_associado[0]->nr_cont_corrente}}">
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!--JQUERY-->
    
    


    <script>
        $(document).ready(function() {
            $('#idagencia').select2({
                theme: 'bootstrap-5',
                // Initialize the select2 with disabled state
            });


            $('#idbanco').change(function(e) {
                var idbanco = $(this).val();
                e.preventDefault();
                $('#idagencia').prop('disabled', false); // Enable the #idagencia dropdown

                $.ajax({
                    type: "GET",
                    url: "/recebe-agencias/" + idbanco,
                    dataType: "json",
                    success: function(response) {
                        $('#idagencia').empty();
                        $.each(response, function(index, item) {
                            $('#idagencia').append("<option value =" + item.id + "> " +
                                item.agencia + " - " + item.desc_agen + "</option>");
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
        $(document).ready(function() {
            $('#collapseExample').hide();
            $('input[type="radio"]').click(function() {
                if ($(this).attr('id') === 'banco_do_brasil' || $(this).attr('id') === 'brb') {
                    $('#collapseExample').show();
                } else {
                    $('#collapseExample').hide();
                }
            });
        });
    </script>

    <div class="row d-flex justify-content-evenly">
        <div class="col-2"><a href="/gerenciar-associado" class="btn btn-danger" style="width:150%">Cancelar</a></div>
        <div class="col-2"><button type="submit" class="btn btn-primary" style="width: 150%;">Confirmar</button></div>
    </div>
    </form>


    @endsection