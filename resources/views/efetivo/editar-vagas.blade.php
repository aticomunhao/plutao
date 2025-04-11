@extends('layouts.app')

@section('head')
    <title>Editar Vagas</title>
@endsection

@section('content')
    @csrf
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($busca)
        <form method="post" action="/atualizar-vagas/{{ $busca->idVagas }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="idVagas" value="{{ $busca->idVagas }}">
            <div class="container-fluid"> {{-- Container completo da página --}}
                <div class="justify-content-center">
                    <div class="col-12">
                        <br>
                        <div class="card" style="border-color: #355089">
                            <div class="card-header">
                                <div class="row">
                                    <h5 class="col-12" style="color: #355089">
                                        Editar Vagas
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row" style="margin-left:5px">
                                    <div style="display: flex; gap: 20px; align-items: flex-end; margin-top: 20px;">
                                        <div for="cargoSelect" class="form-label">Cargo a ser editado
                                            <select id="cargoSelect" class="form-select status select pesquisa-select"
                                                style="width: 600px;" name="vagasCargo" disabled>
                                                <option value="{{ $busca->idCargo }}">{{ $busca->nomeCargo }}</option>
                                            </select>
                                        </div>
                                        <div for="setorSelect" style="margin-top:20px" class="form-label">Setor
                                            pertencente
                                            <select id="setorSelect" class="form-select status select pesquisa-select"
                                                style="width: 600px;" name="vagasSetor" disabled>
                                                <option value="{{ $busca->idSetor }}">{{ $busca->siglaSetor }} - {{ $busca->nomeSetor }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 20px; align-items: flex-end; margin-top: 20px;">
                                        <div>
                                            <label for="vTotal" style="margin-top:20px;" class="form-label">
                                                Selecione a quantidade de vagas a ser editada para o cargo selecionado
                                            </label>
                                            <input type="number" class="form-control form-control-number" id="vTotal"
                                                style="width: 600px;" name="vTotal" value="{{ $busca->vTotal }}">
                                        </div>
                                        <div>
                                            <label for="vExcelencia" style="margin-top:20px" class="form-label">
                                                Selecione a quantidade de vagas de Excelência a ser editada para o cargo
                                                selecionado
                                            </label>
                                            <input type="number" class="form-control form-control-number" id="vExcelencia"
                                                style="width: 600px;" name="vExcelencia" value="{{ $busca->vExcelencia }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div>
                <a class="btn btn-danger col-md-3 col-2 mt-4 offset-md-1" href="/controle-vagas" role="button">Cancelar</a>
                <button type="submit" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-3"
                    id="sucesso">Confirmar</button>
            </div>
        </form>
    @endif
@endsection
