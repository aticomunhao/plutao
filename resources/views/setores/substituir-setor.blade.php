@extends('layouts.app')
@section('head')
<title>Substituir Setor</title>
@endsection
@section('content')

<form method="POST" action="/substituir-setor/{ids}" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Substituir Setor
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <input class="form-control" type="text" value="{{ $nome_setor[0]->nome}}" id="iddt_inicio" name="dt_inicio" required="required" disabled>
                            </div>
                        </div>
                        <br>
                        <hr>
                        @csrf
                        <div class="row justify-content-start">
                            @csrf
                            <div class="row col-10 offset-1" style="margin-top:none">

                                <div class=" col-12">
                                    <div>Selecione Setor Substituto</div>
                                    <select class="form-select"  style="border: 1px solid #999999; padding: 5px;" name="setor_substituto" required>
                                        <value="">
                                            <option value=""></option>
                                            @foreach ($setor as $setores)
                                            <option value="{{ $setores->id }}">{{ $setores->nome}}</option>
                                            @endforeach
                                    </select>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="/gerenciar-setor" type="button" value="" class="btn btn-danger col-md-3 col-2 mt-5 offset-md-1">Cancelar</a>
                <input type="submit" value="Confirmar" class="btn btn-primary col-md-3 col-1 mt-5 offset-md-3">
</form>

@endsection