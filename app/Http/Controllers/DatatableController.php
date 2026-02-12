<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Creditocliente;
use App\Models\Forma;
use App\Models\User;
use App\Models\prestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DatatableController extends Controller
{
    public function clients()
    {
        $clients = Cliente::select('id', 'dni', 'nombre', 'telefono', 'correo', 'direccion', 'fecha_nacimiento')
            ->orderBy('id', 'desc')->get();
        return DataTables::of($clients)->toJson();
    }

    public function users()
    {
        $users = User::select('id', 'name', 'email', 'created_at')
            ->orderBy('id', 'desc')->get();
        return DataTables::of($users)->toJson();
    }

    public function formas()
    {
        $formas = Forma::select('id', 'nombre')
            ->orderBy('id', 'desc')->get();
        return DataTables::of($formas)->toJson();
    }

    public function prestamos(Request $request)
    {
        $prestamos = Prestamo::with(['cliente'])->where('estado', [1, 2])->get();

        // Formatear los datos para DataTables
        $data = $prestamos->map(function ($prestamo) {
            return [
                'id' => $prestamo->id,
                'monto' => number_format($prestamo->monto, 2),
                'metodo' => $prestamo->metodo ?? 'Sin Método',
                'metodo_pago' => $prestamo->metodo_pago ?? 'Sin Método',
                'total_pagar' => number_format($prestamo->total_pagar, 2),
                'cliente' => $prestamo->cliente->nombre ?? 'Sin Cliente',
                'letra' => $prestamo->letra ? asset('storage/' . $prestamo->letra) : null,
                'garantia' => $prestamo->garantia ? asset('storage/' . $prestamo->garantia) : null,
                'foto' => $prestamo->foto ? asset('storage/' . $prestamo->foto) : null,
                'created_at' => $prestamo->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function creditoclientes($id)
    {
        // Verificar si el ID fue recibido correctamente
        if (!$id) {
            return response()->json(['error' => 'ID del cliente no proporcionado'], 400);
        }

        $creditos = Creditocliente::with(['cliente', 'prestamo', 'abonos'])
            ->where('id_cliente', $id) // Filtrar solo los créditos del cliente seleccionado
            ->get();

        $data = [];

        foreach ($creditos as $credito) {
            $abonado = $credito->abonos->sum('monto');
            $restante = $credito->monto - $abonado;

            $data[] = [
                'id' => $credito->id,
                'monto_prestamo' => number_format($credito->monto_prestamo, 2),
                'total' => number_format($credito->monto, 2),
                'cliente' => $credito->cliente->nombre,
                'abonado' => number_format($abonado, 2, '.', ''),
                'restante' => number_format($restante, 2, '.', ''),
                'fecha' => $credito->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return response()->json(['data' => $data]);
    }
}
