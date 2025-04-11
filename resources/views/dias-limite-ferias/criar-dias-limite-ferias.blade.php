@extends('layouts.app')
@section('head')
    <title>Gerenciar Entidade de Ensino</title>
@endsection
@section('content')
    

    <div class="container">
        {{-- Container completo da página  --}}

        <br>
        <div class="col-12">
            <div class="card" style="border-color: #355089;">
                <div class="card-header">
                    <div class="ROW">
                        <h5 class="col-12" style="color: #355089">
                            Criar Dias Limite de Férias
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('store.gerenciar-dia-limite-ferias') }}" method="POST">
                        @csrf
                        <div class="container-fluid">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="numerosDeDias" class="form-label">
                                        <h5 style="color: #355089">Números de Dias</h5>
                                    </label>
                                    <input type="number" class="form-control" id="numerosDeDias"
                                        aria-describedby="emailHelp" min="1" max="365" name="dias">
                                </div>
                            </div>
                            <div class="row d-flex justify-content-evenly">
                                <div class="col-md-3 col-sm-12">
                                    <a href="{{ url()->previous() }}">
                                        <button type="button" class="btn btn-danger" style="width: 100%">Cancelar</button>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <button type="submit" class="btn btn-success" style="width: 100%">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tt="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            document.getElementById('numerosDeDias').addEventListener('input', function() {
                let value = parseInt(this.value, 10);
                if (value < 1) {
                    this.value = 1;
                } else if (value > 365) {
                    this.value = 365;
                }
            });
        </script>

    </div>
@endsection
