@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <div class="container-fluid">
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089;">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Gerenciar Associado
                            </h5>
                        </div>
                    </div>
                    <div>
                        <div class="card-body">
                            <form class="justify-content-center" action="{{ route('pesquisar') }}"
                                class="form-horizontal mt-4" method="GET">
                                <div class="row">
                                    <div class="col-1">Nr Associado
                                        <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                            maxlength="50" type="text" id="2" name="nr_associado"
                                            value="{{ $nr_associado }}">
                                    </div>
                                    <div class="col-3">Nome
                                        <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                            maxlength="30" type="text" id="3" name="nome_completo"
                                            value="{{ $nome_completo }}">
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
                                        <select class="form-select" id="4" name="status" type="number"
                                            style="border: 1px solid #999999; padding: 5px;">
                                            <option value="">Todos</option>
                                            <option value="1">Ativo</option>
                                            <option value="2">Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col"><br>
                                        <input class="btn btn-light btn-sm"
                                            style="font-size: 0.9rem; box-shadow: 1px 2px 5px #000000; margin:5px;"
                                            type="submit" value="Pesquisar">

                                        <a href="/gerenciar-associado"><button class="btn btn-light btn-sm" type="button"
                                                value=""
                                                style="font-size: 0.9rem; box-shadow: 1px 2px 5px #000000; margin:5px;">Limpar</button></a>


                                        <a href="/informar-dados-associado"><input class="btn btn-success btn-sm"
                                                type="button" name="6" value="Novo Cadastro +"
                                                style="font-size: 0.9rem; box-shadow: 1px 2px 5px #000000; margin:5px;"></a>
                                    </div>
                            </form>
                        </div>
                        <br>
                        <hr>
                        <div class="table">
                            <table class="table table-striped table-bordered border-secondary table-hover align-middle">
                                <thead style="text-align: center;">
                                    <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                        <th class="col-1">Nr associado</th>
                                        <th class="col-3">Nome</th>
                                        <th class="col-1">Voluntário</th>
                                        <th class="col-1">Votante</th>
                                        <th class="col-1">Data Inicio</th>
                                        <th class="col-1">Data Final</th>
                                        <th class="col-1">Status</th>
                                        <th class="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 16px; color:#000000; text-align: center">
                                    @foreach ($lista_associado as $lista_associados)
                                        <tr>
                                            <td ><center>{{ $lista_associados->nr_associado }}</center></td>
                                            <td ><center>{{ $lista_associados->nome_completo }}</center></td>
                                            <td ><center>{{ $lista_associados->voluntario }}</center></td>
                                            <td ><center></center></td>
                                            <td ><center>{{ Carbon\Carbon::parse($lista_associados->dt_inicio)->format('d-m-Y') }}</center></td>
                                            <td>
                                            @if (!empty($lista_associados->dt_fim)){{ Carbon\Carbon::parse($lista_associados->dt_fim)->format('d-m-Y') }}
                                            @endif
                                            </td>
                                            <td "><center>{{ $lista_associados->status }}</center></td>
                                            <td>
                                                <a href="/editar-associado/{{ $lista_associados->ida }}"><button
                                                        type="button" class="btn btn-outline-warning btn-sm"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                                        <i class="bi-pencil" style="font-size: 1.2rem; color:#000000;"></i>
                                                </button></a>
                                                <a href="/capture-photo/{{ $lista_associados->ida }}"><button
                                                        type="button" class="btn btn-sm btn-outline-warning"
                                                        data-toggle="modal" data-target="#camera{{ $lista_associados->ida }}"
                                                        data-bs-placement="top" title="Foto">
                                                        <i class="bi-camera" style="font-size: 1.2rem; color:#000000;""></i>
                                                </button></a>
                                                <a href="/gerenciar-dados_bancarios/{{ $lista_associados->ida }}"><button
                                                        type="button" class="btn btn-outline-warning btn-sm" data-placement="top" title="Contribuição">
                                                        <i class="bi-currency-dollar" style="font-size: 1.2rem; color:#000000;""></i>
                                                </button></a>
                                                <a href="/documento-associado/{{ $lista_associados->ida }}"><button
                                                        type="button" class="btn btn-sm btn-outline-warning"
                                                        data-placement="top" title="Gerar Form">
                                                        <i class="bi bi-clipboard-data" style="font-size: 1.2rem; color:#000000;""></i>
                                                </button></a>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                data-placement="top" title="Visualizar">
                                                    <i class="bi-search" style="font-size: 1.2rem; color:#000000;""></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-bs-toggle="modal" data-bs-target="#inativar{{ $lista_associados->ida }}" data-placement="top" title="Inativar"
                                                    class="btn btn-outline-danger">
                                                    <i class="bi-x-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-bs-toggle="modal" data-bs-target="#excluir{{ $lista_associados->ida }}" data-placement="top" title="Excluir"
                                                    class="btn btn-outline-danger">
                                                    <i class="bi-trash"></i>
                                                </button>

                                                 <!-- Modal de cancelar -->
                                                 <div>
                                                    <div class="modal fade" id="inativar{{ $lista_associados->ida }}"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background-color:#DC4C64;">
                                                                    <div class="row">
                                                                        <h5 style="color:white;">Inativar Associado</h5>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-body">                                                                    
                                                                <form method='POST' action="/inativar-associado/{{ $lista_associados->ida }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <center>
                                                                        <label for="recipient-name" class="col-form-label" style="font-size:17px">Tem certeza que deseja inativar:<br /><span
                                                                                style="color:#DC4C64; font-weight: bold;">{{ $lista_associados->nome_completo }}</span>&#63;</label>
                                                                        <br />
                                                                    </center>
                                                                    <center>
                                                                        <div class="mb-2 col-10">
                                                                            <input class="form-control"  type="date" name="dt_fim" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                                                                            <label class="col-form-label">Insira o motivo da <span style="color:#DC4C64">inativação:</span></label>
                                                                            <select class="form-select teste1" name="motivo" required>
                                                                                @foreach ($motivo as $motivos)
                                                                                    <option value="{{ $motivos->idsp }}">{{ $motivos->motivo }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </center>
                                                                </div> 
                                                                <div class="modal-footer">
                                                                    <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Cancelar</button>
                                                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                                                </div>
                                                                </form>                                                             
                                                            </div>                                                              
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--Fim Modal de cancelar-->
                                               
                                                <!-- Modal de excluir -->
                                                <div>
                                                    <div class="modal fade" id="excluir{{ $lista_associados->ida }}"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background-color:#DC4C64;">
                                                                    <div class="row">
                                                                        <h5 style="color:white;">Excluir Associado</h5>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="alert alert text-center">
                                                                        Você realmente deseja excluir o Associado:
                                                                        <br>
                                                                        <span class="fw-bold" style="color:#DC4C64;">{{ $lista_associados->nome_completo }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                                                    <a href="/excluir-associado/{{ $lista_associados->ida }}">
                                                                        <button type="button" class="btn btn-primary">Confirmar</button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--Fim Modal de excluir-->
                                                <!-- Modal -->

                                                <div class="modal fade bd-example-modal-lg"
                                                    id="armazenar{{ $lista_associados->ida }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                    Documento
                                                                    Autorização Débito em Conta</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Fechar">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <center>
                                                                                <h6>Arquivo Atual</h6>
                                                                                <a
                                                                                    href="/visualizar-arquivo/{{ $lista_associados->ida }}"><button
                                                                                        type="button"
                                                                                        class="btn btn-outline-primary btn-sm"><i
                                                                                            class="bi-search"
                                                                                            style="font-size: 2rem; color:#303030;"></i></button></a>
                                                                            </center>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <form method='POST'
                                                                                action="/salvar-documento-associado/{{ $lista_associados->ida }}"
                                                                                enctype="multipart/form-data">
                                                                                @csrf
                                                                                <div class="form-group">
                                                                                    <label for="exampleFormControlFile1">
                                                                                        <h6>Carregar Arquivo</h6>

                                                                                    </label>
                                                                                    <input type="file"
                                                                                        class="form-control-file"
                                                                                        id="exampleFormControlFile1"
                                                                                        name="arquivo">
                                                                                </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-danger"
                                                                                data-bs-dismiss="modal">Cancelar</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Salvar
                                                                                mudanças</button>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
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
                    <div style="margin-right: 10px; margin-left: 10px">
                        {{ $lista_associado->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

@endsection
