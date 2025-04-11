@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-4">
                        <div class="card">
                            <div class="card-body">
                                {{$setor->nome}}
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-end">
                        <a href="{{ route('index.setor') }}" class="text-black">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered border-secondary table-hover align-middle">
                        <thead style="background-color: #d6e3ff; font-size:17px; color:#000000">
                        <tr style="text-align: center;">
                            <th colspan="3">Setores Subordinados</th>
                        </tr>
                        </thead>
                        <thead style="background-color: #d6e3ff; font-size:17px; color:#000000">
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Data de Início</th>
                            <th scope="col">Data de Fim</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($setores_subordinados as $setor_subordinado)
                            <tr>
                                <td>{{ $setor_subordinado->nome }}</td>
                                <td>{{ $setor_subordinado->dt_inicio ? Carbon::parse($setor_subordinado->dt_inicio)->format('d/m/Y') : '--' }}</td>
                                <td>{{ $setor_subordinado->dt_fim ? Carbon::parse($setor_subordinado->dt_fim)->format('d/m/Y') : '--' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5>Histórico do Setor</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered border-secondary table-hover align-middle">
                                <thead style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Data de Início</th>
                                    <th scope="col">Data de Fim</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $setor->nome }}</td>
                                    <td>{{ $setor->dt_inicio ? Carbon::parse($setor->dt_inicio)->format('d/m/Y') : '--' }}</td>
                                    <td>{{ $setor->dt_fim ? Carbon::parse($setor->dt_fim)->format('d/m/Y') : '--' }}</td>
                                </tr>
                                @foreach($setores_anteriores as $setor_anterior)
                                    <tr>
                                        <td>{{ $setor_anterior->nome }}</td>
                                        <td>{{ $setor_anterior->dt_inicio ? Carbon::parse($setor_anterior->dt_inicio)->format('d/m/Y') : '--' }}</td>
                                        <td>{{ $setor_anterior->dt_fim ? Carbon::parse($setor_anterior->dt_fim)->format('d/m/Y') : '--' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <a href="{{route('index.setor')}}" class="btn btn-danger btn-block" style="width: 100%">Retornar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
