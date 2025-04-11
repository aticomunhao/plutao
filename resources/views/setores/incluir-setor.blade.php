@extends('layouts.app')
@section('head')
<title>Criar Setor</title>
@endsection
@section('content')

<form method="post" action="/incluir-setores" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Criar Setor
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <hr>
                        @csrf
                        <div class="row">
                                <div class="form-group  col-xl-4 col-md-5 ">
                                    <label for="1">Nome</label>
                                    <input type="text" class="form-control" style="border: 1px solid #999999; padding: 5px;" id="1" name="nome_setor" value="{{ old('nome_setor') }}"  required>
                                </div>
                                <div class="form-group  col-xl-2 col-md-2">
                                    <label for="2">Sigla</label>
                                    <input type="text" class="form-control" style="border: 1px solid #999999; padding: 5px;" id="2" name="sigla" value="{{ old('sigla') }}" required>
                                </div>


                                <div class="form-group  col-xl-4 col-md-2">
                                    <label for="3">Nivel</label>
                                    <select class="form-select" name="nivel" id="3" value="{{ old('niv') }}" style="border: 1px solid #999999; padding: 5px;"  required>
                                        <value="">
                                            <option value=""></option>
                                            @foreach ($nivel as $niveis)
                                            <option value="{{ $niveis->idset }}">{{ $niveis->nome}}</option>
                                            @endforeach

                                    </select>
                                </div>
                                <div class="form-group  col-xl-2 col-md-2">
                                    <label for="4">Data de Inicio</label>
                                    <input type="date" class="form-control" style="border: 1px solid #999999; padding: 5px;" name="dt_inicio" id="4"  required>
                                </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <a class="btn btn-danger col-md-3 col-2 mt-5 offset-md-1" href="/gerenciar-setor" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary col-md-3 col-1 mt-5 offset-md-3">Confirmar</button>
</form>

@endsection