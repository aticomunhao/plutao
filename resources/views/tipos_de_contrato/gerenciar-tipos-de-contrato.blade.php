@extends('layouts.app')
@section('head')
    <title>Gerenciar Tipos de Contrato</title>
@endsection
@section('content')
    
    <div class="container"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089;">
                    <div class="card-header">
                        <div class="row justify-content-between">
                            <h5 class="col-md-4 col-sm-12" style="color: #355089">
                                Gerenciar Tipos de Contrato
                            </h5>
                            <div class="col-md-3 col-sm-12">
                                <a href="{{ route('create.tipos-de-contrato') }}"{{-- Botao com rota para incluir entidades --}}
                                    class="btn btn-success  " style="box-shadow: 1px 2px 5px #000000;width:100%">
                                    Novo+
                                </a>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card-body">



                    <table class="table  table-striped table-bordered border-secondary table-hover align-middle">
                        {{-- Tabela com todas as informacoes --}}
                        <thead style="text-align: center; ">
                            <tr style="background-color: #d6e3ff; font-size:17px; color:#000;">
                                <th>Entidades de ensino</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 15px; color:#000000;">
                            @foreach ($tipos_de_contrato as $tipo_de_contrato)
                                {{-- Foreach da tabela com os dados --}}
                                <tr style="text-align: center">
                                    <td>{{ $tipo_de_contrato->nome }}</td> {{-- Variavel que tras os nomes das entidades de ensino --}}
                                    <td>
                                        <a href="{{ route('edit.tipos-de-contrato', ['id' => $tipo_de_contrato->id]) }}"><button
                                                type="submit" class="btn btn-outline-warning"{{-- Botao de editar --}}
                                                data-tt="tooltip" data-placement="top" title="Editar"
                                                style="font-size: 1rem; color:#303030;"><i
                                                    class="bi bi-pencil sm"></i></button></a>
                                        <button type="button"
                                            class="btn btn-outline-danger delete-btn"{{-- Botao de excluir que aciona o modal --}}
                                            data-bs-toggle="modal" data-bs-target="#A{{ $tipo_de_contrato->id }}"
                                            data-tt="tooltip" data-placement="top" title="Excluir"
                                            style="font-size: 1rem; color:#303030;"><i class="bi bi-trash"></i></button>

                                        <div class="modal fade" id="A{{ $tipo_de_contrato->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color:#DC4C64;">
                                                        <h5 class="modal-title" id="exampleModalLabel"
                                                            style=" color:rgb(255, 255, 255)">Excluir Tipo De Contrato
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Você realmente deseja excluir <br><span
                                                            style="color:#DC4C64; font-weight: bold">{{ $tipo_de_contrato->nome }}</span>
                                                        ?

                                                    </div>
                                                    <div class="modal-footer mt-2">
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <a type="button" class="btn btn-primary"
                                                            href="{{ route('destroy.tipos-de-contrato', ['id' => $tipo_de_contrato->id]) }}">Confirmar
                                                        </a>
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

        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tt="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>
    </div>
    </div>
@endsection
