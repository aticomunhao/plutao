@extends('layouts.app')


@section('content')
    <div class="container-fluid">
        <div class="col-12">
            <div class="row justify-content-center" class="form-horizontal mt-4"
                method="GET">
                <div class="row" style="padding-left:5%">
                    <div class="col-3">Setor
                        <input class="form-control" maxlength="50" type="text" id="2" name="nome"
                            value="{{ $nome }}">
                    </div>
                    <div class="col-1">Sigla
                        <input class="form-control" maxlength="30" type="text" id="3" name="sigla"
                            value="{{ $sigla }}">
                    </div>
                    <div class="col-1">Data de Início
                        <input class="form-control" maxlength="30" type="date" id="3" name="dt_inicio"
                            value="{{ $dt_inicio }}">
                    </div>
                    <div class="col-1">Data de Fim
                        <input class="form-control" maxlength="30" type="date" id="3" name="dt_fim" 
                            value="{{ $dt_fim }}">
                    </div>
                    <div class="col" style="padding-left:20%"><br>
                        <input class="btn btn-light btn-sm"
                            style="font-size: 0.9rem; box-shadow: 1px 2px 5px #000000; margin:5px;" type="submit"
                            value="Pesquisar">

                        <a href="/gerenciar-setor"><button class="btn btn-light btn-sm" type="button" value=""
                                style="font-size: 0.9rem; box-shadow: 1px 2px 5px #000000; margin:5px;">Limpar</button></a>
                        </form>

                        <a href="/incluir-setor"><input class="btn btn-success btn-sm" type="button" name="6"
                                value="Novo Cadastro +"
                                style="font-size: 0.9rem; box-shadow: 1px 2px 5px #000000; margin:5px;"></a>

                    </div>
                </div>
            </div>
            <hr>
            <div class="table">
                <table class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                    <thead style="text-align: center;">
                        <tr style="background-color: #365699; font-size:19px; color:#ffffff">
                            <th class="col-2">Setor</th>
                            <th class="col-1">Sigla</th>
                            <th class="col-1">Data Inicio</th>
                            <th class="col-1">Data Final</th>
                            <th class="col-1">Status</th>
                            <th class="col-1">Substituto</th>
                            <th class="col-1">Ações</th>

                        </tr>
                    </thead>
                    <tbody style="font-size: 16px; color:#000000;">
                        <tr>
                            @foreach ($lista as $listas)
                                <td scope="">
                                    <center>{{ $listas->nome }}</center< /td>
                                <td scope="">
                                    <center>{{ $listas->sigla }}</center>
                                </td>
                                <td scope="">
                                    <center>{{ $listas->dt_inicio }}</center>
                                </td>
                                <td scope="">
                                    <center>{{ $listas->dt_fim }}</center>
                                </td>
                                <td scope="">
                                    <center>{{ $listas->status?'Ativo':'Inativo'}}</center>
                                </td>
                                <td scope="">
                                    <center>{{ $listas->nome_substituto}}</center>
                                </td>


                                <td scope="">
                                    <center>

                                        <a href="/editar-setor/{{ $listas->ids}}"><button type="button"
                                                class="btn btn-outline-warning btn-sm"><i class="bi-pencil"
                                                    style="font-size: 1rem; color:#303030;"></i></button></a>
                                        <a href=""><button type="button" class="btn btn-outline-primary btn-sm"><i
                                                    class="bi-search"
                                                    style="font-size: 1rem; color:#303030;"></i></button></a>
                                        
                                        <a href ="setores-pessoa"><button type="button" class="btn btn-outline-primary btn-sm"><i
                                                class="bi-people-fill"
                                                style="font-size: 1rem;color:#303030; "></i></button></a>

                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#A{{ $listas->nome }}-{{ $listas->ids}}"class="btn btn-outline-danger btn-sm"><i
                                                class="bi-trash" style="font-size: 1rem; color:#303030;"></i></button>







                                        <!-- Modal -->
                                        <div>
                                            <div class="modal fade" id="A{{ $listas->nome}}-{{ $listas->ids}}"
                                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Excluir
                                                                Funcionário</h5>

                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="fw-bold alert alert-danger text-center">Você
                                                                realmente deseja excluir o setor:
                                                                <br>
                                                                {{ $listas->nome }}
                                                                <br>
                                                                com o subsetor: {{ $listas->nome}}
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <a
                                                                href="/excluir-setor/{{ $listas->ids }}/{{ $listas->ids }}"><button
                                                                    type="button"
                                                                    class="btn btn-primary">Confirmar</button></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>





                                        <!--Fim Modal-->


                                </td>
                        </tr>

                        </tr>
                        @endforeach
                    @endsection
