@extends('layouts.app')
@section('head')
    <title>Visualizar Cargos</title>
@endsection
@section('content')
    <br/>
    <div class="container">
        <div class="card" style="border-color:#355089">
            <div class="card-header">
                <div>Visualizar Cargos</div>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between">
                    <div class="col-md-5 col-sm-12">
                        <input class="form-control" type="text" value="{{ $cargo->nomeCR }}"
                               {{-- Campo com o nome do cargo --}}
                               aria-label="Disabled input example" disabled>
                    </div>
                    <div class="col-md-7 col-12">
                        <a href="{{ route('gerenciar.cargos') }}">{{-- Botao de retorno para index --}}
                            <button type="button"
                                    class="btn btn-primary col-md-3 col-12 offset-md-8 offset-0 mt-2 mt-md-0">Retornar
                            </button>
                        </a>
                    </div>
                </div>
                <hr>
                <table class="table table-striped table-bordered border-secondary table-hover align-middle">
                    <thead style="text-align: center;">
                    <tr style="background-color: #d6e3ff; font-size:19px; color:#000;" class="align-middle">
                        <th class="col-2">Data Inicial</th>
                        <th class="col-2">Data Final</th>
                        <th class="col-4">Salário</th>
                        <th class="col-4">Motivo</th>
                    </tr>
                    </thead>
                    <tbody style="font-size: 15px; color:#000000;">
                    @foreach ($hist_cargo_regular as $hist_cargo_regulars)
                        <tr>
                            <td style="text-align:center;">{{-- Data inicio --}}
                                {{ date('d-m-Y', strtotime($hist_cargo_regulars->data_inicio)) }}</td>
                            <td style="text-align:center;">{{-- Data Final --}}
                                {{ !is_null($hist_cargo_regulars->data_fim) ? date('d-m-Y', strtotime($hist_cargo_regulars->data_fim)) : '--' }}
                            </td>
                            <td style="text-align:center;">{{-- Salario --}}
                                {{ number_format($hist_cargo_regulars->salarioHist, 2, ',', '.') }}</td>
                            <td style="text-align:center;">{{ $hist_cargo_regulars->motivoAlt }}</td>{{-- Motivo --}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
