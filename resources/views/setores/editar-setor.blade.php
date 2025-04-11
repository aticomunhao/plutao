@extends('layouts.app')
@section('head')
<title>Editar Setor</title>
@endsection
@section('content')

<form method='POST' action="/atualizar-setor/{{$editar[0]->ids}}" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Editar Setor
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <input class="form-control" type="text" value="{{ $editar[0]->nome}}" id="iddt_inicio" name="dt_inicio" required="required" disabled>
                            </div>
                        </div>
                        <br>
                        <hr>
                        @csrf
                        <div class="row">
                            <div class="form-group  col-xl-3 col-md-5 ">
                                <label for="1">Nome</label>
                                <input class="form-control" name="nome" style="border: 1px solid #999999; padding: 5px;"maxlength="60" maxlength="45" oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" id="1" value="{{$editar[0]->nome}}" required>
                            </div>
                            <div class="form-group  col-xl-2 col-md-5">
                                <label for="2">Sigla</label>
                                <input type="text" class="form-control" style="border: 1px solid #999999; padding: 5px;"name="sigla" maxlength="45" oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" id="2" value="{{$editar[0]->sigla}}"  required>
                            </div>
                            <div class="form-group  col-xl-2 col-md-5 ">
                                <label for="3">Data de Inicio</label>
                                <input type="date" class="form-control" style="border: 1px solid #999999; padding: 5px;" name="dt_inicio" id="3" value="{{$editar[0]->dt_inicio}}"  required>
                            </div>
                            <div class="form-group  col-xl-2 col-md-5">
                                <label for="4">Data Final</label>
                                <input type="date" class="form-control" style="border: 1px solid #999999; padding: 5px;"name="dt_fim" id="4" value="{{$editar[0]->dt_fim}}">
                            </div>
                            <div class="form-group  col-xl-3 col-md-2">
                                <label for="3">Nível</label>
                                <select class="form-select" style="border: 1px solid #999999; padding: 5px;" name="nivel" id="3"  required>
                                    <option value="{{ $editar[0]->id_nivel }}">{{$editar[0]->nome_nivel }}</option>
                                    @foreach ($nivel as $niveis)
                                    <option value="{{ $niveis->idset }}">{{ $niveis->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <a href="/gerenciar-setor" type="button" value="" class="btn btn-danger col-md-3 col-2 mt-5 offset-md-1">Cancelar</a>
                <input type="submit" value="Confirmar" class="btn btn-primary col-md-3 col-1 mt-5 offset-md-3">
</form>

@endsection