@extends('template')

@section('title', 'CREAR-CLIENTE')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h1 class="fw-bold ">ADMINISTRACIÓN DE LOS NUEVOS CLIENTES</h1>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <!-- Agregar campo DNI y botón de consulta -->
            <div class="form-group col-md-4">
                {{ Form::label('dni', 'Busca DNI') }}
                {{ Form::number('dnip', '', ['class' => 'form-control', 'id' => 'dnip', 'placeholder' => 'INGRESE UN DNI']) }}
                <button type="button" id="consultarDni" class="btn btn-primary mt-2">Consultar DNI</button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @if ($errors->any())
                        @foreach ($errors->all() as $item)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $item }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endforeach
                    @endif
                    <div class="card card-default">

                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.clientes.store') }}" role="form"
                                autocomplete="off">
                                @csrf

                                @include('admin.cliente.form')

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#consultarDni').on('click', function() {
                var dni = $('#dnip').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('consulta.dni') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        dni: dni
                    },
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            // Ajusta estos nombres de campo según la respuesta de tu API
                            $('#nombre').val(data.nombre);
                            $('#dni').val(data.numeroDocumento);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error al consultar el DNI');
                    }
                });
            });
        });
    </script>
@stop
