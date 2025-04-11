@extends('layouts.app')
@section('head')
    <title>Novo Afastamento</title>
@endsection
@section('content')
    <form method="post" action="/armazenar-afastamentos/{{ $funcionario->funcionario_id }}" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid"> {{-- Container completo da página  --}}
            <div class="justify-content-center">
                <div class="col-12">
                    <br>
                    <div class="card" style="border-color: #355089">
                        <div class="card-header">
                            <div class="ROW">
                                <h5 class="col-12" style="color: #355089">
                                    Novo Afastamento
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <input class="form-control" type="text" value="{{ $funcionario->nome_completo }}"
                                        id="iddt_inicio" name="dt_inicio" required="required" disabled>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="form-group row">
                                <div class="form-group col-3 mb-3">Motivo do Afastamento
                                    <select class="form-select" style="border: 1px solid #999999; padding: 5px;"
                                        name="tipo_afastamento" id="tipo_afastamento" required="required" value="">
                                        @foreach ($tipoafastamento as $tiposafastamentos)
                                            <option value="{{ $tiposafastamentos->id }}"
                                                {{ $tiposafastamentos->id == old('tipo_afastamento') ? 'selected' : '' }}>
                                                {{ $tiposafastamentos->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-3 mb-3" id="complemento_suspensao" style="display: none;">
                                    Complemento
                                    <select class="form-select" id="referencia_suspensao" name="referencia_suspensao">
                                        <!-- Dados serão preenchidos dinamicamente -->
                                    </select>
                                </div>

                                <div class="form-group col-2">Data inicial da Ausência
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="{{ old('dt_inicio') }}" id="iddt_inicio" name="dt_inicio"
                                        required="required">
                                </div>
                                <div class="form-group col-2">Data de Retorno à Atividade
                                    <input class="form-control" style="border: 1px solid #999999; padding: 5px;"
                                        type="date" value="{{ old('dt_fim') }}" id="iddt_fim" name="dt_fim">
                                </div>
                                <div class="form-group col-4">Arquivo de Anexo
                                    <input type="file" style="border: 1px solid #999999; padding: 5px;"
                                        class="form-control form-control-sm" name ="ficheiro" id="idficheiro">
                                </div>
                            </div>

                            <div class="form-check mb-2">
                                <input type="checkbox" style="border: 1px solid #999999; padding: 5px;"
                                    class="form-check-input" id="justificado" name="justificado"
                                    @if (old('justificado')) checked @endif>
                                <label class="form-check-label" for="justificado">
                                    Justificado?
                                </label>
                            </div>


                            <div class="row">
                                <div class="mb-3 mt-md-0 mt-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Observação</label>
                                    <textarea class="form-control" style="border: 1px solid #999999; padding: 5px;" id="idobservacao" rows="3"
                                        name="observacao" value=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div>
            <a class="btn btn-danger col-md-3 col-2 mt-4 offset-md-1"
                href="/gerenciar-afastamentos/{{ $funcionario->funcionario_id }}" role="button">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary col-md-3 col-1 mt-4 offset-md-3" id="sucesso">
                Confirmar
            </button>
        </div>
    </form>

    <script>
        document.getElementById('tipo_afastamento').addEventListener('change', function() {
            const selectedValue = this.value;
            const complementoDiv = document.getElementById('complemento_suspensao');
            const referenciaSelect = document.getElementById('referencia_suspensao');

            // Verifica se o valor selecionado é 16 ou 17 (Suspensão de contrato ou outro motivo)
            if (selectedValue == 16 || selectedValue == 17) {
                complementoDiv.style.display = 'block'; // Mostra o campo

                // Faz requisição AJAX para buscar as opções complementares
                fetch(`/afastamento/complemento/${selectedValue}`)
                    .then(response => response.json())
                    .then(data => {
                        referenciaSelect.innerHTML = ''; // Limpa opções anteriores

                        // Adiciona as novas opções
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.complemento;
                            referenciaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erro ao buscar dados:', error)); // Trata possíveis erros
            } else {
                complementoDiv.style.display = 'none'; // Esconde o campo se não for 16 ou 17
                referenciaSelect.innerHTML = ''; // Limpa as opções do select
            }
        });
    </script>
@endsection
