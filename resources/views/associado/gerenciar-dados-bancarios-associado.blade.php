@extends('layouts.app')
@section('head')
    <title>Gerenciar Dados Bancários Associado</title>
@endsection
@section('content')
    
    @csrf
    <div class="container-fluid"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Gerenciar Dados Bancários Associado
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row"> {{-- Linha com o nom --}}
                            <div class="col-md-4 col-10">
                                <fieldset
                                    style="border: 1px solid #c0c0c0; border-radius: 3px; padding-bottom: 6px; text-align: center; background-color: #ebebeb; padding-top: 10px;">
                                    {{ $associado->nome_completo }}
                                </fieldset>

                            </div>
                            <div class="col-md-1 col-6">
                                <fieldset
                                    style="border: 1px solid #c0c0c0; border-radius: 3px; padding-bottom: 6px; text-align: center; background-color: #ebebeb; padding-top: 10px;">
                                    {{ $associado->nr_associado }}
                                </fieldset>
                            </div>
                            <div class="col-md-3 offset-md-4 col-12 mt-4 mt-md-0"> {{-- Botão de incluir --}}
                                <a href="/visualizar-dados-bancarios/{{ $associado->ida }}" class="col-6"><button
                                        type="button" class="btn btn-success col-md-8 col-12">+Novo Cadastro</button></a>
                            </div>
                        </div>
                    </div>
                    <hr />

                    <div class="row d-flex justify-content-around">
                        <div class="col-md-3 col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <center>
                                        <h5 class="card-title">Tesouraria</h5>
                                        <center>
                                            <hr>
                                            <div class="col-md-7 col-sm-12">
                                                <input style="text-align: center;" type="text" class="form-control"
                                                    name="" value="{{ ucwords(strtolower($tesouraria)) }}"
                                                    disabled="">
                                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <center>
                                        <h5 class="card-title">Boleto Bancário</h5>
                                        <center>
                                            <hr>
                                            <div class="col-md-5 col-sm-12">
                                                <input style="text-align: center;" type="text" class="form-control"
                                                    name="" value="{{ ucwords(strtolower($mes)) }}" disabled="">
                                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <center>
                                        <h5 class="card-title">Autorização de Débito</h5>
                                        <center>
                                            <hr>
                                            <div class="col-md-7 col-sm-12">
                                                <input style="text-align: center;" type="text" class="form-control"
                                                    name="" value="{{ ucwords(strtolower($banco)) }}" disabled="">
                                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <center>
                                        <h5 class="card-title">Ações</h5>

                                        <hr>
                                        <a href="/editar-dados-bancarios-associado/{{ $associado->ida }}"><button
                                                type="button" class="btn btn-outline-warning btn-sm"><i class="bi-pencil"
                                                    style="font-size: 1rem; color:#303030;"></i></button></a>
                                        <a href="/documento-bancario/{{ $associado->ida }}"><button type="button"
                                                class="btn btn-sm btn-outline-secondary"><i class="bi bi-archive"
                                                    style="font-size: 1rem; color:#303030;"></i></button></a>
                                        <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal"
                                            data-target="#modalExemplo{{ $associado->ida }}"><i
                                                class="bi bi-box-arrow-in-down"
                                                style="font-size: 1rem; color:#303030;"></i></button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                            data-target="#excluir"><i class="bi bi-trash"
                                                style="font-size: 1rem; color:#303030;"></i></button>

                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-start">
                        <div class="col-md-3 col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <center>
                                        <h5 class="card-title">Ultima Contribuição</h5>
                                        <center>
                                            <hr>
                                            <div class="col-md-4 col-sm-12">
                                                <input style="text-align: center;" type="text" class="form-control"
                                                    name="" value="{{ $associado->ultima_contribuicao }}"
                                                    disabled="">
                                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <center>
                                        <h5 class="card-title">Dia do Vencimento</h5>
                                        <center>
                                            <hr>
                                            <div class="col-md-5 col-sm-12">
                                                <input style="text-align: center;" type="text" class="form-control"
                                                    name="" value="{{ $associado->dt_vencimento }}"
                                                    disabled="">
                                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <center>
                                        <h5 class="card-title">Valor</h5>
                                        <center>
                                            <hr>
                                            <div class="col-md-4 col-sm-12">
                                                <input style="text-align: center;" type="text" class="form-control"
                                                    name="" value="R${{ $associado->valor }}" disabled="">
                                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>

                <!-- Modal -->
                <div class="modal fade bd-example-modal-lg" id="modalExemplo{{ $associado->ida }}" tabindex="-1"
                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Documento Autorização Débito em Conta</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <center>
                                                <h6>Arquivo Atual</h6>
                                                <a href="/visualizar-arquivo-bancario/{{ $associado->ida }}"><button
                                                        type="button" class="btn btn-outline-primary btn-sm"><i
                                                            class="bi-search"
                                                            style="font-size: 2rem; color:#303030;"></i></button></a>
                                            </center>
                                        </div>
                                        <div class="col-md-5">
                                            <form method='POST'
                                                action="/salvar-documento-bancario/{{ $associado->ida }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="exampleFormControlFile1">
                                                        <h6>Carregar Arquivo</h6>
                                                    </label>
                                                    <input type="file" class="form-control-file"
                                                        id="exampleFormControlFile1" name="arquivo">
                                                </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary">Salvar mudanças</button>
                            </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="modal fade" id="excluir" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Excluir Dados Bancários Associado</h5>
                                <button type="button" class="btn-close" data-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="fw-bold alert alert-danger text-center">Você realmente deseja excluir os Dados
                                    bancários do(a)</p>
                                <center>
                                    <h4>{{ $associado->nome_completo }}</h4>
                                </center>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                <a href="/excluir-dados-bancarios-associado/{{ $associado->ida }}"><button type="button"
                                        class="btn btn-primary">Confirmar</button></a>
                            </div>
                        </div>
                    </div>
                </div>



              
                
            @endsection
