@extends('layouts.app')


@section('content')
    <div class="container-xxl">
        <div class="col-12">
            <div class="row justify-content-center">
                <div>
                    <form action="{{ route('gerenciar') }}" class="form-horizontal mt-4" method="GET">
                        <div class="row">
                            <div class="col-2">CPF
                                <input class="form-control" type="numeric" name="cpf" value="">
                            </div>
                            <div class="col-4">Nome
                                <input class="form-control" type="text" name="nome" value="">
                            </div>
                            <div class="col-1 form-check form-switch"><br>

                            </div>
                            <!--<input type="checkbox" id="checkAdq" name="checkAdq" switch="bool" />
                            <label for="checkAdq" data-on-label="Sim" data-off-label="Não"></label>-->
                            <div class="col-3"><br>
                                <input class="btn btn-info btn-sm" type="submit" value="Pesquisar">
                                <a href="/"><input class="btn btn-danger btn-sm" type="button" value="Cancelar"></a>
                    </form>
                    <a href="/incluir-voluntario"><input class="btn btn-success btn-sm" type="button"
                            value="Novo Cadastro"></a>

                </div>
            </div>
            <br>
            <br>
        </div>
        <hr>
        <div>
            <table class="table table-striped table-bordered border-dark col-3">
                <thead style="text-align: center;">
                    <tr style="background-color: #CBE4D6;">
                        <th scope="col">ID</th>
                        <th scope="col">Cidade</th>
                        <th scope="col">Grupo</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px;">
                    <tr>
                        <td scope="2">Grupo</td>
                        <td scope="2">Botões</td>
                        @foreach ($lista as $listas)
                            <td scope="2">{{ $listas->id_cidade }}</td>
                            <td scope="2">{{ $listas->descricao }}</td>
                            <td scope="2">Grupo</td>
                            <td scope="2">Botões</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    </div>
    </div>
@endsection

@section('footerScript')
    <script src="{{ URL::asset('/js/pages/mascaras.init.js') }}"></script>
    <script src="{{ URL::asset('/js/pages/form-advanced.init.js') }}"></script>

@endsection
