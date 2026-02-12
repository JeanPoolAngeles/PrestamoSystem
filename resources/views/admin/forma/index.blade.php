@extends('template')

@section('title', 'ADMIN-PAGOS')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h1 class="fw-bold">ADMINISTRACIÓN DE LAS FORMAS DE PAGOS</h1>
        </div>
    </div>
    <div class="container mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    @can('admin.formas-pago.index')
                        <div class="mb-2">
                            <a href="{{ route('admin.formas.create') }}" class="btn btn-primary btn-sm" data-placement="left">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    @endcan

                    <div class="card">
                        <div class="card-body">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ $message }}
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped table-hover display responsive nowrap" width="100%"
                                    id="tblFormas">
                                    <thead class="thead">
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="deleteForm" action="#" method="post">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('css')
    <link href="DataTables/datatables.min.css" rel="stylesheet">
@endsection

@section('js')
    <script src="DataTables/datatables.min.js"></script>
    <script>
        var editUrl = "{{ route('admin.formas.edit', ['forma' => ':forma']) }}";
        var deleteUrl = "{{ route('admin.formas.destroy', ['forma' => ':forma']) }}";

        document.addEventListener("DOMContentLoaded", function() {
            new DataTable('#tblFormas', {
                responsive: true,
                fixedHeader: true,
                ajax: {
                    url: '{{ route('admin.formas.list') }}',
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'nombre'
                    },
                    {
                        // Agregar columna para acciones
                        data: null,
                        render: function(data, type, row) {
                            return `<a class="btn btn-sm btn-primary" href="${editUrl.replace(':forma', row.id)}"><i class="fas fa-edit"></i></a>` +
                                `<button class="btn btn-sm btn-danger" onclick="deleteForma(${row.id})"><i class="fas fa-trash"></i></button>`;
                        }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.3/i18n/es-ES.json',
                },
                order: [
                    [0, 'asc']
                ]
            });
        });

        // Función para eliminar una forma de pago
        function deleteForma(formaId) {
            Swal.fire({
                title: "Eliminar",
                text: "¿Estás seguro de que quieres eliminar esta forma de pago?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!"
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.querySelector('#deleteForm');
                    form.action = deleteUrl.replace(':forma', formaId);
                    // Enviar el formulario
                    form.submit();
                }
            });
        }
    </script>
@stop
