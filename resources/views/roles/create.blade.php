@extends('template')

@section('title', 'ROLES-USUARIO')

@push('css')
@endpush

@section('content')

    <div class="card mt-4">
        <div class="card-body text-center bg-primary text-white">
            <h1>ADMINISTRACIÓN DE LOS ROLES DE USUARIOS</h1>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            {!! Form::open(['route' => 'roles.store']) !!}

            @include('roles.form')

            {!! Form::submit('Crear Rol', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('js')
@endpush
