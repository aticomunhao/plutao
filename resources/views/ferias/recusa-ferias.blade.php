@extends('layouts.app')

@section('content')
    <br>

    <div class="container-fluid">
        <div class="card" style="border-color: #355089">
            <h5 class="card-header" style="color: #355089">Gerenciar Férias</h5>
            <div class="card-body">
                <div class="row justify-content-around">
                    <div class="col-md-3">
                        <input class="form-control" type="text"
                            value="{{ $periodos_aquisitivos->nome_completo_funcionario }}"
                            aria-label="Disabled input example" disabled readonly
                            style="text-align: center; color: #355089; font-size: 16px; font-weight: bold;
                        border: 2px solid #355089; border-radius: 5px; background-color: #f8f9fa;">
                    </div>
                    <div class="col-md-3">

                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                        <thead style="text-align: center;">
                        <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">

                                <th scope="col">Início 1</th>
                                <th scope="col">Fim 1</th>
                                <th scope="col">Início 2</th>
                                <th scope="col">Fim 2</th>
                                <th scope="col">Início 3</th>
                                <th scope="col">Fim 3</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td style="text-align: center">
                                    {{ $periodos_aquisitivos->dt_ini_a ? \Carbon\Carbon::parse($periodos_aquisitivos->dt_ini_a)->format('d/m/y') : '--' }}
                                </td>
                                <td style="text-align: center">
                                    {{ $periodos_aquisitivos->dt_fim_a ? \Carbon\Carbon::parse($periodos_aquisitivos->dt_fim_a)->format('d/m/y') : '--' }}
                                </td>
                                <td style="text-align: center">
                                    {{ $periodos_aquisitivos->dt_ini_b ? \Carbon\Carbon::parse($periodos_aquisitivos->dt_ini_b)->format('d/m/y') : '--' }}
                                </td>
                                <td style="text-align: center">
                                    {{ $periodos_aquisitivos->dt_fim_b ? \Carbon\Carbon::parse($periodos_aquisitivos->dt_fim_b)->format('d/m/y') : '--' }}
                                </td>
                                <td style="text-align: center">
                                    {{ $periodos_aquisitivos->dt_ini_c ? \Carbon\Carbon::parse($periodos_aquisitivos->dt_ini_c)->format('d/m/y') : '--' }}
                                </td>
                                <td style="text-align: center">
                                    {{ $periodos_aquisitivos->dt_fim_c ? \Carbon\Carbon::parse($periodos_aquisitivos->dt_fim_c)->format('d/m/y') : '--' }}
                                </td>
                                <td style="text-align: center">{{ $periodos_aquisitivos->status_pedido_ferias }}
                                </td>

                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="container">

                        <form method="get" action="{{route('RecusaFerias', ['id' => $id])}}">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_motivo_da_recusa">Motivo Recusa</label>
                                        <input type="text" class="form-control" id="id_motivo_da_recusa"
                                            aria-describedby="emailHelp" name="motivo_da_recusa" required>
                                    </div>
                                    <br>
                                    <div class="row justify-content-around">

                                        <div class="col-4">
                                            <a href="/gerenciar-ferias" class="btn btn-danger" style="width: 100%">
                                                Cancelar
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-primary"
                                                style="width: 100%">Enviar</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
