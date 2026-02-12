<?php

namespace App\Exports;

use App\Models\Prestamo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CreditoclienteExport implements FromView
{
    public function view(): View
    {
        $id_user = Auth::id();

        $consulta = Prestamo::with('creditos.abonos', 'cliente')
            ->where('id_usuario', $id_user)
            ->get();
            
        $creditos = [];        

        foreach ($consulta as $venta) {
            
            foreach ($venta->creditos as $credito) {

                $abonado = $credito->abonos->sum('monto');
                $restante = $venta->total - $abonado;

                $creditos[] = [
                    'id' => $credito->id,
                    'total' => number_format($venta->total, 2),
                    'nombre' => $venta->cliente->nombre,
                    'telefono' => $venta->cliente->telefono,
                    'abonado' => number_format($abonado, 2),
                    'restante' => number_format($restante, 2),
                    'fecha' => $credito->created_at->format('Y-m-d H:i:s'),
                ];
            }
        }

        return view('prestamos.creditos.reporte', compact('creditos'));
    }
}
