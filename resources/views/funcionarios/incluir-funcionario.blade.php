@extends('layouts.app')

@section('head')
    <title>Cadastrar Funcionário</title>
@endsection

@section('content')
    <form class="form-horizontal" method="post" action="/incluir-funcionario">
        @csrf
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089;">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Incluir Dados Pessoais
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">Nome Completo
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="45"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="idnome_completo" name="nome_completo" value="{{ old('nome_completo') }}"
                                        required="required">
                                </div>
                                <div class="col-md-2 col-sm-12">Nome resumido
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="20"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="" name="nome_resumido" value="{{ old('nome_resumido') }}"
                                        required="required">
                                </div>
                                <div class="col-md-2 col-sm-12">CPF
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="11" placeholder="888.888.888-88"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="8" name="cpf" required="required">
                                </div>
                                <div class="col-md-2 col-sm-12">Data de Nascimento
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="{{ old('dt_nascimento') }}" id="3"
                                        name="dt_nascimento" required="required">
                                </div>
                                <div class="col-md-2 col-sm-12">Sexo
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="4" name="sex" required="required">
                                        <option value=""></option>
                                        @foreach ($sexo as $sexos)
                                            <option @if (old('sex') == $sexos->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $sexos->id }}">{{ $sexos->tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-5 col-sm-12">Nacionalidade
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="nacionalidade" name="pais" value="{{ old('pais') }}" required="required">
                                        <option value=""></option>
                                        @foreach ($nac as $nacs)
                                            <option @if (old('pais') == $nacs->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $nacs->id }}">{{ $nacs->local }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12">UF de Nascimento
                                    <select select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        data-placeholder="Choose one thing" name="uf_nat" required="required"
                                        id="uf1">
                                        <option value=""></option>
                                        @foreach ($tp_uf as $tp_ufs)
                                            <option @if (old('uf_nat') == $tp_ufs->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $tp_ufs->id }}">{{ $tp_ufs->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 col-sm-12">Naturalidade
                                    <select class="form-select select2" id="cidade1" name="natura"
                                        value="{{ old('natura') }}" required="required" disabled="disabled">
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">Número de Identidade
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="20" type="number" id="11" name="identidade"
                                        value="{{ old('identidade') }}" required="required">
                                </div>
                                <div class="col-md-2 col-sm-12">UF da Identidade
                                    <select class="js-example-responsive form-select"
                                        style="border: 1px solid #999999; padding: 5px;" id="uf-idt" name="uf_idt"
                                        required="required">
                                        <option value=""></option>
                                        @foreach ($tp_uf as $tp_ufs)
                                            <option @if (old('uf_idt') == $tp_ufs->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $tp_ufs->id }}">{{ $tp_ufs->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12">Órgão expedidor
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        required="required" id="12" name="orgexp" required="required">
                                        <option value=""></option>
                                        <@foreach ($org_exp as $org_exps)
                                            <option @if (old('orgexp') == $org_exps->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $org_exps->id }}">{{ $org_exps->sigla }}
                                            </option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12">Data de emissão
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" id="13" name="dt_idt" value="{{ old('dt_idt') }}"
                                        required="required">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">E-mail
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="45" type="email" id="31" name="email"
                                        value="{{ old('email') }}">
                                </div>
                                <div class="col-md-2 col-sm-12">DDD
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="16" name="ddd" required="required">
                                        <option value=""></option>
                                        <@foreach ($ddd as $ddds)
                                            <option @if (old('ddd') == $ddds->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $ddds->id }}">{{ $ddds->descricao }}
                                            </option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-12">Telefone/Celular
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        max="999999999" type="number"
                                        oninput="if (this.value.length > 9) this.value = this.value.slice(0, 9);"
                                        placeholder="Ex.: 99999-9999" value="{{ old('celular') }}" id="22"
                                        name="celular">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089;">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Incluir Dados do Funcionário
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">Setor Alocado
                                    <select class="form-select select2" style="border: 1px solid #999999; padding: 5px;"
                                        required id="setorid" name="setor">
                                        <option value=""></option>
                                        @foreach ($setor as $setores)
                                            <option @if (old('setor') == $setores->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $setores->id }}">{{ $setores->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">CTPS
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton1" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="8" type="number" id="23" name="ctps"
                                        value="{{ old('ctps') }}" required="required">
                                </div>
                                <div class="col-md-3 col-sm-12">Série
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton2" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="5" type="number" id="26" name="serie_ctps"
                                        value="{{ old('serie_ctps') }}" required="required">
                                </div>
                                <div class="col-md-2 col-sm-12">UF
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton3" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <select class="js-example-responsive form-select"
                                        style="border: 1px solid #999999; padding: 5px;" required="required"
                                        id="uf_ctps" name="uf_ctps" required="required">
                                        <option value=""></option>
                                        @foreach ($tp_uf as $tp_ufs)
                                            <option @if (old('uf_ctps') == $tp_ufs->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $tp_ufs->id }}">{{ $tp_ufs->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12">Data de Emissão
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton4" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" id="24" name="dt_ctps" value="{{ old('dt_ctps') }}"
                                        required="required">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">Título Eleitor
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton5" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="12" type="number" id="17" name="titele"
                                        value="{{ old('titele') }}">
                                </div>
                                <div class="col-md-3 col-sm-12">Zona
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton6" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="5" type="number" id="18" name="zona"
                                        value="{{ old('zona') }}">
                                </div>
                                <div class="col-md-3 col-sm-12">Seção
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton7" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="5" type="number" id="19" name="secao"
                                        value="{{ old('secao') }}">
                                </div>
                                <div class="col-md-2 col-sm-12">Data emissão
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton8" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" id="20" name="dt_titulo" value="{{ old('dt_titulo') }}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">Tipo Programa
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="9" name="tp_programa" required="required">
                                        <option value=""></option>
                                        @foreach ($programa as $programas)
                                            <option @if (old('tp_programa') == $programas->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $programas->id }}">{{ $programas->programa }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-12">Número do PIS/NIS/PASEP
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="11" type="number" id="10" name="nr_programa"
                                        value="{{ old('nr_programa') }}" required="required">
                                </div>
                                <div class="col-md-3 col-sm-12">Cor pele
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="14" name="cor" required="required">
                                        <option value=""></option>
                                        <@foreach ($cor as $cors)
                                            <option @if (old('cor') == $cors->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $cors->id }}">{{ $cors->nome_cor }}
                                            </option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">Mãe
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton9" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="45"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="29" name="nome_mae" value="{{ old('nome_mae') }}"
                                        required="required">
                                </div>
                                <div class="col-md-6 col-sm-12">Pai
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton10" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <i class="bi bi-question-circle"></i>
                                    </a>
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="45"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="30" name="nome_pai" value="{{ old('nome_pai') }}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-5 col-sm-12">Número de Reservista
                                    <input class="form-control" maxlength="12" type="number" id="28"
                                        style="border: 1px solid #999999; padding: 5px;" name="reservista"
                                        value="{{ old('reservista') }}">
                                </div>
                                <div class="col-md-2 col-sm-12">Cat CNH
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="32" name="cnh">
                                        <option value=""></option>
                                        @foreach ($cnh as $cnhs)
                                            <option @if (old('cnh') == $cnhs->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $cnhs->id }}">{{ $cnhs->nome_cat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12">Tipo sanguíneo
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="15" name="tps">
                                        <option value=""></option>
                                        <@foreach ($sangue as $sangues)
                                            <option @if (old('tps') == $sangues->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $sangues->id }}">{{ $sangues->nome_sangue }}
                                            </option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12">Fator RH
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="16" name="frh">
                                        <option value=""></option>
                                        <@foreach ($fator as $fators)
                                            <option @if (old('frh') == $fators->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $fators->id }}">{{ $fators->nome_fator }}
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
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089;">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Incluir Dados Residenciais
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-4 col-sm-12">CEP
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="8" type="number"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="cep" name="cep" value="{{ old('cep') }}">
                                </div>
                                <div class="col-md-4 col-sm-12">UF
                                    <br>
                                    <select class="js-example-responsive form-select"
                                        style="border: 1px solid #999999; padding: 5px;" id="uf2" name="uf_end">
                                        <option value=""></option>
                                        @foreach ($tp_uf as $tp_ufs)
                                            <option @if (old('uf_end') == $tp_ufs->id) {{ 'selected="selected"' }} @endif
                                                value="{{ $tp_ufs->id }}">{{ $tp_ufs->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-12">Cidade
                                    <br>
                                    <select class="js-example-responsive form-select"
                                        style="border: 1px solid #999999; padding: 5px;" id="cidade2" name="cidade"
                                        value="{{ old('cidade') }}" disabled>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">Logradouro
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="45" type="text" id="logradouro" name="logradouro"
                                        value="{{ old('logradouro') }}">
                                </div>
                                <div class="col-md-3 col-sm-12">Número
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="10" type="text" id="35" name="numero"
                                        value="{{ old('numero') }}">
                                </div>
                                <div class="col-md-3 col-sm-12">Complemento
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;" maxlength="45"
                                        class="form-control" id="complemento" name="comple"
                                        value="{{ old('comple') }}">
                                </div>
                                <div class="col-md-3 col-sm-12">Bairro:
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;" maxlength="45"
                                        class="form-control" id="bairro" name="bairro" value="{{ old('bairro') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="botões">
            <a href="/gerenciar-funcionario" type="button" value=""
                class="btn btn-danger col-md-3 col-2 mt-4 offset-md-2">Cancelar</a>
            <input type="submit" value="Confirmar" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-2">
        </div>
        <br>
    </form>

    <script>
        $(document).ready(function() {
            $('#cidade1,  #setorid').select2({
                theme: 'bootstrap-5',
                width: '100%',
            });

            function populateCities(selectElement, stateValue) {
                $.ajax({
                    type: "get",
                    url: "/retorna-cidade-dados-residenciais/" + stateValue,
                    dataType: "json",
                    success: function(response) {
                        selectElement.empty();
                        $.each(response, function(indexInArray, item) {
                            selectElement.append('<option value="' + item.id_cidade + '">' +
                                item.descricao + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred:", error);
                    }
                });
            }

            $('#uf1').change(function(e) {
                var stateValue = $(this).val();
                $('#cidade1').removeAttr('disabled');
                populateCities($('#cidade1'), stateValue);
            });

            $('#uf2').change(function(e) {
                var stateValue = $(this).val();
                $('#cidade2').removeAttr('disabled');
                populateCities($('#cidade2'), stateValue);
            });

            $('#idlimpar').click(function(e) {
                $('#idnome_completo').val("");
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#cep').on('input', function() {
                let cep = $(this).val().replace(/\D/g, '');

                if (cep.length === 8) {
                    let estados = @JSON($tp_uf);

                    $.ajax({
                        type: "GET",
                        url: 'https://viacep.com.br/ws/' + cep + '/json/',
                        dataType: "json",
                        success: function(response) {
                            console.log(response);

                            // Preenchendo os campos automaticamente
                            $('#logradouro').val(response.logradouro);
                            $('#bairro').val(response.bairro);
                            $('#complemento').val(response.complemento);

                            // Encontrando o estado correspondente
                            let estadoEncontrado = estados.find(estado => estado.sigla ===
                                response.uf);

                            if (estadoEncontrado) {
                                $('#uf2').val(estadoEncontrado.id).trigger('change');

                                // Buscar cidades automaticamente e selecionar pelo nome
                                populateCities($('#cidade2'), estadoEncontrado.id, response
                                    .localidade);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Erro ao buscar o CEP:", error);
                        }
                    });
                }
            });

            function populateCities(selectElement, uf, cidadeNome) {
                $.ajax({
                    type: "GET",
                    url: "/retorna-cidades/" + uf,
                    dataType: "JSON",
                    success: function(response) {
                        selectElement.empty();
                        selectElement.removeAttr('disabled');

                        let cidadeSelecionada = null;

                        $.each(response, function(indexInArray, item) {
                            selectElement.append('<option value="' + item.id_cidade + '">' +
                                item.descricao + '</option>');

                            // Verifica se o nome da cidade retornado pelo ViaCEP é igual ao da lista
                            if (item.descricao.toLowerCase() === cidadeNome.toLowerCase()) {
                                cidadeSelecionada = item.id_cidade;
                            }
                        });

                        // Se encontramos a cidade pelo nome, selecionamos ela
                        if (cidadeSelecionada) {
                            selectElement.val(cidadeSelecionada).trigger('change');
                        }
                    }
                });
            }
        });
    </script>


    <script>
        $(document).ready(function() {

            $('#uf1').change(function(e) {

                var uf = $(this).val();
                populateCities($('#cidade1'), uf);
            });

            $('#iduf').change(function(e) {
                var uf = $(this).val();
                $('#idcidade').removeAttr('disabled');
                populateCities($('#idcidade'), uf);
            });

            $('#idlimpar').click(function(e) {
                $('#idnome_completo').val("");
            });

            $('#idcidade').select2({
                theme: 'bootstrap-5',
                width: '100%',
            });
            $('#cidade1').select2({
                theme: 'bootstrap-5',
                width: '100%',
            });
        });
    </script>

    <style>
        /* Estilo para centralizar o conteúdo do botão */
        .btn-circle {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1px solid #999999;
            /* Espaçamento entre o círculo e o texto */
        }
    </style>
@endsection
