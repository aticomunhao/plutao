@extends('layouts.app')

@section('head')
    <title>Gerenciar Funcionários</title>
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
                                Gerenciar Funcionários
                            </h5>
                        </div>
                    </div>
                    <div>
                        <div class="card-body">
                            <form action="{{ route('gerenciar') }}" class="form-horizontal mt-2" method="GET">
                                <div class="row justify-content-md-center">
                                    <div class="col-md-2 col-sm-12">CPF
                                        <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                            maxlength="11" type="text" id="1" name="cpf"
                                            value="{{ $cpf }}">
                                    </div>
                                    <div class="col-md-2 col-sm-12">Identidade
                                        <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                            maxlength="9" type="text" id="2" name="idt"
                                            value="{{ $idt }}">
                                    </div>
                                    <div class="col-md-2 col-sm-12">Nome
                                        <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                            maxlength="50" type="text" id="3" name="nome"
                                            value="{{ $nome }}">
                                    </div>
                                    <div class="col-md-auto col-sm-12">
                                        <label for="1">Setor</label>
                                        <select id="idsetor" class="form-select select2" name="setor">
                                            <option></option>
                                            @foreach ($setor as $setores)                                                    
                                                    <option @if (request('setor') == $setores->id) {{ 'selected="selected"' }} @endif
                                                    value="{{ $setores->id }}">{{ $setores->sigla }}</option>                        
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-12">Situação
                                        <select class="form-select custom-select"
                                            style="border: 1px solid #999999; padding: 5px;" id="4" name="status"
                                            type="number">
                                            <option value="0" {{ 0 == request('status') ? 'selected' : '' }}>Todos
                                            </option>
                                            <option value="3" {{ 3 == request('status') ? 'selected' : '' }}>Ativos
                                            </option>
                                            <option value="1" {{ 1 == request('status') ? 'selected' : '' }}>Inativos
                                            </option>
                                            <option value="2" {{ 2 == request('status') ? 'selected' : '' }}>Sem
                                                contrato</option>
                                        </select>
                                    </div>
                                    <div class="col" style="margin-top: 20px">
                                        <input class="btn btn-light btn-sm"
                                            style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; margin:5px;"
                                            type="submit" value="Pesquisar">
                                        <a href="/gerenciar-funcionario" class="btn btn-light btn-sm"
                                            style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; margin:5px;"
                                            type="button" value="">
                                            Limpar
                                        </a>
                                        <a href="/informar-dados">
                                            <input class="btn btn-success btn-sm" type="button" name="6"
                                                value="Novo+"
                                                style="font-size: 1rem; box-shadow: 1px 2px 5px #000000; margin:5px;">
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div>
                    <h6 style="margin-top: 10px;">
                        Ativos: <input type="text" value="{{ $totativo }}" style="width: 5%; text-align:center;"
                            disabled>
                        Inativos: <input type="text" value="{{ $totinativo }}" style="width: 5%; text-align:center;"
                            disabled>
                        Sem contrato: <input type="text" value="{{ $totscontr }}"
                            style="width: 5%; text-align:center;" disabled>
                        Total: <input type="text" value="{{ $totfunc }}" style="width: 5%; text-align:center;"
                            disabled>
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                        <thead style="text-align: center;">
                            <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                <th class="col-1">CPF</th>
                                <th class="col-1">Identidade</th>
                                <th class="col-4">Nome</th>
                                <th class="col">Setor</th>
                                <th class="col">Situação</th>
                                <th class="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 14px; color:#000000;">
                            @foreach ($lista as $listas)
                                <tr>
                                    <td scope="">{{ $listas->cpf }}</td>
                                    <td scope="">{{ $listas->idt }}</td>
                                    <td class="nome-item" data-nome-completo="{{ $listas->nome_completo }}"
                                        data-nome-resumido="{{ $listas->nome_resumido }}">
                                        {{ $listas->nome_completo }}
                                    </td>
                                    <td scope="" style="text-align: center;">{{ $listas->sigla_completa }}</td>
                                    <td scope="" style="text-align: center;">{{ $listas->status_funcionario }}</td>
                                    <td scope="" style="text-align: center">
                                        <a href="/editar-funcionario/{{ $listas->idp }}" type="button"
                                            class="btn btn-outline-warning" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Editar"><i class="bi-pencil"
                                                style="font-size: 1rem; color:#303030;"></i>
                                        </a>
                                        <a href="/visualizar-funcionario/{{ $listas->idp }}/ {{ $listas->idf }}"
                                            type="button" class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Visualizar"><i class="bi-search"
                                                style="font-size: 1rem; color:#303030;"></i>
                                        </a>
                                        <a href="/gerenciar-dependentes/{{ $listas->idf }}" type="button"
                                            class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-placement="top" title="Dependentes"><i
                                                class="bi-people-fill" style="font-size: 1rem;color:#303030; "></i>
                                        </a>
                                        <a href="{{ route('index.dadosbancarios.funcionario', ['id' => $listas->idf]) }}"
                                            type="button" class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Dados Bancários"><i class="bi bi-bank"
                                                style="font-size: 1rem; color:#303030;"></i>
                                        </a>
                                        <a href="/gerenciar-afastamentos/{{ $listas->idf }}" type="button"
                                            class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Afastamento"><i class="bi-bandaid"
                                                style="font-size: 1rem;color:#303030;"></i>
                                        </a>
                                        <a href="/gerenciar-certificados/{{ $listas->idf }}" type="button"
                                            class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Certificados"><i class="bi-mortarboard"
                                                style="font-size: 1rem; color:#303030;"></i>
                                        </a>
                                        <a href="/gerenciar-contrato/{{ $listas->idf }}" type="button"
                                            class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Contratos"><i class="bi bi-pencil-square"
                                                style="font-size: 1rem; color:#303030;"></i>
                                        </a>
                                        <a href="/gerenciar-base-salarial/{{ $listas->idf }}" type="button"
                                            class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Base Salarial"><i
                                                class="bi bi-currency-dollar" style="font-size: 1rem; color:#303030;"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#A{{ $listas->cpf }}-{{ $listas->idp }}"
                                            class="btn btn-outline-danger btn-sm" data-bs-placement="top"
                                            title="Excluir"><i class="bi-trash"
                                                style="font-size: 1rem; color:#303030;"></i></button>


                                        <!-- Modal -->
                                        <div class="modal fade" id="A{{ $listas->cpf }}-{{ $listas->idp }}"
                                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color:#DC4C64;">
                                                        <h5 class="modal-title" id="exampleModalLabel"
                                                            style=" color:rgb(255, 255, 255)">Excluir
                                                            Funcionário
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" style="text-align: center">
                                                        Você realmente deseja excluir <br><span
                                                            style="color:#DC4C64; font-weight: bold">{{ $listas->nome_completo }}</span>
                                                        ?

                                                    </div>
                                                    <div class="modal-footer mt-2">
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <a type="button" class="btn btn-primary"
                                                            href="/excluir-funcionario/{{ $listas->idp }}">Confirmar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Fim Modal-->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="margin-right: 10px; margin-left: 10px">
                {{ $lista->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    </div>
    </div>


    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

    <style>
        .highlight {
            color: red;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const nomeItems = document.querySelectorAll('.nome-item');

            nomeItems.forEach(item => {
                const nomeCompleto = item.getAttribute('data-nome-completo');
                const nomeResumido = item.getAttribute('data-nome-resumido');

                if (nomeCompleto.includes(nomeResumido)) {
                    const partes = nomeCompleto.split(nomeResumido);
                    item.innerHTML = partes.join(`<span class="highlight">${nomeResumido}</span>`);
                }
            });
        });
    </script>
@endsection
