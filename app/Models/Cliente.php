<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    public static function rules($id = null)
    {
        return [
            'nombre' => 'required|string|max:255',
            'dni' => "required|unique:clientes,dni,{$id}",
            'telefono' => 'required|string|max:11',
            'correo' => 'nullable|email|max:255',
            'direccion' => 'required|string',
            'credito' => 'nullable',
            'fecha_nacimiento' => 'required|date',
        ];
    }

    protected $table = 'clientes';

    protected $fillable = ['nombre', 'dni', 'telefono', 'correo', 'direccion', 'credito', 'fecha_nacimiento'];

    // Relación con prestamos
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_cliente', 'id');
    }
}
