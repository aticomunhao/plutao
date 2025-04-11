@extends('layouts.app')
@section('head')
    <title>Editar Contrato</title>{{-- ERA ACORDO ANTES, ESTÁ COMO ACORDO NO BANCO --}}
@endsection
@section('content')
    <form method="post" action="/atualizar-contrato/{{ $contrato->id }}" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">{{-- ERA ACORDO ANTES, ESTÁ COMO ACORDO NO BANCO --}}
                                    Editar Contrato
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
                            <div class="form-group row">
                                <div class="col-md-2 col-sm-12">Número de Matrícula
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="numeric" maxlength="11"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        id="1" name="matricula" value="{{ $contrato->matricula }}" required="required">
                                </div>
                                <div class="form-group col-md-2">Tipo de Contrato{{-- ERA ACORDO ANTES, ESTÁ COMO ACORDO NO BANCO --}}
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        name="tipo_contrato" required="required" value="{{ $contrato->tp_contrato }}">
                                        @foreach ($tipocontrato as $tiposcontrato)
                                            <option value="{{ $tiposcontrato->id }}">{{ $tiposcontrato->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2">Data de Inicio
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="{{ $contrato->dt_inicio }}" id="iddt_inicio" name="dt_inicio"
                                        required="required">
                                </div>
                                <div class="form-group col-md-1" style="text-align: center">Arquivo atual
                                    <p>
                                        <a href="{{ asset("$contrato->caminho") }}"><button type="button"
                                                class="btn btn-lg btn-outline-secondary"><i
                                                    class="bi bi-archive"></i></button>
                                        </a>
                                    </p>
                                </div>
                                <div class="form-group col-2">Novo Arquivo
                                    <input type="file" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control form-control-sm" name="ficheiroNovo" id="idficheiro">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div>
            <a class="btn btn-danger col-md-3 col-2 mt-4 offset-md-1" href="/gerenciar-contrato/{{ $funcionario->id }}"
                role="button">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-3" id="sucesso">
                Confirmar
            </button>
        </div>
    </form>
@endsection
