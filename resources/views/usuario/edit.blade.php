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
            <h1>ASIGNAR ROLES PARA EL USUARIO</h1>
        </div>
    </div>

    <div class="card mt-4">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-body">
                    <p class="h3">Nombre: </p>
                    <p class="form-control">{{ $user->name }}</p>


                    {!! Form::model($user, ['route' => ['usuarios.update', $user], 'method' => 'put']) !!}
                    @foreach ($roles as $item)
                        <div>
                            <label>
                                {!! Form::checkbox('roles[]', $item->id, null, ['class' => 'mr-1']) !!}
                                {{ $item->name }}
                            </label>
                        </div>
                    @endforeach

                    {!! Form::submit('ASIGNAR ROL', ['class' => 'btn btn-primary mt-4']) !!}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
