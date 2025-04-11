@extends('layouts.app')

@section('title')
    Alterar usuário
@endsection

@section('content')
    <br>
    <div class="container">
        <form class="form-horizontal mt-4" method="POST" action="/usuario-atualizar/{{ $resultUsuario[0]->id }}">
            @csrf
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card m-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    GERENCIAR USUÁRIO
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <p>NOME:<strong> {{ $result[0]->nome_completo }}</strong></p>
                                        <p>IDENTIDADE:<strong> {{ $result[0]->idt }}</strong> </p>
                                    </div>
                                    <div class="col">
                                        <p>CPF: <strong> {{ $result[0]->cpf }}</strong> </p>
                                        <p>DATA NASCIMENTO:<strong>
                                                {{ date('d-m-Y', strtotime($result[0]->dt_nascimento)) }}</strong> </p>
                                    </div>
                                    <div class="col">
                                        <p>EMAIL: <strong> {{ $result[0]->email }}</strong> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card m-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    SELECIONAR PERFIS
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <input type="hidden" name="idPessoa" value="{{ $result[0]->id }}">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <tr>
                                        <td style="text-align:right;">Ativo</td>
                                        <td>
                                            <input id="ativo" type="checkbox" name="ativo" data-size="small"
                                                data-size="small" data-toggle="toggle" data-onstyle="success"
                                                data-offstyle="danger" data-onlabel="Sim" data-offlabel="Não"
                                                {{ $resultUsuario[0]->ativo ? 'checked' : '' }}>
                                            <label for="ativo" class="form-check-label"></label>
                                        </td>
                                        <td style="text-align:right;">Bloqueado</td>
                                        <td>
                                            <input id="bloqueado" type="checkbox" name="bloqueado" data-size="small"
                                                data-size="small" data-toggle="toggle" data-onstyle="success"
                                                data-offstyle="danger" data-onlabel="Sim" data-offlabel="Não"
                                                {{ $resultUsuario[0]->bloqueado ? 'checked' : '' }}>
                                            <label for="bloqueado" class="form-check-label"></label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">

                                    @foreach ($resultPerfil as $resultPerfils)
                                        <tr>
                                            <td>
                                                {{ $resultPerfils->nome }}
                                            </td>
                                            <td>

                                                <input id="{{ $resultPerfils->nome }}" type="checkbox"
                                                    name="{{ $resultPerfils->nome }}" value="{{ $resultPerfils->id }}"
                                                    data-size="small" data-size="small" data-toggle="toggle"
                                                    data-onstyle="success" data-offstyle="danger" data-onlabel="Sim"
                                                    data-offlabel="Não"
                                                    {{ in_array($resultPerfils->id, $resultPerfisUsuarioArray) ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    {{--  <div class="card m-1">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                SELECIONAR ESTOQUE
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0">
                                            @foreach ($resultDeposito as $resultDepositos)
                                                <tr>
                                                    <td>
                                                        {{ $resultDepositos->nome }}
                                                    </td>
                                                    <td>
                                                        <input id="{{ $resultDepositos->nome }}" type="checkbox"
                                                            name="{{ $resultDepositos->nome }}"
                                                            value="{{ $resultDepositos->id }}" data-size="small"
                                                            data-size="small" data-toggle="toggle" data-onstyle="success"
                                                            data-offstyle="danger" data-onlabel="Sim" data-offlabel="Não">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  --}}
                    <div class="card m-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    SETOR
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating">
                                            <input class="form-control" type="text" id="nome_pesquisa"
                                                name="nome_pesquisa">
                                            <label for="floatingTextarea">Pesquisa de Setor</label>
                                        </div>
                                        <br />
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped mb-0" id="myTable"
                                                name="myTable">
                                                @foreach ($resultSetor as $resultSetors)
                                                    <tr>
                                                        <td>
                                                            {{ $resultSetors->nome }}
                                                        </td>
                                                        <td>

                                                            <input id="{{ $resultSetors->nome }}" type="checkbox"
                                                                name="{{ $resultSetors->nome }}"
                                                                value="{{ $resultSetors->id }}" data-size="small"
                                                                data-size="small" data-toggle="toggle"
                                                                data-onstyle="success" data-offstyle="danger"
                                                                data-onlabel="Sim" data-offlabel="Não"
                                                                {{ in_array($resultSetors->id_setor, $resultSetorUsuarioArray) ? 'checked' : '' }}>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <center>
                                    <div class="row">
                                        <div class="col">
                                            <a href="/gerenciar-usuario ">
                                                <input class="btn btn-danger btn-block col-3" type="button"
                                                    value="Cancelar">
                                            </a>
                                        </div>
                                        <div class="col ">
                                            <button type="submit"
                                                class="btn btn-primary btn-block col-3">Cadastrar</button>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </div>
                        {{-- <div style="margin-right: 10px; margin-left: 10px">
                            {{ $resultSetor->appends(request()->input())->links('pagination::bootstrap-5') }}
                        </div> --}}
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
