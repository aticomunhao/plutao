@extends('layouts.app')
@section('head')
    <title>Incluir Dependentes</title>
@endsection
@section('content')
    <form method="post" action="/armazenar-dependentes/{{ $funcionario_atual[0]->id }}">
        @csrf
        <div class="container-fluid"> {{-- Container completo da página  --}}
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Incluir Dependentes
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-5">
                                    <input class="form-control" type="text"
                                        value="{{ $funcionario_atual[0]->nome_completo }}" id="iddt_inicio" name="dt_inicio"
                                        required="required" disabled>
                                </div>
                            </div>
                            <br />
                            <hr>
                            <div class="form-group row">
                                <div class="col-md-2 mt-1 mt-md-0">Parentesco
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        id="4" name="relacao_dep" required="required">
                                        @foreach ($tp_relacao as $tp_relacaos)
                                            <option value="{{ $tp_relacaos->id }}">{{ $tp_relacaos->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 mt-3 mt-md-0">Nome completo
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="40" id="2"
                                        oninput="this.value = this.value.replace(/[0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        name="nomecomp_dep" value="" required="required">
                                </div>
                                <div class="col-2 mt-3 mt-md-0">Data nascimento
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="" id="3" name="dtnasc_dep" required="required">
                                </div>
                                <div class="col-3 mt-3 mt-md-0">CPF
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="text" maxlength="14" value="" id="cpf" name="cpf_dep"
                                        required="required" oninput="formatarCPF(this); validarCPF(this);">
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div>
            <a class="btn btn-danger col-md-3 col-2 mt-4 offset-md-1"
                href="/gerenciar-dependentes/{{ $funcionario_atual[0]->id }}" role="button">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-3" id="sucesso">
                Confirmar
            </button>
        </div>
    </form>


    <script>
        function formatarCPF(input) {
            // Remover caracteres não numéricos
            let cpf = input.value.replace(/\D/g, '');

            // Adicionar pontos e traço conforme formatação do CPF
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

            // Atualizar o valor do campo de entrada
            input.value = cpf;
        }

        function validarCPF(input) {
            // Remover caracteres não numéricos
            let cpf = input.value.replace(/\D/g, '');

            // Verificar se o CPF tem 11 dígitos
            if (cpf.length !== 11) {
                input.setCustomValidity('CPF inválido');
                return;
            }

            // Calcular os dígitos verificadores
            let soma = 0;
            for (let i = 0; i < 9; i++) {
                soma += parseInt(cpf.charAt(i)) * (10 - i);
            }
            let resto = soma % 11;
            let digito1 = resto < 2 ? 0 : 11 - resto;

            soma = 0;
            for (let i = 0; i < 10; i++) {
                soma += parseInt(cpf.charAt(i)) * (11 - i);
            }
            resto = soma % 11;
            let digito2 = resto < 2 ? 0 : 11 - resto;

            // Verificar se os dígitos verificadores calculados correspondem aos fornecidos
            if (parseInt(cpf.charAt(9)) !== digito1 || parseInt(cpf.charAt(10)) !== digito2) {
                input.setCustomValidity('CPF inválido');
                return;
            }

            // CPF válido
            input.setCustomValidity('');
        }
    </script>
@endsection
