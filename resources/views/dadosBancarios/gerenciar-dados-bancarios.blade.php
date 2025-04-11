@extends('layouts.app')
@section('head')
    <title>Gerenciar Dados Bancarios</title>
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
                                Gerenciar Dados Bancários
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row"> {{-- Linha com o nome e botão novo --}}
                            <div class="col-md-6 col-12">
                                <input class="form-control" type="text" value="{{ $funcionario->nome_completo }}"
                                    id="iddt_inicio" name="dt_inicio" required="required" disabled>
                            </div>
                            <div class="col-md-3 offset-md-3 col-12 mt-4 mt-md-0"> {{-- Botão de incluir --}}
                                <a href="{{ route('create.dadosbancarios.funcionario', ['id' => $funcionario->id]) }}"
                                    class="col-6">
                                    <button type="button" style="font-size: 1rem; box-shadow: 1px 2px 5px #000000;"
                                        class="btn btn-success col-md-8 col-12">
                                        Novo+
                                    </button>
                                </a>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <div class="container-fluid table-responsive"> {{-- Faz com que a tabela não grude nas bordas --}}
                            <div class="table">
                                <table class="table table-striped table-bordered border-secondary table-hover align-middle">
                                    <thead style="text-align: center;"> {{-- Text-align gera responsividade abaixo de Large --}}
                                        <tr class="align-middle"
                                            style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                            <th class="col">Banco</th>
                                            <th class="col">Agencia</th>
                                            <th class="col">Conta</th>
                                            <th class="col">Tipo</th>
                                            <th class="col">Subtipo</th>
                                            <th class="col">Dt inicio</th>
                                            <th class="col">Dt Fim</th>
                                            <th class="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 15px; color:#000000;">
                                        @foreach ($contasBancarias as $contaBancaria)
                                            <tr>
                                                <td class="text-center">{{ $contaBancaria->nome }}</td>
                                                <td class="text-center">
                                                    {{ str_pad($contaBancaria->agencia, 3, '0', STR_PAD_LEFT) }}</td>
                                                <td class="text-center">{{ $contaBancaria->nmr_conta }}</td>
                                                <td class="text-center">{{ $contaBancaria->nome_tipo_conta }}</td>
                                                <td class="text-center">{{ $contaBancaria->descricao }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($contaBancaria->dt_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($contaBancaria->dt_fim)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center"> {{--  Área de ações  --}}
                                                    <a href="{{ route('edit.dadosbancarios.funcionario', ['id' => $contaBancaria->id]) }}"
                                                        class="btn btn-outline-warning" data-tt="tooltip"
                                                        data-placement="top" title="Editar">{{--  Botão editar  --}}
                                                        <i class="bi bi-pencil"
                                                            style="font-size: 1rem; color:#303030"></i></a>
                                                    <button class="btn btn-outline-danger" {{-- Botão que aciona o modal  --}}
                                                        data-bs-toggle="modal" data-bs-target="#A{{ $contaBancaria->id }}"
                                                        data-tt="tooltip" data-placement="top" title="Excluir">
                                                        <i class="bi bi-trash"
                                                            style="font-size: 1rem; color:#303030"></i></button>
                                                    <div class="modal fade"{{--  Modal  --}}
                                                        id="A{{ $contaBancaria->id }}" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background-color:rgba(202, 61, 61, 0.911);">
                                                                    <div class="row">
                                                                        <h2 style="color:white;">Excluir Dependente</h2>
                                                                    </div>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body" style="color:#e24444;">
                                                                    <br />
                                                                    <p class="fw-bold alert  text-center">Você
                                                                        realmente deseja excluir
                                                                        <br>
                                                                        <span class="fw-bolder fs-5">
                                                                            {{ $contaBancaria->nmr_conta }}</span>
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer  ">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cancelar
                                                                    </button>
                                                                    <a
                                                                        href="/deletar-dado-bancario/{{ $contaBancaria->id }}">
                                                                        <button type="button"
                                                                            class="btn btn-danger">Excluir
                                                                            permanentemente
                                                                        </button>
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
            <script>
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tt="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            </script>
        @endsection
