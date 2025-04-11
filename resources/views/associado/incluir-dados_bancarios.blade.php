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
                <form class="form-horizontal mt-4" method='POST'
                    action="/incluir-dados_bancarios-associado/{{ $associado[0]->ida }}">
                    @csrf

                    <div class="container-fluid">
                        <div class="row d-flex justify-content-around">

                            <div class="col-md-2 col-sm-12">
                                <label for="2">Valor</label>
                                <input type="text" class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                    name="valor" placeholder="R$ 0,00" maxlength="11"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                    value="">
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <label for="4">Data de Vencimento</label>
                                <input type="text" class="form-control" name="dt_vencimento"
                                    style="border: 1px solid #999999; padding: 5px;" id="2" maxlength="2"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                    value="">
                            </div>
                        </div>
                        <br>

                        <div class="card-header">
                            <center>Tesouraria</center>
                        </div>
                        <br>
                        <div class="d-flex justify-content-around">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tesouraria" id="dinheiro"
                                    value="dinheiro">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Dinheiro
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tesouraria" id="cheque"
                                    value="cheque" required>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Cheque
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tesouraria" id="ct_de_debito"
                                    value="ct_de_debito" required>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Cartão de Débito
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tesouraria" id="ct_de_credito"
                                    value="ct_de_credito" required>
                                <label class="form-check-label" for="flexRadioDefault1">
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
                                <input class="form-check-input" type="radio" name="periodo_para_contribuicao"
                                    id="mensal" value="mensal" required>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Mensal
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periodo_para_contribuicao"
                                    id="trimestral" value="trimestral" required>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Trimestral
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periodo_para_contribuicao" id="semestral"
                                    value="semestral" required>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Semestral
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periodo_para_contribuicao" id="anual"
                                    value="anual" required>
                                <label class="form-check-label" for="flexRadioDefault1">
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
                                <input class="form-check-input id_banco" type="radio" name="banco"
                                    id="banco_do_brasil" data-bs-target="#collapseExample" value="1" required>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Banco do Brasil
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input id_banco" type="radio" name="banco" id="brb"
                                    data-bs-target="#collapseExample" value="70" required>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    BRB
                                </label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div id="collapseExample">
                        <div class="row d-flex justify-content-around">
                            <div class="col-md-3 col-sm-12">
                                <label for="agencia">
                                    Agência
                                </label>

                                <select id="idagencia" style="border: 1px solid #999999; padding: 5px;"
                                    class="form-select" aria-label="Default select example" name="tp_banco_ag">
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <label for="2">Conta Corrente</label>
                                <input type="text" class="form-control" name="conta_corrente"
                                    style="border: 1px solid #999999; padding: 5px;" maxlength="6">
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    </div>



    <div class="row d-flex justify-content-evenly">
        <div class="col-2"><a href="/gerenciar-associado" class="btn btn-danger" style="width:150%">Cancelar</a></div>
        <div class="col-2"><button type="submit" class="btn btn-primary" style="width: 150%;">Confirmar</button></div>
    </div>
    </form>


    <!--JQUERY-->
    
    


    <script>
        $(document).ready(function() {
            $('#idagencia').select2({
                theme: 'bootstrap-5',
                // Initialize the select2 with disabled state
            });

            $(".id_banco").change(function (e) {
                e.preventDefault();
                var banco = $(this).val();


                $.ajax({
                    type: "GET",
                    url: "/recebe-agencias/" + banco,
                    dataType: "JSON",
                    success: function (response) {
                        $('#idagencia').empty();
                        $.each(response, function (index, item) {
                            $('#idagencia').append("<option value =" + item.id + "> " +
                            item.agencia + " - " + item.desc_agen + "</option>");

                        });

                    }
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
        });
    </script>
@endsection
