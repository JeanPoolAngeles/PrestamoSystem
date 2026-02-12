<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacione;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CotizacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.cotizacion.index');
    }

    public function store(Request $request)
    {
        $datosCotizacion = $request->all();

        $id_cliente = $datosCotizacion['id_cliente'];
        //registrar cotizacion
        $totalDecimal = Cart::instance('cotizaciones')->subtotal();
        $total = str_replace(',', '', $totalDecimal);
        if ($total > 0) {
            $userId = Auth::id();
            $sale = Cotizacione::create([
                'total' => $total,
                'id_cliente' => $id_cliente,
                'id_usuario' => $userId,
            ]);
            if ($sale) {
                foreach (Cart::instance('cotizaciones')->content() as $item) {
                    
                }
                Cart::instance('cotizaciones')->destroy();
                return response()->json([
                    'title' => 'COTIZACION GENERADA',
                    'icon' => 'success',
                    'ticket' => $sale->id
                ]);
            }
        } else {
            return response()->json([
                'title' => 'CARRITO VACIO',
                'icon' => 'warning'
            ]);
        }
    }
/*
    public function ticket($id)
    {
        $data['company'] = Compania::first();

        $data['cotizacion'] = Cotizacion::join('clientes', 'cotizaciones.id_cliente', '=', 'clientes.id')
            ->select('cotizaciones.*', 'clientes.nombre', 'clientes.telefono', 'clientes.direccion')
            ->where('cotizaciones.id', $id)
            ->first();

        $data['productos'] = Detallecotizacion::join('productos', 'detallecotizacion.id_producto', '=', 'productos.id')
            ->select('detallecotizacion.*', 'productos.producto')
            ->where('detallecotizacion.id_cotizacion', $id)
            ->get();

        $fecha_cotizacion = $data['cotizacion']['created_at'];
        $data['fecha'] = date('d/m/Y', strtotime($fecha_cotizacion));
        $data['hora'] = date('h:i A', strtotime($fecha_cotizacion));
        // Generar el contenido del ticket en HTML
        $html = View::make('cotizacion.ticket', $data)->render();
        //Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // Generar el PDF utilizando laravel-dompdf
        //$pdf = Pdf::loadHTML($html)->setPaper([0, 0, 226.77, 500], 'portrait')->setWarnings(false);
        $pdf = Pdf::loadHTML($html)->setPaper([0, 0, 140, 500], 'portrait')->setWarnings(false);

        return $pdf->stream('ticket.pdf');
    }
*/
    public function show()
    {
        return view('admin.cotizacion.show');
    }

    public function cliente(Request $request)
    {
        $term = $request->get('term');
        $clients = Cliente::where('nombre', 'LIKE', '%' . $term . '%')
            ->select('id', 'nombre AS label', 'telefono', 'direccion')
            ->limit(10)
            ->get();
        return response()->json($clients);
    }

    public function eliminar($cotizacionId)
    {
        // Buscar la cotizacion por ID con sus detalles
        $cotizacion = Cotizacione::findOrFail($cotizacionId);
        // Actualizar el estado de la cotizacion a 0
        $cotizacion->delete();

        return redirect()->route('admin.cotizacion.show')
            ->with('success', 'COTIZACION ELIMINADA');
    }
}
