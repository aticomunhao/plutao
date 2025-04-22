@extends('layouts.app')
@section('head')
    <title>Gerenciar Hierarquia</title>
@endsection
@section('content')
    
       
<div class="container-fluid"> {{-- Container completo da página  --}}
    <div class="justify-content-center">
        <div class="col-12">
            <br>
            <div class="card" style="border-color: #355089">
                <div class="card-header">
                    <div class="ROW">
                        <h5 class="col-12" style="color: #355089">
                            Gerenciar Hierarquia
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <form id="" action="/gerenciar-hierarquia"  method="GET">
                    @csrf
                    <div class="row">
                        <div class="form-group  col-xl-2 col-md-5 ">
                            <label for="1">Nível</label>
                            <select id="idnivel" class="form-select"
                                style="border: 1px solid #999999; padding: 5px;" name="nivel_pai">
                                <option></option>
                                @foreach ($nivel as $niveis)
                                    <option value="{{ $niveis->id_nivel }}"
                                        {{ request('nivel_pai') == $niveis->id_nivel ? 'selected' : '' }}>
                                        {{ $niveis->nome_nivel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-4 ">
                            <label for="1">Setor</label>
                            <select id="idsetor" class="form-select select2"
                                style="border: 1px solid #999999; padding: 5px;" name="setor_pai" disabled>
                                <option></option>
                                @foreach ($setor as $setores)
                                    <option value="{{ $setores->ids }}"
                                        {{ request('setor_pai') == $setores->ids ? 'selected' : '' }}>
                                        {{ $setores->nome_setor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col" style="padding-top:5px">
                            <a href="/gerenciar-hierarquia" type="button" class="btn btn-light btn-sm"
                                style="box-shadow: 1px 2px 5px #000000; margin-top: 16px; margin-left: 2px; font-size: 1rem"
                                value="">Limpar</a>
                            <input type="submit" value="Pesquisar" class="btn btn-light btn-sm"
                                style="box-shadow: 1px 2px 5px #000000; margin-top: 16px; font-size: 1rem">
                        </div>
                        </form>
                    </div>                    
                    <hr>
                    <div class="table">
                        <table class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                            <thead style="text-align: center;">
                                <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                    <th class="col-4">Nome</th>
                                    <th class="col-1">Sigla</th>
                                    <th class="col-1">Data Inicio</th>
                                    <th class="col-1">Status</th>
                                    <th class="col-2">Substituto</th>
                                    <th class="col-2">Setor Responsável</th>
                                    <th class="col-1">Ação</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 15px; color:#000000;">
                                @foreach ($lista as $listas)
                                <tr>
                                    <td><center>{{ $listas->nome_setor }}</center></td>
                                    <td><center>{{ $listas->sigla }}</center></td>
                                    <td><center>{{ $listas->dt_inicio }}</center></td>
                                    <td><center>{{ $listas->status }}</center></td>
                                    <td><center>{{ $listas->nome_substituto }}</center></td>
                                    <td><center>{{ $listas->st_pai }}</center></td>
                                    <td style="text-align: center;">
                                    <a href="/editar-hierarquia/{{ $listas->ids }}"
                                            type="button" class="btn btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Mudar"><i class="bi bi-arrow-down-up"
                                                style="font-size: 1rem; color:#303030;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                        
                    </div>
                </div>
                <div style="margin-right: 10px; margin-left: 10px">
                        {{ $lista->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        $('#idnivel').change(function() {
            var nivel_id = $(this).val();

            $.ajax({
                url: '/obter-setores/' + nivel_id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#idsetor').removeAttr('disabled').empty().append('<option></option>');
                    $.each(data, function(index, item) {
                        $('#idsetor').append('<option value="' + item.id + '">' + item.nome + '</option>');
                    });
                },
                error: function(xhr) {
                    console.log('Erro ao buscar setores:', xhr.responseText);
                }
            });
        });
    </script>

@endsection
