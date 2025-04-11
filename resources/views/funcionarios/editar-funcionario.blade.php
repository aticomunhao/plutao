@extends('layouts.app')
@section('head')
    <title>Editar Cadastro de Funcionário</title>
@endsection
@section('content')
    <!--{{ route('atualizar.funcionario', ['idp', $pessoa[0]->idp]) }}-->
    <form method='POST' action="/atualizar-funcionario/{{ $pessoa[0]->idp }}">
        @csrf
        <div class="container-fluid">
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Editar Dados Pessoais
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">Nome Completo
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="nome_completo" maxlength="45"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="2" value="{{ $pessoa[0]->nome_completo }}" required="required">
                                </div>
                                <div class="col-md-2 col-sm-12">Nome resumido
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="20"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="" name="nome_resumido" value="{{ $pessoa[0]->nome_resumido }}"
                                        required="required">
                                </div>
                                <div class="col-md-2 col-sm-12">CPF
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="cpf" maxlength="11"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="8" value="{{ $pessoa[0]->cpf }}" required="required">
                                    <div class="invalid-feedback">
                                        Por favor, informe um CPF válido.
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">Data de Nascimento
                                    <input type="date" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="dt_nascimento" id="3"
                                        value="{{ $pessoa[0]->dt_nascimento }}" required="required">
                                    <div class="invalid-feedback">
                                        Por favor, selecione a Data de Nascimento.
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">Sexo
                                    <select id="4" class="form-select"
                                        style="border: 1px solid #999999; padding: 5px;" name="sexo" type="text"
                                        required="required">
                                        <option value="{{ $pessoa[0]->id_sexo }}">{{ $pessoa[0]->nome_sexo }}</option>
                                        @foreach ($tpsexo as $tpsexos)
                                            <option value="{{ $tpsexos->id }}">
                                                {{ $tpsexos->tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, selecione um Campo
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-5 col-sm-12">Nacionalidade
                                    <select id="5" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" name="pais" required="required">
                                        <option value="{{ $pessoa[0]->nacionalidade }}">
                                            {{ $pessoa[0]->nome_nacionalidade }}</option>
                                        @foreach ($tpnacionalidade as $tpnacionalidades)
                                            <option value="{{ $tpnacionalidades->id }}">{{ $tpnacionalidades->local }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, selecione uma Nacionalidade.
                                    </div>
                                    <br>
                                </div>
                                <div class="col-md-2 col-sm-12">UF de Nascimento
                                    <select select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        data-placeholder="Choose one thing" name="uf_nat" required="required"
                                        id="uf1">
                                        <option value="{{ $pessoa[0]->uf_naturalidade }}">
                                            {{ $pessoa[0]->sigla_naturalidade }}
                                        </option>
                                        @foreach ($tp_uf as $tp_ufs)
                                            <option value="{{ $tp_ufs->id }}">
                                                {{ $tp_ufs->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 col-sm-12">Naturalidade
                                    <select class="form-select" id="cidade1" name="natura" required="required">
                                        <option value="{{ $pessoa[0]->naturalidade }}">
                                            {{ $pessoa[0]->descricao_cidade }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">Número de Identidade
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="identidade" maxlength="20"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="10" value="{{ $pessoa[0]->identidade }}" required="required">
                                    <div class="invalid-feedback">
                                        Por favor, informe um RG válido.
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">UF da Identidade
                                    <select id="24" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" name="uf_idt" required="required">
                                        <option value="{{ $identidade[0]->uf_idt }}">
                                            {{ $identidade[0]->sigla_identidade }}
                                        </option>
                                        @foreach ($tp_ufi as $tp_ufis)
                                            <option value="{{ $tp_ufis->id }}">
                                                {{ $tp_ufis->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, selecione um UF válido.
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">Órgão Expedidor
                                    <select id="9" style="border: 1px solid #999999; padding: 5px;"
                                        name="orgexp" class="form-select" required="required">
                                        <option value="{{ $pessoa[0]->id_orgao_expedidor }}">
                                            {{ $pessoa[0]->sigla_orgao_expedidor }}
                                        </option>
                                        @foreach ($tporg_exp as $tporg_exps)
                                            <option value="{{ $tporg_exps->id }}">
                                                {{ $tporg_exps->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12">Data de Emissão
                                    <input type="date" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="dt_idt" id="12"
                                        value="{{ $pessoa[0]->dt_emissao_identidade }}" required="required">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">Email
                                    <input type="email" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="email" maxlength="50" id="28"
                                        value="{{ $pessoa[0]->email }}">
                                </div>
                                <div class="col-md-2 col-sm-12">DDD
                                    <select id="19" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" name="ddd">
                                        <option value="{{ $pessoa[0]->ddd }}">
                                            {{ $pessoa[0]->numero_ddd }}
                                        </option>
                                        @foreach ($tpddd as $tpddds)
                                            <option value="{{ $tpddds->id }}">
                                                {{ $tpddds->descricao }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, selecione um DDD válido.
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">Telefone/Celular
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="celular" maxlength="9"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="20" value="{{ $pessoa[0]->celular }}">
                                    <div class="invalid-feedback">
                                        Por favor, informe o Número de Celular.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Editar Dados do Funcionário
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">Setor Alocado
                                    <select id="setorid" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" name="setor">
                                        <option value="{{ $acharSetor->id_setor }}" required="required">
                                            {{ $acharSetor->nome_setor }}
                                        </option>
                                        @foreach ($tpsetor as $tpsetores)
                                            <option value="{{ $tpsetores->id }}">
                                                {{ $tpsetores->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, selecione uma Cat CNH válida.
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">CTPS
                                    <button type="button" class="btn-circle" id="popoverButton1"
                                        data-bs-trigger="focus" data-bs-toggle="popover" title="Popover title"
                                        data-bs-content="And here's some amazing content. It's very engaging. Right?">?</button>
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="ctps" maxlength="6"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="21" value="{{ $funcionario[0]->ctps }}">
                                    <div class="invalid-feedback">
                                        Por favor, informe um CTPS Nr válido.
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">Série
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton2" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <span class="btn-circle">
                                            ?
                                        </span>
                                    </a>
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="serie_ctps" maxlength="4"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="23" value="{{ $funcionario[0]->serie_ctps }}">
                                    <div class="invalid-feedback">
                                        Por favor, informe um Nr Série válido.
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">UF
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton3" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <span class="btn-circle">
                                            ?
                                        </span>
                                    </a>
                                    <select id="24" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" name="uf_ctps">
                                        <option value="{{ $funcionario[0]->uf_ctps }}">
                                            {{ $funcionario[0]->sigla_ctps }}
                                        </option>
                                        @foreach ($tp_uff as $tp_uffs)
                                            <option value="{{ $tp_uffs->id }}">
                                                {{ $tp_uffs->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, selecione um UF válido.
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">Data de Emissão
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton4" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <span class="btn-circle">
                                            ?
                                        </span>
                                    </a>
                                    <input type="date" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="dt_ctps" id="22"
                                        value="{{ $funcionario[0]->emissao_ctps }}">
                                    <div class="invalid-feedback">
                                        Por favor, selecione a Data de Emissão.
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">Titulo Eleitor
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton5" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <span class="btn-circle">
                                            ?
                                        </span>
                                    </a>
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="titele" maxlength="12"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="15" value="{{ $funcionario[0]->titulo_eleitor }}">
                                    <div class="invalid-feedback">
                                        Por favor, informe um Titulo eleitor Nr válido.
                                    </div>
                                    <br>
                                </div>
                                <div class="col-md-3 col-sm-12">Zona
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton6" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <span class="btn-circle">
                                            ?
                                        </span>
                                    </a>
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="zona" maxlength="3"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="16" value="{{ $funcionario[0]->zona_titulo }}">
                                    <div class="invalid-feedback">
                                        Por favor, informe um Titulo eleitor Nr válido.
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">Seção
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton7" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <span class="btn-circle">
                                            ?
                                        </span>
                                    </a>
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="secao" maxlength="4"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="17" value="{{ $funcionario[0]->secao_titulo }}">
                                    <div class="invalid-feedback">
                                        Por favor, informe uma Seção válida.
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">Data de Emissão
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton8" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <span class="btn-circle">
                                            ?
                                        </span>
                                    </a>
                                    <input type="date" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="dt_titulo" id="18"
                                        value="{{ $funcionario[0]->dt_titulo }}">
                                    <div class="invalid-feedback">
                                        Por favor, selecione a Data de Emissão.
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">Tipo Programa
                                    <select id="9" style="border: 1px solid #999999; padding: 5px;"
                                        name="tp_programa" class="form-select" required="required">
                                        <option value="{{ $funcionario[0]->tp_programa }}">
                                            {{ $funcionario[0]->nome_programa }}
                                        </option>
                                        @foreach ($tpprograma as $tpprogramas)
                                            <option value="{{ $tpprogramas->id }}">
                                                {{ $tpprogramas->programa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, informe um PIS/NIS/PASEP válido.
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">Número do PIS/NIS/PASEP
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="11" type="numeric" id="10" name="nr_programa"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="15" value="{{ $funcionario[0]->nr_programa }}" required="required">
                                </div>
                                <div class="col-md-3 col-sm-12">Cor Pele
                                    <select id="13" style="border: 1px solid #999999; padding: 5px;"
                                        name="cor" class="form-select" type="bigint">
                                        <option value="{{ $funcionario[0]->tp_cor }}">
                                            {{ $funcionario[0]->nome_cor }}
                                        </option>
                                        @foreach ($tppele as $tppeles)
                                            <option value="{{ $tppeles->id }}">
                                                {{ $tppeles->nome_cor }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor, selecione a Cor da Pele.
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">Mãe
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton9" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <span class="btn-circle">
                                            ?
                                        </span>
                                    </a>
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="nome_mae" maxlength="45"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="26" value="{{ $funcionario[0]->nome_mae }}">
                                    <div class="invalid-feedback">
                                        Por favor, informe o Nome do Ascendente Primário>
                                    </div>
                                    <br>
                                </div>
                                <div class="col-md-6 col-sm-12">Pai
                                    <a tabindex="0" class="btn btn-sm" id="popoverButton10" role="button"
                                        data-bs-toggle="popover" data-bs-trigger="focus">
                                        <span class="btn-circle">
                                            ?
                                        </span>
                                    </a>
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="nome_pai" maxlength="45"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="27" value="{{ $funcionario[0]->nome_pai }}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-5 col-sm-12">Número de Reservista
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control" name="reservista" maxlength="12"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="25" value="{{ $funcionario[0]->reservista }}">
                                </div>
                                <div class="col-md-2 col-sm-12">Cat CNH
                                    <select id="validationCustomUsername" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-select" name="cnh">
                                        <option value="{{ $funcionario[0]->id_tp_cnh }}">
                                            {{ $funcionario[0]->tp_cnh }}
                                        </option>
                                        @foreach ($tpcnh as $tpcnhs)
                                            <option value="{{ $tpcnhs->id }}">
                                                {{ $tpcnhs->nome_cat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12">Tipo Sanguíneo
                                    <select id="14" style="border: 1px solid #999999; padding: 5px;"
                                        name="tps" class="form-select">
                                        <option value="{{ $funcionario[0]->tp_sangue }}">
                                            {{ $funcionario[0]->nome_sangue }}
                                        </option>
                                        @foreach ($tpsangue as $tpsangues)
                                            <option value="{{ $tpsangues->id }}">
                                                {{ $tpsangues->nome_sangue }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12">Fator RH
                                    <select id="14" style="border: 1px solid #999999; padding: 5px;"
                                        name="fator" class="form-select">
                                        <option value="{{ $funcionario[0]->id_fator_rh }}">
                                            {{ $funcionario[0]->nome_fator }}
                                        </option>
                                        @foreach ($fator as $fators)
                                            <option value="{{ $fators->id }}">
                                                {{ $fators->nome_fator }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div>

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
                                    Dados Residenciais
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-4 col-sm-12">CEP
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="8" type="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="cep" name="cep" value="{{ $endereco[0]->cep }}">
                                </div>

                                <div class="col-md-4 col-sm-12">UF
                                    <br>
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="uf2" name="uf_end">
                                        <option value="{{ $endereco[0]->uf_endereco }}">
                                            {{ $endereco[0]->sigla_uf_endereco }}
                                        </option>
                                        @foreach ($tp_ufe as $tp_ufes)
                                            <option value="{{ $tp_ufes->id }}">
                                                {{ $tp_ufes->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 col-sm-12">Cidade
                                    <br>
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="cidade2" name="cidade">
                                        <option value="{{ $endereco[0]->cidade }}">
                                            {{ $endereco[0]->nome_cidade }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">Logradouro
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="45" type="text" id="logradouro" name="logradouro"
                                        value="{{ $endereco[0]->logradouro }}">
                                </div>

                                <div class="col-md-3 col-sm-12">Número
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        maxlength="10" type="text" id="numero" name="numero"
                                        value="{{ $endereco[0]->numero }}">
                                </div>
                                <div class="col-md-3 col-sm-12">Complemento
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;" maxlength="45"
                                        class="form-control" id="complemento" name="comple"
                                        value="{{ $endereco[0]->complemento }}">
                                </div>
                                <div class="col-md-3 col-sm-12">Bairro:
                                    <input type="text" style="border: 1px solid #999999; padding: 5px;" maxlength="45"
                                        class="form-control" id="bairro" name="bairro"
                                        value="{{ $endereco[0]->bairro }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="botões">
            <a href="javascript:history.back()" type="button" class="btn btn-danger col-md-3 col-2 mt-4 offset-md-2">
                Cancelar
            </a>
            <input type="submit" value="Confirmar" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-2">
        </div>
        <br>
    </form>
    <script>

    </script>
    <script>
        $(document).ready(function() {
            $('#cidade1, #setorid').select2({
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


            $('#uf2').change(function(e) {
                var stateValue = $(this).val();
                $('#cidade2').removeAttr('disabled');
                populateCities($('#cidade2'), stateValue);
            });

            $('#uf1').change(function(e) {
                var stateValue = $(this).val();
                $('#cidade1').removeAttr('disabled');
                populateCities($('#cidade1'), stateValue);
            });

            $('#idlimpar').click(function(e) {
                $('#idnome_completo').val("");
            });
        });
    </script>

    <script>
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })
        const popover = new bootstrap.Popover('.popover-dismiss', {
            trigger: 'focus'
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function customizePopover(popoverButton, content) {
                var popover = new bootstrap.Popover(popoverButton, {
                    content: content,
                    placement: "top"
                });

                popoverButton.addEventListener('shown.bs.popover', function() {
                    var popoverElement = document.querySelector('.popover');
                    popoverElement.style.borderRadius =
                        '10px'; // Define o raio da borda para 10px (ou o valor que desejar)
                    popoverElement.style.backgroundColor = 'rgb(134, 179, 246)'; // Cor de fundo azul
                });
            }

            var popoverButton1 = new bootstrap.Popover(document.querySelector('.popoverButton1'), {
                container: 'body'
            });

            var popoverButton2 = document.getElementById('popoverButton2');
            customizePopover(popoverButton2, 'Série do CTPS');

            var popoverButton3 = document.getElementById('popoverButton3');
            customizePopover(popoverButton3, 'UF do CTPS');

            var popoverButton4 = document.getElementById('popoverButton4');
            customizePopover(popoverButton4, 'Data de Emissão do CTPS');

            var popoverButton5 = document.getElementById('popoverButton5');
            customizePopover(popoverButton5, 'Número do Título de Eleitor');

            var popoverButton6 = document.getElementById('popoverButton6');
            customizePopover(popoverButton6, 'Zona do Título de Eleitor');

            var popoverButton7 = document.getElementById('popoverButton7');
            customizePopover(popoverButton7, 'Seção do Título de Eleitor');

            var popoverButton8 = document.getElementById('popoverButton8');
            customizePopover(popoverButton8, 'Data de Emissão do Título de Eleitor');

            var popoverButton9 = document.getElementById('popoverButton9');
            customizePopover(popoverButton9, 'Pai/Mãe');

            var popoverButton10 = document.getElementById('popoverButton10');
            customizePopover(popoverButton10, 'Pai/Mãe');
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#cep').on('input', function() {

                let cep = $(this).val().replace(/\D/g, '');

                if (cep.length === 8) {

                    $.ajax({
                        type: "GET",
                        url: 'https://viacep.com.br/ws/' + cep + '/json/',
                        dataType: "json",
                        success: function(response) {

                            console.log(response);
                            $('#logradouro').val(response.logradouro);
                            $('#bairro').val(response.bairro);
                            $('#complemento').val(response.complemento);
                        }
                    });

                }
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
