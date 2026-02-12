<div class="form-group">
    {!! Form::label('name', 'nombre') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholde' => 'Ingrese nombre del rol...']) !!}

    @error('name')
        <small class="text-danger">
            {{$message}}
        </small>
    @enderror
</div>

<h2 class="h3">LISTA DE PERMISOS</h2>
@foreach ($permissions as $permi)
    <div>
        <label>
            {!! Form::checkbox('permissions[]', $permi->id, null, ['class' => 'mr-1']) !!}
            {{$permi->description}}
        </label>
    </div>
@endforeach