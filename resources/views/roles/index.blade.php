@extends('template')

@section('title', 'ROLES-USUARIOS')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    @if (session('success'))
        <script>
            let message = "{{ session('success') }}"
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: message,
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
    <div class="card mt-4">
        <div class="card-body text-center bg-primary text-white">
            <h1>ADMINISTRACIÓN DE LOS ROLES DE USUARIOS</h1>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-body">
                        <a class="btn btn-primary float-right mr-4" href="{{ route('roles.create') }}">Nuevo ROL</a>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ROLE</th>
                                    <th colspan="2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $item)
                                    <tr>
                                        <td>{{ $item->id }}</th>
                                        <td>{{ $item->name }}</th>
                                        <td width="10px">
                                            <a href="{{ route('roles.edit', $item) }}"
                                                class="btn btn-sm btn-primary">Editar</a>
                                        </td>
                                        <td width="10px">
                                            <form action="{{ route('roles.destroy', $item) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
