@extends('layouts.app')
@section('head')
    <title>Editar Dependentes</title>
@endsection
@section('content')
    <form method="post" action="/atualizar-dependentes/{{ $dependente->id }}" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Editar Dependente
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
                                <div class="col-2 mt-1 mt-md-0">
                                    Parentesco
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        name="relacao_dep" required="required">
                                        <option value="{{ $dependente->id_parentesco }}">
                                            {{ $dependente->nome }}
                                        </option>
                                        @foreach ($tp_relacao as $tp_relacao_item)
                                            <option value="{{ $tp_relacao_item->id }}">
                                                {{ $tp_relacao_item->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mt-3 mt-md-0">Nome completo
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="40" id="2"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        name="nomecomp_dep" value="{{ $dependente->nome_dependente }}" required="required">
                                </div>
                                <div class="col-2 mt-3 mt-md-0">Data nascimento
                                    <input class="form-control"style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="{{ $dependente->dt_nascimento }}" id="3"
                                        name="dtnasc_dep" required="required">
                                </div>
                                <div class="col-2 mt-3 mt-md-0">CPF
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="numeric" maxlength="11" value="{{ $dependente->cpf }}" id="8"
                                        name="cpf_dep" required="required"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
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
            <a class="btn btn-danger col-md-3 col-2 mt-4 offset-md-1" href="/gerenciar-dependentes/{{ $funcionario->id }}"
                role="button">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-3" id="sucesso">
                Confirmar
            </button>
        </div>
    </form>
@endsection
