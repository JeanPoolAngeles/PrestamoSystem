@extends('template')

@section('title', 'ROLES-USUARIO')

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
            {!! Form::model($roles, ['route' => ['roles.update', $roles], 'method' => 'PUT']) !!}

            @include('roles.form')

            {!! Form::submit('Actualizar Rol', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('js')
@endpush
