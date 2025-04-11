@extends('layouts.app')
@section('head')
    <title>
        Editar Tipo de Contrato</title>
@endsection
@section('content')
    
    <br>
    <div class="container"> {{-- Container completo da página  --}}
        <div class="row justify-content-center">
            <div class="col-md-8"> {{-- Ajuste a largura do card conforme necessário --}}
                <div class="card">
                    <div class="card-header">
                        Editar Novo Tipo de Contrato
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update.tipos-de-contrato', ['id' => $tipo_de_contrato->id]) }}" method="PUT">
                            @csrf
                            <div class="mb-3">
                                <input class="form-control" type="text" maxlength="100" name="nome" required
                                    value="{{ $tipo_de_contrato->nome }}">
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('index.tipos-de-contrato') }}" class="btn btn-danger">Cancelar</a>
                                <button class="btn btn-success" type="submit">Confirmar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
