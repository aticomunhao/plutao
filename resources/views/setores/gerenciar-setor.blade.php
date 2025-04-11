@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089;">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Gerenciar Setores
                            </h5>
                        </div>
                    </div>
                    <div>
                        <div class="card-body">
                            <form class="justify-content-center" action="{{ route('pesquisar-setor') }}"
                                class="form-horizontal" method="GET">
                                <div class="row mt-2">
                                    <div class="col-3">Setor
                                        <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                            maxlength="50" type="text" id="2" name="nome"
                                            value="{{ $nome }}">
                                    </div>
                                    <div class="col-1">Sigla
                                        <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                            maxlength="30" type="text" id="3" name="sigla"
                                            value="{{ $sigla }}">
                                    </div>
                                    <div class="col-2">Data de Início
                                        <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                            maxlength="30" type="date" id="3" name="dt_inicio"
                                            value="{{ $dt_inicio }}">
                                    </div>
                                    <div class="col-2">Data de Fim
                                        <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                            maxlength="30" type="date" id="3" name="dt_fim"
                                            value="{{ $dt_fim }}">
                                    </div>
                                    <div class="col-1">Status
                                        <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                            id="4" name="status" type="number">
                                            <option value="">Todos</option>
                                            <option value="1">Ativo</option>
                                            <option value="2">Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col" style="margin-top:22px">
                                        <input class="btn btn-light btn-sm"
                                            style="box-shadow: 1px 2px 5px #000000; font-size: 1rem" type="submit"
                                            value="Pesquisar">

                                        <a href="/gerenciar-setor">
                                            <button class="btn btn-light btn-sm" type="button" value=""
                                                style="box-shadow: 1px 2px 5px #000000; margin:2px; font-size: 1rem">
                                                Limpar
                                            </button>
                                        </a>
                            </form>

                            <a href="/incluir-setor"><input class="btn btn-success btn-sm" type="button" name="6"
                                    value="Novo Cadastro +" style="box-shadow: 1px 2px 5px #000000; font-size: 1rem"></a>

                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="table">
                        <table
                            class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                            <thead style="text-align: center;">
                                <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                    <th class="col-4">Setor</th>
                                    <th class="col-1">Sigla</th>
                                    <th class="col">Data Inicio</th>
                                    <th class="col">Data Final</th>
                                    <th class="col-1">Status</th>
                                    <th class="col">Substituto</th>
                                    <th class="col">Ações</th>

                                </tr>
                            </thead>
                            <tbody style="font-size: 16px; color:#000000; text-align: center">
                                @foreach ($lista_setor as $lista_setores)
                                    <tr>

                                        <td scope="">
                                            {{ $lista_setores->nome }}
                                        </td>
                                        <td scope="">
                                            {{ $lista_setores->sigla }}
                                        </td>
                                        <td scope="">
                                            {{ Carbon\Carbon::parse($lista_setores->dt_inicio)->format('d-m-Y') }}
                                        </td>
                                        <td scope="">
                                            @if (!empty($lista_setores->dt_fim))
                                                {{ Carbon\Carbon::parse($lista_setores->dt_fim)->format('d-m-Y') }}
                                            @endif
                                        </td>
                                        <td scope="">
                                            {{ $lista_setores->status }}
                                        </td>
                                        <td scope="">
                                            {{ $lista_setores->nome_substituto }}
                                        </td>


                                        <td scope="" style="text-align: center">


                                            <a href="/editar-setor/{{ $lista_setores->ids }}"
                                                class="btn btn-outline-warning">

                                                <i class="bi-pencil" style="font-size: 1rem; color:#303030;"></i>

                                            </a>
                                            <a href="/carregar-dados/{{ $lista_setores->ids }}"
                                                class="btn btn-outline-primary">

                                                <i class="bi bi-arrow-left-right"
                                                    style="font-size: 1rem;color:#303030; "></i>

                                            </a>

                                            <a href="/visualizar-setor/{{ $lista_setores->ids }}" type="button"
                                                class="btn btn-outline-primary"><i class="bi-search"
                                                    style="font-size: 1rem; color:#303030;"></i>
                                            </a>

                                            <a type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                                data-bs-target="#A{{ $lista_setores->ids }}"
                                                class="btn btn-outline-danger"><i class="bi-trash"
                                                    style="font-size: 1rem; color:#303030;"></i>
                                            </a>

                                            <!-- Modal -->
                                            <div class="modal fade" id="A{{ $lista_setores->ids }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="background-color:#DC4C64;">
                                                            <h5 class="modal-title" id="exampleModalLabel"
                                                                style=" color:rgb(255, 255, 255)">Excluir Setor</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Você realmente deseja excluir o setor
                                                            <br>
                                                            <span style="color:#DC4C64; font-weight: bold">
                                                                {{ $lista_setores->nome }}</span> ?
                                                        </div>
                                                        <div class="modal-footer mt-2">
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Cancelar
                                                            </button>
                                                            <a type="button" class="btn btn-primary"
                                                                href="/excluir-setor/{{ $lista_setores->ids }}">Confirmar
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--Fim Modal-->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
