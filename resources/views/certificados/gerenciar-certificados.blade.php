@extends('layouts.app')
@section('head')
    <title>Gerenciar Certificados</title>
@endsection
@section('content')
    <div class="container-fluid"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089;">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Gerenciar Certificados
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row"> {{-- Linha com o nome e botão novo --}}
                            <div class="col-md-6 col-12">
                                <input class="form-control" type="text" value="{{ $funcionario[0]->nome_completo }}"
                                    id="iddt_inicio" name="dt_inicio" required="required" disabled>
                            </div>
                            <div class="col-md-3 offset-md-3 col-12 mt-4 mt-md-0"> {{-- Botão de incluir --}}
                                <a href="/incluir-certificados/{{ $funcionario[0]->id }}" class="col-6">
                                    <button type="button" style="font-size: 1rem; box-shadow: 1px 2px 5px #000000;" class="btn btn-success col-md-8 col-12">
                                        Novo+
                                    </button>
                                </a>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <div class="table">
                            <table class="table table-striped table-bordered border-secondary table-hover align-middle">
                                <thead style="text-align: center;">
                                    <tr class="align-middle"
                                        style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                        <th class="col">Nivel Ensino</th>
                                        <th class="col-4">Nome</th>
                                        <th class="col">Etapa</th>
                                        <th class="col">Grau Academico</th>
                                        <th class="col">Entidade/Autor</th>
                                        <th class="col">Dt Conlusão</th>
                                        <th class="col">Ações</th>

                                    </tr>
                                </thead>
                                <tbody style="font-size: 15px; color:#000000;">
                                    @foreach ($certificados as $certificado)
                                        <tr>
                                            <td scope="" style="text-align: center">
                                                {{ $certificado->nome_tpne }}
                                            </td>
                                            <td scope="">
                                                {{ $certificado->nome }}
                                            </td>
                                            <td scope="" style="text-align: center">
                                                {{ $certificado->nome_tpee }}
                                            </td>
                                            <td scope="" style="text-align: center">
                                                {{ $certificado->nome_grauacad }}
                                            </td>
                                            <td scope="" style="text-align: center">
                                                {{ $certificado->nome_tpentensino }}
                                            </td>
                                            <td scope="" style="text-align: center">
                                                {{ \Carbon\Carbon::parse($certificado->dt_conclusao)->format('d/m/Y') }}
                                            </td>
                                            <td scope="" style="font-size: 1rem; color:#303030; text-align: center">

                                                <!--Botao de Editar-->
                                                <a href="/editar-certificado/{{ $certificado->id }}"
                                                    class="btn btn-outline-warning" data-tt="tooltip"
                                                    style="font-size: 1rem; color:#303030" data-placement="top"
                                                    title="Editar">
                                                    <i class="bi bi-pencil">
                                                    </i>
                                                </a>

                                                <!-- Botao de excluir, trigger modal -->
                                                <a>
                                                    <button type="button" class="btn btn-outline-danger delete-btn"
                                                        style="font-size: 1rem; color:#303030" data-bs-toggle="modal"
                                                        data-bs-target="#A{{ $certificado->id }}">
                                                        <i class="bi bi-trash">
                                                        </i>
                                                    </button>
                                                </a>

                                                <!--Modal-->
                                                <div class="modal fade" id="A{{ $certificado->id }}" tabindex="-1"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <div class="row">
                                                                    <h2>Excluir Certificado</h2>
                                                                </div>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="fw-bold alert alert-danger text-center">Você
                                                                    realmente deseja
                                                                    <br>
                                                                    <span class="fw-bolder fs-5">EXCLUIR
                                                                        {{ $certificado->nome }}</span>
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                <a href="/deletar-certificado/{{ $certificado->id }}"><button
                                                                        type="button"
                                                                        class="btn btn-danger">Excluir</button></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row d-flex justify-content-around">
                <div class="col-4">
                    <a href="javascript:history.back()">
                        <button class="btn btn-primary" style="width: 100%">Retornar </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
