@extends('layouts.app')
@section('head')
    <title>Atualizar Tipo de Desconto</title>
@endsection
@section('content')
    <br />

    <div class="container">

        <div class="card" style="border-color:#355089">

            <div class="card-header">
                Atualizar tipo de Desconto
            </div>

            <div class="card-body">
                <br>
                <div class="row justify-content-start">
                    <form method="POST" action="/modificar-tipo-desconto/{{ $inf->id }}">
                        @csrf

                            <div class="row col-10 offset-1" style="margin-top:none">
                                <div class="col-md-6 col-12">
                                    <div>Tipo de desconto</div>
                                    <input type="text" class="form-control" aria-label="Sizing example input"
                                        placeholder="Tipo de desconto..." name = "edittpdesc" required="Required"
                                        value = '{{ $inf->description }}' maxlength="50">
                                </div>
                                <div class="col-md-3 col-12 mt-3 mt-md-0 ">
                                    <div>Porcentagem</div>
                                    <input type="number" class="form-control" aria-label="Sizing example input"
                                        placeholder="Porcentagem do desconto..." name = "editpecdesc" required="Required"
                                        value = '{{ $inf->percDesconto }}'>
                                </div>
                                <div class="col-md-3 col-12 mt-3 mt-md-0 ">
                                    <div>Data de inicio</div>
                                    <input type="date" class="form-control" aria-label="Sizing example input"
                                         name = "dtdesc" value="{{ $inf->dt_inicio }}">
                                </div>
                            </div>



                        <center>
                            <div class="col-12" style="margin-top: 70px;">
                                <a href="/gerenciar-tipo-desconto" class="btn btn-secondary col-3">
                                    Cancelar
                                </a>

                                <button type = "submit" class="btn btn-primary col-3 offset-3">
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
