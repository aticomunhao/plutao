@extends('layouts.app')

@section('head')
    <title>Visualizar Funcionário</title>
@endsection

@section('content')
    @csrf
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h5 class="col-12" style="color: #355089">
                                    Visualizar Dados Pessoais
                                </h5>
                            </div>
                            <div class="col">
                                <a href="javascript:history.back()">
                                    <button type="button" class="btn btn-danger btn-sm float-end remove-proposta">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <fieldset class="border rounded p-4 position-relative" style="margin-bottom: 20px">
                            <legend class="w-auto"
                                style="font-size: .9rem; padding: 0 10px; position: absolute; top: -12px; left: 20px; background: white; color: red">
                                Dados pessoais</legend>
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Nome Completo:
                                    </legend>
                                    {{ $pessoa[0]->nome_completo }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Nome Resumido:
                                    </legend>
                                    {{ $pessoa[0]->nome_resumido }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Número da Matrícula:
                                    </legend>
                                    {{ $funcionario[0]->matricula }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        CPF:
                                    </legend>
                                    {{ $pessoa[0]->cpf }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Data de Nascimento:
                                    </legend>
                                    {{ \Carbon\Carbon::parse($pessoa[0]->dt_nascimento)->format('d/m/Y') }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Sexo:
                                    </legend>
                                    {{ $pessoa[0]->nome_sexo }}
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Nacionalidade:
                                    </legend>
                                    {{ $pessoa[0]->nome_nacionalidade }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        UF de Nascimento:
                                    </legend>
                                    {{ $pessoa[0]->sigla_naturalidade }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Naturalidade:
                                    </legend>
                                    {{ $pessoa[0]->descricao_cidade }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Email:
                                    </legend>
                                    {{ $pessoa[0]->email }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        DDD:
                                    </legend>
                                    {{ $pessoa[0]->numero_ddd }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Telefone/Celular:
                                    </legend>
                                    {{ $pessoa[0]->celular }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Número de Identidade:
                                    </legend>
                                    {{ $pessoa[0]->identidade }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        UF da Identidade:
                                    </legend>
                                    {{ $identidade[0]->sigla_identidade }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Órgão Expedidor:
                                    </legend>
                                    {{ $pessoa[0]->sigla_orgao_expedidor }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Data de Emissão:
                                    </legend>
                                    {{ \Carbon\Carbon::parse($pessoa[0]->dt_emissao_identidade)->format('d/m/Y') }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Mãe:
                                    </legend>
                                    {{ $funcionario[0]->nome_mae }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Pai:
                                    </legend>
                                    {{ $funcionario[0]->nome_pai }}
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="border rounded p-4 position-relative" style="margin-bottom: 20px">
                            <legend class="w-auto"
                                style="font-size: .9rem; padding: 0 10px; position: absolute; top: -12px; left: 20px; background: white; color: red">
                                Dados Funcionais</legend>
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Titulo Eleitor:
                                    </legend>
                                    {{ $funcionario[0]->titulo_eleitor }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Zona:
                                    </legend>
                                    {{ $funcionario[0]->zona_titulo }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Seção:
                                    </legend>
                                    {{ $funcionario[0]->secao_titulo }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Data de Emissão:
                                    </legend>
                                    {{ \Carbon\Carbon::parse($funcionario[0]->dt_titulo)->format('d/m/Y') }}

                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Tipo Programa:
                                    </legend>
                                    {{ $funcionario[0]->nome_programa }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Número do PIS/NIS/PASEP:
                                    </legend>
                                    {{ $funcionario[0]->nr_programa }}
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Data de Admissão:
                                    </legend>
                                    {{ \Carbon\Carbon::parse($funcionario[0]->dt_inicio)->format('d/m/Y') }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        CTPS:
                                    </legend>
                                    {{ $funcionario[0]->ctps }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Série do CTPS:
                                    </legend>
                                    {{ $funcionario[0]->serie_ctps }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        UF do CTPS:
                                    </legend>
                                    {{ $funcionario[0]->sigla_ctps }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Data de Emissão do CTPS:
                                    </legend>
                                    {{ \Carbon\Carbon::parse($funcionario[0]->emissao_ctps)->format('d/m/Y') }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Cor Pele:
                                    </legend>
                                    {{ $funcionario[0]->nome_cor }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Setor Alocado:
                                    </legend>
                                    {{ $acharSetor->nome_setor }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Número de Reservista:
                                    </legend>
                                    {{ $funcionario[0]->reservista }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Cat CNH:
                                    </legend>
                                    {{ $funcionario[0]->tp_cnh }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Tipo Sanguíneo:
                                    </legend>
                                    {{ $funcionario[0]->nome_sangue }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Fator RH:
                                    </legend>
                                    {{ $funcionario[0]->nome_fator }}
                                </div>

                            </div>
                        </fieldset>
                        <fieldset class="border rounded p-4 position-relative" style="margin-bottom: 10px">
                            <legend class="w-auto"
                                style="font-size: .9rem; padding: 0 10px; position: absolute; top: -12px; left: 20px; background: white; color: red">
                                Endereço</legend>
                            <div class="row">
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        CEP:
                                    </legend>
                                    {{ $endereco[0]->cep }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        UF:
                                    </legend>
                                    {{ $endereco[0]->sigla_uf_endereco }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Cidade:
                                    </legend>
                                    {{ $endereco[0]->nome_cidade }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Logradouro:
                                    </legend>
                                    {{ $endereco[0]->logradouro }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Número:
                                    </legend>
                                    {{ $endereco[0]->numero }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Complemento:
                                    </legend>
                                    {{ $endereco[0]->complemento }}
                                </div>
                                <div class="col">
                                    <legend class="schedule-border"
                                        style="font-size: small; width:inherit; font-weight: bold;">
                                        Bairro:
                                    </legend>
                                    {{ $endereco[0]->bairro }}
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <br>
    <div class="row d-flex justify-content-around">
        <div class="col" style="margin-left: 10px">
            <a href="javascript:history.back()">
                <button class="btn btn-danger col-md-1 col-2">Fechar</button>
            </a>
        </div>
    </div>
    <br>
    <button type="button" class="btn btn-danger btn-floating btn-lg" id="btn-back-to-top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <style>
        #btn-back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
        }
    </style>
    <script>
        //Get the button
        let mybutton = document.getElementById("btn-back-to-top");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {
            scrollFunction();
        };

        function scrollFunction() {
            if (
                document.body.scrollTop > 20 ||
                document.documentElement.scrollTop > 20
            ) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }
        // When the user clicks on the button, scroll to the top of the document
        mybutton.addEventListener("click", backToTop);

        function backToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
@endsection
