@extends('template')

@section('title', 'EDITAR-CLIENTE')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h1 class="fw-bold ">ADMINISTRACIÓN DE LOS CLIENTES</h1>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header text-center">
            <h2>Estas editando al cliente: {{ $cliente->nombre }}</h2>
        </div>
        <div class="card-body">
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
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Cliente</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.clientes.update', $cliente->id) }}" role="form"
                            autocomplete="off">
                            {{ method_field('PATCH') }}
                            @csrf
                            @include('admin.cliente.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
