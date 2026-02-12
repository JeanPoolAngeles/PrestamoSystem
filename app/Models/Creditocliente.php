<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creditocliente extends Model
{
  use HasFactory;

  protected $fillable = ['monto_prestamo', 'monto', 'id_cliente', 'id_prestamo', 'id_usuario'];

  public function abonos()
  {
    return $this->hasMany(Abonocliente::class, 'id_credito', 'id');
  }

  // Relación con Cliente
  public function cliente()
  {
    return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
  }

  public function prestamo()
  {
    return $this->belongsTo(Prestamo::class, 'id_prestamo', 'id');
  }
}
