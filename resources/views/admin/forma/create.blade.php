@extends('template')

@section('title', 'CREAR-PAGO')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h1 class="fw-bold">ADMINISTRACIÓN DE LAS FORMAS DE PAGOS</h1>
        </div>
    </div>
    <div class="container mt-4">
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
                            <form method="POST" action="{{ route('admin.formas.store') }}" role="form"
                                autocomplete="off">
                                @csrf

                                @include('admin.forma.form')

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
