<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacione extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'cotizaciones';
    protected $fillable = ['total', 'estado', 'id_cliente', 'id_usuario'];

}
