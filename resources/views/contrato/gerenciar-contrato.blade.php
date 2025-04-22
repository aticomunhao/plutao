@extends('layouts.app')

@section('head')
    <title>Gerenciar Contratos</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card border-primary">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="text-primary m-0">Gerenciar Contratos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 col-12 mb-3 mb-md-0">
                                <input class="form-control" type="text" value="{{ $funcionario->nome_completo }}"
                                    disabled>
                            </div>
                            <div class="col-md-3 offset-md-3 col-12">
                                <a href="/incluir-contrato/{{ $funcionario->id }}" class="d-block">
                                    <button type="button" class="btn btn-success w-100 shadow-sm">Novo+</button>
                                </a>
                            </div>
                        </div>

                        <hr />

                        <div class="table-responsive">
                            <table
                                class="table table-striped table-bordered border-secondary table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr class="text-dark" style="font-size: 17px;">
                                        <th class="col">Tipo de Contrato</th>
                                        <th class="col">Admissão</th>
                                        <th class="col">Matrícula</th>
                                        <th class="col">Demissão</th>
                                        @if (collect($contrato)->contains('tp_contrato', 5))
                                            <th class="col">Previsão de Fim</th>
                                        @endif
                                        <th class="col">Motivo Desligamento</th>
                                        <th class="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contrato as $contratos)
                                        <tr>
                                            <td>{{ $contratos->nome }}</td>
                                            <td>{{ \Carbon\Carbon::parse($contratos->dt_inicio)->format('d/m/Y') }}</td>
                                            <td>{{ $contratos->matricula }}</td>
                                            <td>
                                                {{ $contratos->dt_fim ? \Carbon\Carbon::parse($contratos->dt_fim)->format('d/m/Y') : 'Em vigor' }}
                                            </td>
                                            @if (collect($contrato)->contains('tp_contrato', 5))
                                                <td>{{ $contratos->data_fim_prevista ? \Carbon\Carbon::parse($contratos->data_fim_prevista)->format('d/m/Y') : '-' }}
                                                </td>
                                            @endif
                                            <td>{{ $contratos->motivo ?? '-' }}</td>
                                            <td>
                                                <a href="#" class="btn btn-outline-secondary" data-bs-toggle="tooltip"
                                                    title="Visualizar">
                                                    <i class="bi bi-archive"></i>
                                                </a>

                                                <a href="/editar-contrato/{{ $contratos->id }}"
                                                    class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <button class="btn btn-outline-danger" data-bs-toggle="modal"
                                                    data-bs-target="#modalExcluir{{ $contratos->id }}" title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                @if (!$contratos->dt_fim)
                                                    <button type="button" class="btn btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalFinalizar{{ $contratos->id }}"
                                                        title="Finalizar contrato">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                    </button>
                                                @endif

                                                <!-- Modal Finalizar Contrato (Lógica Original Mantida) -->
                                                <div class="modal fade" id="modalFinalizar{{ $contratos->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <form method="POST"
                                                            action="/inativar-contrato/{{ $contratos->id }}">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title">Finalizar Contrato</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Motivo da
                                                                            Finalização:</label>
                                                                        <select class="form-select" name="motivo_inativar"
                                                                            required>
                                                                            @foreach ($situacao as $motivo)
                                                                                <option value="{{ $motivo->id }}">
                                                                                    {{ $motivo->motivo }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Data da
                                                                            Finalização:</label>
                                                                        <input type="date" class="form-control"
                                                                            name="dt_fim_inativacao" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Confirmar</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <!-- Modal Excluir Contrato (Lógica Original Mantida) -->
                                                <div class="modal fade" id="modalExcluir{{ $contratos->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title">Confirmar Exclusão</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-center fw-bold">Deseja realmente excluir o
                                                                    contrato:<br>
                                                                    <span
                                                                        class="text-danger">"{{ $contratos->nome }}"</span>?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a href="/excluir-contrato/{{ $contratos->id }}">
                                                                    <button type="button" class="btn btn-danger">Excluir
                                                                        Permanentemente</button>
                                                                </a>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancelar</button>
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

                        <div class="row mt-4">
                            <div class="col-md-4 mx-auto">
                                <a href="{{ route('gerenciar') }}" class="d-block">
                                    <button class="btn btn-danger w-100">Retornar</button>
                                </a>
                            </div>
                        </div>

                        <script>
                            // Tooltips (Ajuste de Estilo)
                            document.addEventListener('DOMContentLoaded', function() {
                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                                    return new bootstrap.Tooltip(tooltipTriggerEl)
                                })
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
