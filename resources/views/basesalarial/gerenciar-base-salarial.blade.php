@extends('layouts.app')

@section('content')
    <div class="container-fluid"> {{-- Container completo da página  --}}
        <div class="justify-content-center">
            <div class="col-12">
                <br>
                <div class="card" style="border-color: #355089;">
                    <div class="card-header">
                        <div class="ROW">
                            <h5 class="col-12" style="color: #355089">
                                Gerenciar Base Salarial
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row"> {{-- Linha com o nome e botão novo --}}
                            <div class="col-md-6 col-12">
                                <input class="form-control" type="text" value="{{ $funcionario->nome_completo }}"
                                    id="iddt_inicio" name="dt_inicio" required="required" disabled>
                            </div>
                            <div class="col-md-3 offset-md-3 col-12 mt-4 mt-md-0"> {{-- Botão de incluir --}}
                                <a href="{{ route('EditarBaseSalarial', ['idf' => $idf]) }}" class="col-6">
                                    <button type="button" style="font-size: 1rem; box-shadow: 1px 2px 5px #000000;" class="btn btn-success col-md-8 col-12">
                                        Editar Salário Atual
                                    </button>
                                </a>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <div class="table-responsive">
                            <div class="table">
                                <table
                                    class="table table-striped table-bordered border-secondary table-hover align-middle">
                                    <thead style="text-align: center;">
                                        <tr class="align-middle" style="background-color: #d6e3ff; font-size:19px; color:#000;">
                                            <th class="col">Nome Cargo</th>
                                            <th class="col">Salário</th>
                                            <th class="col">Anuênio</th>
                                            @if ($salarioatual->fgid != null)
                                                <th>Função Gratificada</th>
                                                <th>Gratificação</th>
                                            @endif
                                            <th class="col">Data Inicial</th>
                                            <th class="col">Data Final</th>
                                            <th class="col">Salário Final</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($hist_base_salarial as $hist_base_salarials)
                                            <tr>
                                                <td class="text-center">{{ $hist_base_salarials->crnome }}</td>
                                                <td class="text-center">{{ formatSalary($hist_base_salarials->crsalario) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ calculaAnuenio($hist_base_salarials, $funcionario) }}
                                                    %</td>
                                                @if ($salarioatual->fgid != null)
                                                    @if ($hist_base_salarials->hist_bs_fg_salario !== null)
                                                        <td class="text-center">{{ $hist_base_salarials->fgnome }}</td>
                                                        <td class="text-center">
                                                            {{ formatSalary($hist_base_salarials->hist_bs_fg_salario - ($hist_base_salarials->hist_bs_cr_salario + ($hist_base_salarials->hist_bs_cr_salario * calculaAnuenio($hist_base_salarials, $funcionario)) / 100)) }}
                                                        </td>
                                                    @else
                                                        <td class="text-center">--</td>
                                                        <td class="text-center">--</td>
                                                    @endif
                                                @endif
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($hist_base_salarials->hist_bs_dtinicio)->format('d/m/Y') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ optional($hist_base_salarials->hist_bs_dtfim)->format('d/m/Y') ?? '----' }}
                                                </td>
                                                <td>
                                                    {{ isset($hist_base_salarials->hist_bs_fg_salario)
                                                        ? formatSalary($hist_base_salarials->hist_bs_fg_salario)
                                                        : formatSalary(
                                                            $hist_base_salarials->crsalario +
                                                                (calculaAnuenio($hist_base_salarials, $funcionario) / 100) * $hist_base_salarials->crsalario,
                                                        ) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row d-flex justify-content-around">
                    <div class="col-4">
                        <a href="javascript:history.back()">
                            <button class="btn btn-primary" style="width: 100%">Retornar </button>
                        </a>
                    </div>
                </div>
            </div>
        @endsection

        @php
            function formatSalary($salary)
            {
                return 'R$' . number_format($salary, 2, ',', '.');
            }

            function calculaAnuenio($hist_base_salarial, $funcionario)
            {
                if ($hist_base_salarial->hist_bs_dtfim == null) {
                    $dataDeHoje = \Carbon\Carbon::now();
                    $dataDeContratacao = \Carbon\Carbon::parse($funcionario->dt_inicio);
                    $calculoFinal = intval($dataDeHoje->diffInDays($dataDeContratacao) / 365);
                    return $calculoFinal >= 10 ? 10 : $calculoFinal;
                } else {
                    $dataFim = \Carbon\Carbon::parse($hist_base_salarial->hist_bs_dtfim);
                    $dataDeContratacao = \Carbon\Carbon::parse($funcionario->dt_inicio);
                    return intval($funcionario->dt_inicio->diffInDays($dataDeContratacao) / 365);
                }
            }
        @endphp
