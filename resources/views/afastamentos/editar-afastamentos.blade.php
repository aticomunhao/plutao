@extends('layouts.app')
@section('head')
    <title>Editar Afastamento</title>
@endsection
@section('content')
    <form method="post" action="/atualizar-afastamento/{{ $afastamentos->id }}" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid"> {{-- Container completo da página  --}}
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Editar Afastamento
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
                                <div class="form-group col-3">Motivo do Afastamento
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        name="tipo_afastamento" required="required">
                                        <option value="{{ $afastamentos->id_tp_afastamento }}">
                                            {{ $afastamento_com_tipo->nome_tp_afastamento }}
                                        </option>
                                        @foreach ($tipoafastamentos as $tipoafastamentos)
                                            <option value="{{ $tipoafastamentos->id }}">
                                                {{ $tipoafastamentos->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-2">Data inicial da Ausência
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="{{ $afastamentos->dt_inicio }}" id="iddt_inicio"
                                        name="dt_inicio" required="required">
                                </div>
                                <div class="form-group col-2">Data de Retorno à Atividade
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="{{ $afastamentos->dt_fim }}" id="iddt_fim" name="dt_fim"
                                        required="required">
                                </div>
                                <div class="form-group col-2" style="text-align: center">Arquivo Anexado
                                    <p>
                                        @if ($afastamentos->caminho)
                                            <a href="{{ asset($afastamentos->caminho) }}" target="_blank">
                                                <button type="button" class="btn btn-lg btn-outline-secondary">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            </a>
                                        @else
                                            Nenhum arquivo anexado
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group col-3">Novo Arquivo
                                    <input type="file" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control form-control-sm" name="ficheiroNovo" id="idficheiro">
                                </div>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" style="border: 1px solid #999999; padding: 5px;"
                                    class="form-check-input" id="justificado" name="justificado" value="justificado"
                                    {{ $afastamentos->justificado ? 'checked' : '' }}>
                                <label class="form-check-label" for="justificado">
                                    Justificado?
                                </label>
                            </div>
                            <div class="mb-3 mt-md-0">
                                <label for="exampleFormControlTextarea1" class="form-label">
                                    Observação
                                </label>
                                <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                    value="{{ $afastamentos->observacao }}" type="text" maxlength="40"
                                    id="2"class="form-control " id="idobservacao" rows="3" value=""
                                    name="observacao">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div>
            <a class="btn btn-danger col-md-3 col-2 mt-4 offset-md-1" href="/gerenciar-afastamentos/{{ $funcionario->id }}"
                role="button">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-3" id="sucesso">
                Confirmar
            </button>
        </div>
    </form>
@endsection
