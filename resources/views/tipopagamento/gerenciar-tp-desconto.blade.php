@extends('layouts.app')
@section('head')
    <title>Gerenciar Tipo de Desconto</title>
@endsection
@section('content')
    
    <div class="container-fluid">
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089;">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Gerenciar Tipo de Desconto
                            </h5>
                        </div>
                    </div>
                    <br>
                    <div class="card-body">
                        <form method="GET" action="/gerenciar-tipo-desconto">
                            @csrf
                            <div class="row justify-content-start">
                                <div class="col-md-4 col-sm-12">
                                    <input type="text" class="form-control" style="border: 1px solid #999999; margin-top: 5px" aria-label="Sizing example input"
                                        name="pesquisa"{{-- Input de pesquisa --}} value= "{{ $pesquisa }}" maxlength="40">
                                </div>
                                <div class="col-md-8 col-12">
                                    <a href="/gerenciar-tipo-desconto" type="button" class="btn btn-light btn-sm"
                                        style="box-shadow: 1px 2px 5px #000000; margin-left: 2px; font-size: 1rem"
                                        value="">Limpar</a>
                                    <button class="btn btn-light btn-sm " style="font-size: 1rem; margin-left: 2px; box-shadow: 1px 2px 5px #000000; margin:5px;"{{-- Botao submit do formulario de pesquisa --}}
                                        type="submit">Pesquisar</button>

                                    <a href="/incluir-tipo-desconto" style="font-size: 1rem; box-shadow: 1px 2px 5px #000000;"{{-- Botao com rota para incluir tipo de desconto --}}
                                    class="btn btn-success offset-md-8">
                                        Novo+
                                    </a>
                                </div>
                            </div>
                        </form>
                        <br />
                        <hr>
                        <table
                            class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                            {{-- Inicio da tabela de informacoes --}}
                            <thead style="text-align: center; ">
                                <tr style="background-color: #d6e3ff; font-size:19px; color:#000;">
                                    <th>Tipo de desconto</th>
                                    <th>Desconto</th>
                                    <th>Data Inicial</th>
                                    <th>Data Final</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 15px; color:#000000;">
                                @foreach ($desc as $descrpt)
                                    <tr>
                                        <td>{{ $descrpt->description }}</td>{{-- Adiciona o tipo de desconto a tabela --}}
                                        <td style="text-align:center;">{{ $descrpt->percDesconto }}%</td>
                                        {{-- Adiciona a porcentagem do desconto  --}}
                                        <td style="text-align:center;">{{ $descrpt->dt_inicio }}</td>{{-- Adiciona a data de inicio --}}
                                        <td style="text-align:center;">
                                            {{ !is_null($descrpt->dt_final) ? date('d-m-Y', strtotime($descrpt->dt_final)) : '--' }}
                                        </td>{{-- Adiciona a data final  --}}
                                        <td style="text-align: center;">
                                            @if ($descrpt->dt_final == null)
                                                {{-- Apenas permite dados ativos a se atualizarem ao mostrar o botao apenas neles --}}
                                                <a href="/renovar-tipo-desconto/{{ $descrpt->id }}"
                                                    class="btn btn-outline-primary" data-tt="tooltip" data-placement="top"
                                                    title="Atualizar">
                                                    <i class="bi bi-arrow-clockwise"></i></i>
                                                </a>
                                            @endif
                                            <a href="/editar-tipo-desconto/{{ $descrpt->id }}"
                                                class="btn btn-outline-warning" data-tt="tooltip" data-placement="top"
                                                title="Editar">{{-- Botao com rota para editar tipo de desconto --}}
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger"
                                                data-bs-toggle="modal"{{-- Botao com rota para a modal de exclusao --}}
                                                data-bs-target="#exampleModal{{ $descrpt->id }}" data-tt="tooltip"
                                                data-placement="top" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="exampleModal{{ $descrpt->id }}"{{-- inicio da modal --}}
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5 text-danger" id="exampleModalLabel">Excluir
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body ">
                                                    Tem certeza que deseja excluir este arquivo?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <a href="/exluir-tipo-desconto/{{ $descrpt->id }}"{{-- Botao de exclusao --}}
                                                        class="btn btn-danger">Excluir Permanentemente</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>{{-- Fim da modal --}}
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
        @endsection
