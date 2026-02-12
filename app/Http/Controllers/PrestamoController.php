<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Creditocliente;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('admin.prestamos.index');
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        try {
            $datosPrestamo = $request->all();

            // Cálculo de intereses y totales
            $monto = $datosPrestamo['monto'];
            $interes = $datosPrestamo['interes']; // Interés porcentual
            $cantidad = $datosPrestamo['cantidad']; // Cantidad de períodos

            // Validar datos básicos
            if ($monto <= 0 || $interes <= 0 || $cantidad <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos para el cálculo del préstamo.',
                ], 400);
            }

            // Calcular interés total
            $interesTotal = $monto * $interes;

            // Calcular ganancia total
            $ganancia = $interesTotal * $cantidad;

            // Calcular total a pagar
            $totalPagar = $monto + $ganancia;

            // Procesar imágenes
            $letraPath = $request->hasFile('letra') ? $request->file('letra')->store('letras', 'public') : null;
            $garantiaPath = $request->hasFile('garantia') ? $request->file('garantia')->store('garantias', 'public') : null;
            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('fotos', 'public') : null;

            // Crear el préstamo
            $prestamo = Prestamo::create([
                'monto' => $monto,
                'total_pagar' => $totalPagar,
                'ganancia' => $ganancia,
                'interes' => $interes,
                'metodo' => $datosPrestamo['metodo'],
                'metodo_pago' => $datosPrestamo['metodo_pago'],
                'cantidad' => $cantidad,
                'letra' => $letraPath,
                'garantia' => $garantiaPath,
                'foto' => $fotoPath,
                'id_cliente' => $datosPrestamo['id_cliente'],
                'id_usuario' => $userId,
            ]);

            // Comprobar el método y crear crédito si es necesario
            if ($prestamo) {
                $credito = Creditocliente::create([
                    'monto_prestamo' => $monto,
                    'monto' => $totalPagar,
                    'id_cliente' => $datosPrestamo['id_cliente'],
                    'id_prestamo' => $prestamo->id,
                    'id_usuario' => $userId,
                ]);

                // Actualizar el crédito del cliente
                $cliente = Cliente::findOrFail($datosPrestamo['id_cliente']);
                $cliente->credito += $monto;
                $cliente->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Préstamo registrado con éxito',
                    'credito' => $credito,
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function limitecliente($id_cliente)
    {
        $creditos = Creditocliente::select('id', 'monto')
            ->where('id_cliente', $id_cliente)
            ->with('abonos')
            ->get();

        $monto = $creditos->sum('monto');
        $abonado = $creditos->flatMap(function ($credito) {
            return $credito->abonos->pluck('monto');
        })->sum();

        $cliente = Cliente::find($id_cliente);
        $restante = $cliente->credito - ($monto - $abonado);

        return $restante;
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

    public function anular($PrestamoId)
    {
        $userId = Auth::id();
        try {
            // Iniciar una transacción
            DB::beginTransaction();

            // Buscar el prestamo por ID
            $prestamo = Prestamo::findOrFail($PrestamoId);

            // Actualizar el estado del prestamo a 0
            $prestamo->update(['estado' => 0]);

            // Confirmar la transacción
            DB::commit();

            return redirect()->route('admin.prestamos.index')
                ->with('success', 'PRESTAMO ANULADA');
        } catch (\Exception $e) {
            // Deshacer la transacción en caso de error
            DB::rollback();

            return redirect()->route('admin.prestamos.show')
                ->with('error', 'ERROR AL ANULAR');
        }
    }

    public function show()
    {
        return view('admin.prestamos.show');
    }

    public function edit($id)
    {
        $prestamo = Prestamo::where('id', $id)->firstOrFail();
        return view('admin.prestamos.edit', compact('prestamo'));
    }

    public function update(Request $request, $id)
    {
        $prestamo = Prestamo::findOrFail($id);


        // Procesar letra
        if ($request->hasFile('letra')) {
            $letraPath = $request->file('letra')->store('letras', 'public');
            if ($prestamo->letra) {
                Storage::disk('public')->delete($prestamo->letra);
            }
            $prestamo->letra = $letraPath;
        }

        // Procesar garantía
        if ($request->hasFile('garantia')) {
            $garantiaPath = $request->file('garantia')->store('garantias', 'public');
            if ($prestamo->garantia) {
                Storage::disk('public')->delete($prestamo->garantia);
            }
            $prestamo->garantia = $garantiaPath;
        }

        // Procesar foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
            if ($prestamo->foto) {
                Storage::disk('public')->delete($prestamo->foto);
            }
            $prestamo->foto = $fotoPath;
        }

        $prestamo->save();

        return redirect()->route('admin.prestamos.show')->with('success', 'Préstamo actualizado correctamente');
    }
}
