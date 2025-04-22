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
                            Alterar Hierarquia de setor
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <form id="" action="/atualizar-hierarquia/{{$result[0]->ids}}"  method="POST">
                    @csrf
                    <div class="row">
                        <div class="col">Nível
                            <input class="form-select" value="{{$sn_pai}}" style="border: 1px solid #999999; padding: 5px;" disabled>                     
                        </div>
                        <div class="col">Setor
                            <input class="form-select" value="{{$ss_pai}}" style="border: 1px solid #999999; padding: 5px;" disabled> 
                        </div>
                        <div class="col" style="text-align: right;">
                            <a href="/gerenciar-hierarquia" type="button" class="btn btn-danger btn-md"
                                    style="box-shadow: 1px 2px 5px #000000; margin-top: 16px; margin-left: 2px; font-size: 1rem"
                                    value="">Cancelar</a>
                        </div>
                        <div class="col">
                            <input type="submit" value="Confirmar" class="btn btn-primary btn-md"
                                    style="box-shadow: 1px 2px 5px #000000; margin-top: 16px; font-size: 1rem">
                        </div>
                    </div>                   
                    <hr>
                    <div class="table">
                        <table class="table table-sm table-striped table-bordered border-secondary table-hover align-middle">
                            <thead style="text-align: center;">
                                <tr style="background-color: #d6e3ff; font-size:17px; color:#000000">
                                    <th class="col-1">
                                        
                                            <input type="checkbox" id="checkAll" name="todos" data-toggle="toggle" data-onlabel="Todos"
                                                data-offlabel="Não" data-onstyle="success" data-offstyle="danger" data-size="small">                
                                        
                                    </th>
                                    <th class="col-4">Nome</th>
                                    <th class="col-1">Sigla</th>
                                    <th class="col-1">Data Inicio</th>
                                    <th class="col-1">Status</th>
                                    <th class="col-1">Substituto</th>
                                    <th class="col-4">Setor Responsável</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 15px; color:#000000;">
                                @foreach ($result as $results)
                                <tr>
                                    <td>
                                        <center>
                                        <input type="checkbox" class="checkBox" name="setores[]" value="{{ $results->ids }}"
                                        {{ $results->ids == $ids ? 'checked disabled' : ($results->id_pai == $ids ? 'checked' : '') }}
                                        
                                        </center>
                                    </td>
                                    <td><center>{{ $results->nome_setor }}</center></td>
                                    <td><center>{{ $results->sigla_setor }}</center></td>
                                    <td><center>{{ date( 'd-m-Y' , strtotime( $results->dt_inicio)) }}</center></td>
                                    <td><center>{{ $results->status }}</center></td>
                                    <td><center>{{ $results->nome_substituto }}</center></td>
                                    <td><center>{{ $results->sigla_pai }}</center></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        const isChecked = this.checked;
        const checkboxes = document.querySelectorAll('.checkBox:not(:disabled)');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });
</script>

@endsection
