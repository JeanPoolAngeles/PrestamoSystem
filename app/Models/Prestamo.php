<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;
    protected $table = 'prestamos';

    protected $perPage = 20;

    protected $fillable = [
        'monto',
        'total_pagar',
        'metodo',
        'ganancia',
        'metodo_pago', // Este debe estar presente
        'interes',
        'cantidad',
        'letra',
        'garantia',
        'estado',
        'foto',
        'id_cliente',
        'id_usuario',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }

    public function creditos()
    {
        return $this->hasMany(Creditocliente::class, 'id_prestamo');
    }
}
