@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <form class="form-horizontal mt-4" method="POST" action="/cad-voluntario/inserir">
                    @csrf
                    <div class="form-group row">
                        <div class="col">Matrícula
                            <input class="form-control" required="required" type="numeric" id="1" name="matricula">
                        </div>
                        <div class="col">Nome completo
                            <input class="form-control" required="required" type="text" id="2" name="nome">
                        </div>
                        <div class="col">Data nasicmento
                            <input class="form-control" type="date" value="" id="3" name="dt_nascimento">
                        </div>
                        <div class="col">Sexo

                        </div>
                    </div>

                    <div class="row mt-10">
                        <div class="col-md-4">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="numeric" class="form-control cep-mask" id="24" name="cep"
                                placeholder="Ex.:00000-000">
                        </div>
                        <div class="col-md-4">
                            <label for="logradouro" class="form-label">Logradouro</label>
                            <input type="text" class="form-control" id="28" name="logradouro">
                        </div>
                        <div class="col-md-4">
                            <label for="numero" class="form-label">Número</label>
                            <input type="numeric" class="form-control" id="29" name="numero">
                        </div>
                        <div class="col-md-4">
                            <label for="apartamento" class="form-label">Apartamento</label>
                            <input type="numeric" class="form-control" id="26" name="apart">
                        </div>
                        <div class="col-md-4">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="27" name="bairro">
                        </div>
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="25" name="estado">
                        </div>
                        <div class="col-md-4">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="25" name="cidade">
                        </div>
                    </div>--
                    <div class="row">
                        <div class="col-12 mt-3" style="text-align: right;">
                            <button type="submit" class="btn btn-success">Confirmar</button>
                            <a href="/gerenciar-pessoa">
                                <input class="btn btn-danger" type="button" value="Limpar">
                            </a>
                        </div>
                </form>

            </div>
        </div>
    </div>
@endsection
