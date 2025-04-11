@extends('layouts.app')
@section('head')
    <title>Gerenciar Entidade de Ensino</title>
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
                                Gerenciar Entidade de Ensino
                            </h5>
                        </div>
                    </div>
                    <br>
                    <div class="card-body">
                        <form method="GET" action="/gerenciar-entidades-de-ensino">{{-- Formulario para o botao e input de pesquisa --}}
                            @csrf
                            <div class="row justify-content-start">
                                <div class="col-md-4 col-sm-12">
                                    <input type="text" style="border: 1px solid #999999; margin-top: 5px" class="form-control" aria-label="Sizing example input"
                                        name="pesquisa" value= "{{ $pesquisa }}" maxlength="40">{{-- Input de pesquisa --}}
                                </div>
                                <div class="col-md-8 col-12">
                                    <a href="/gerenciar-entidades-de-ensino" type="button" class="btn btn-light btn-sm"
                                        style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; margin:5px;"
                                        value="">Limpar</a>{{-- Botao Limpar --}}
                                    <button class="btn btn-light btn-sm "
                                        style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; margin:5px;"{{-- Botao submit do formulario de pesquisa --}}
                                        type="submit">Pesquisar
                                    </button>{{-- Botao submit da pesquisa --}}
                                    <a href="/incluir-entidades-ensino"{{-- Botao com rota para incluir entidades --}}
                                        class="btn btn-success  offset-md-8" style="box-shadow: 1px 2px 5px #000000;">
                                        Novo+
                                    </a>
                                </div>
                            </div>
                        </form>
                        <br>
                        <hr>
                        <table class="table  table-striped table-bordered border-secondary table-hover align-middle">
                            {{-- Tabela com todas as informacoes --}}
                            <thead style="text-align: center; ">
                                <tr style="background-color: #d6e3ff; font-size:17px; color:#000;">
                                    <th>Entidades de ensino</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 15px; color:#000000;">
                                @foreach ($entidades as $entidade)
                                    {{-- Foreach da tabela com os dados --}}
                                    <tr style="text-align: center">
                                        <td>{{ $entidade->nome_tpentensino }}</td> {{-- Variavel que tras os nomes das entidades de ensino --}}
                                        <td>
                                            <a href="/editar-entidade/{{ $entidade->id }}"><button type="submit"
                                                    class="btn btn-outline-warning"{{-- Botao de editar --}} data-tt="tooltip"
                                                    data-placement="top" title="Editar" style="font-size: 1rem; color:#303030;"><i
                                                        class="bi bi-pencil sm"></i></button></a>
                                            <button type="button"
                                                class="btn btn-outline-danger delete-btn"{{-- Botao de excluir que aciona o modal --}}
                                                data-bs-toggle="modal" data-bs-target="#A{{ $entidade->id }}"
                                                data-tt="tooltip" data-placement="top" title="Excluir" style="font-size: 1rem; color:#303030;"><i
                                                    class="bi bi-trash"></i></button>

                                            <div class="modal fade" id="A{{ $entidade->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="background-color:#DC4C64;">
                                                            <h5 class="modal-title" id="exampleModalLabel" style=" color:rgb(255, 255, 255)">Excluir Entidade de Ensino</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Você realmente deseja excluir <br><span
                                                                style="color:#DC4C64; font-weight: bold">{{ $entidade->nome_tpentensino }}</span> ?

                                                        </div>
                                                        <div class="modal-footer mt-2">
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <a type="button" class="btn btn-primary"
                                                            href="/excluir-entidade/{{ $entidade->id }}">Confirmar
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
