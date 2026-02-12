<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonocliente extends Model
{
    use HasFactory;
    protected $fillable = ['monto', 'foto', 'id_usuario', 'id_credito', 'id_forma'];

    public function creditocliente()
    {
        return $this->belongsTo(Creditocliente::class, 'id_credito');
    }

    public function formapago()
    {
        return $this->belongsTo(Forma::class, 'id_forma');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
