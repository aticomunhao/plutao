@extends('layouts.app')
@section('head')
    <title>Novo Certificado</title>
@endsection
@section('content')
    <form method="post" action="/adicionar-certificado/{{ $funcionario[0]->id }}">
        @csrf
        <div class="container-fluid">
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Novo Certificado
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <input class="form-control" type="text" value="{{ $funcionario[0]->nome_completo }}"
                                        id="iddt_inicio" name="dt_inicio" required="required" disabled>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="form-group row">
                                <div class="col-2">Grau Acadêmico
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;".../0000
                                        id="4" name="grau_academico" required="required">
                                        <option selected>Grau Acadêmico</option>
                                        @foreach ($graus_academicos as $grau_academico)
                                            <option value="{{ $grau_academico->id }}">
                                                {{ $grau_academico->nome_grauacad }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-7">Nome do Certificado
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="40" name="nome_curso" value="" required="required">
                                </div>
                                <div class="col-3">Data de Conclusão
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="" id="3" name="dtconc_cert" required="required">
                                </div>

                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group row">
                                    <div class="col-4">
                                        Nivel de Ensino
                                        <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                            id="4" name="nivel_ensino" required="required">
                                            <option selected>Nivel de Ensino</option>
                                            @foreach ($tp_niveis_ensino as $nivel_ensino)
                                                <option value="{{ $nivel_ensino->id }}">
                                                    {{ $nivel_ensino->nome_tpne }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-4">Etapa de Ensino
                                        <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                            id="4" name="etapa_ensino" required="required">
                                            <option selected>Etapa de Ensino</option>
                                            @foreach ($tp_etapas_ensino as $etapas_ensino)
                                                <option value="{{ $etapas_ensino->id }}">
                                                    {{ $etapas_ensino->nome_tpee }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">Entidade de Ensino
                                        <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                            id="4" name="entidade_ensino" required="required">

                                            @foreach ($tp_entidades_ensino as $entidade_ensino)
                                                <option value="{{ $entidade_ensino->id }}">
                                                    {{ $entidade_ensino->nome_tpentensino }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div>
            <a class="btn btn-danger col-md-3 col-2 mt-4 offset-md-1"
                href="/gerenciar-certificados/{{ $funcionario[0]->id }}" role="button">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-3" id="sucesso">
                Confirmar
            </button>
        </div>
    </form>
@endsection
