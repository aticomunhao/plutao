@extends('layouts.app')
@section('head')
    <title>Gerenciar Entidade de Ensino</title>
@endsection
@section('content')
    
    <div class="container"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089;">
                    <div class="card-header">
                        <div class="ROW">
                            <div class="col-sm-12 col-md-6">
                                <h5 style="color: #355089">
                                    Gerenciar Dias Limite de Férias
                                </h5>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card-body">
                        <form method="GET" action="">{{-- Formulario para o botao e input de pesquisa --}}
                            @csrf
                            <div class="row justify-content-start">

                                <div class="col-md-8 col-12">
                                    {{-- Botao submit da pesquisa --}}
                                    <a href="{{ route('create.gerenciar-dia-limite-ferias') }}" {{-- Botao com rota para incluir entidades --}}
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
                                    <th>Dias Para a Data Limite</th>
                                    <th>Dia inicio de Validade</th>
                                    <th>Dia Fim Validade</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 15px; color:#000000;">
                                @foreach ($dias_limite_de_ferias as $dia_limite_ferias)
                                    <tr style="text-align: center">
                                        <td> {{ $dia_limite_ferias->dias }}</td>
                                        <td> {{ Carbon\Carbon::parse($dia_limite_ferias->data_inicio)->format('d/m/Y') }}
                                        </td>
                                        <td>{{ $dia_limite_ferias->data_fim ? \Carbon\Carbon::parse($dia_limite_ferias->data_fim)->format('d/m/Y') : '--' }}
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
