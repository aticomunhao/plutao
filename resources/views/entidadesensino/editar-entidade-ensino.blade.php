@extends('layouts.app')
@section('head')
<title>Editar Entidade de Ensino</title>
@endsection
@section('content')
    <br />

    <div class="container">

        <div class="card" style="border-color:#355089">

            <div class="card-header">
                Editar Entidades de Ensino
            </div>

            <div class="card-body">

                <br>
                <div class="row justify-content-start">
                    <form method="POST" action="/atualizar-entidade-ensino/{{ $entidade->id }}">{{-- Formulario de envio com action passando um ID --}}
                        @csrf

                            <div class="row col-10 offset-1" style="margin-top:none">

                                <div class=" col-12">
                                    <div>Entidade de ensino</div>
                                    <input class="form-control" type="text" maxlength="100"
                                    id="2"name="nome_ent" value="{{ $entidade->nome_tpentensino }}" required="required">{{-- Input com value do item que esta sendo editado --}}
                                </div>




                        <center>
                            <div class="col-12" style="margin-top: 70px;">
                                <a href="/gerenciar-entidades-de-ensino" class="btn btn-danger col-4">{{-- Botao cancelar com link para o index --}}
                                    Cancelar
                                </a>

                                <button type = "submit" class="btn btn-primary col-4 offset-3">{{-- Botao submit --}}
                                    Confirmar
                                </button>
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
