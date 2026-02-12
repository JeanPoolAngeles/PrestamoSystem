<?php

namespace App\Http\Controllers;

use App\Exports\CreditoclienteExport;
use App\Models\Abonocliente;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Compania;
use App\Models\Creditocliente;
use App\Models\Forma;
use App\Models\Prestamo;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class CreditoclienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $formapagos = Forma::all();
        $cliente = Cliente::findOrFail($id);

        // Asegurar que solo se obtengan créditos relacionados con este cliente
        $creditos = Creditocliente::where('id_cliente', $id)
            ->with(['prestamo', 'abonos'])
            ->get();

        return view('admin.prestamos.creditos.index', compact('cliente', 'creditos', 'formapagos'));
    }

    public function abonos($id)
    {
        $formapagos = Forma::all();

        // Cargar correctamente la relación prestamo y cliente
        $credito = Creditocliente::with(['prestamo.cliente'])->find($id);

        // Verificar si el crédito existe
        if (!$credito) {
            return back()->withErrors('Crédito no encontrado.');
        }

        // Verificar si la relación prestamo existe
        if (!$credito->prestamo) {
            return back()->withErrors('No se encontró el préstamo asociado a este crédito.');
        }

        // Verificar si la relación cliente existe
        if (!$credito->prestamo->cliente) {
            return back()->withErrors('No se encontró el cliente asociado a este préstamo.');
        }

        // Obtener al cliente correctamente
        $cliente = $credito->prestamo->cliente;

        // Obtener abonos
        $abonos = Abonocliente::with('formapago', 'usuario')
            ->where('id_credito', $id)
            ->orderBy('id', 'desc')
            ->paginate(20);

        // Calcular el total abonado
        $abonado = Abonocliente::where('id_credito', $id)->sum('monto');

        return view('admin.prestamos.creditos.abonos', compact('abonos', 'credito', 'formapagos', 'abonado', 'cliente'));
    }

    public function detalle($id_credito)
    {
        $abonado = Abonocliente::where('id_credito', $id_credito)->sum('monto');
        $credito = Creditocliente::with('cliente')->find($id_credito);

        $total = $credito->monto;

        $restante = $total - $abonado;

        $data = [
            'cliente' => ['nombre' => $credito->cliente->nombre, 'telefono' => $credito->cliente->telefono],
            'credito' => ['abonado' => $abonado, 'restante' => $restante, 'total' => $total],
        ];

        return response()->json($data);
    }

    public function limitecliente($id_cliente)
    {
        $creditos = Creditocliente::select('id', 'monto')
            ->where('id_cliente', $id_cliente)
            ->with('abonos')
            ->get();

        $total = $creditos->sum('monto');
        $abonado = $creditos->flatMap(function ($credito) {
            return $credito->abonos->pluck('monto');
        })->sum();

        $cliente = Cliente::find($id_cliente);
        $restante = $cliente->credito - ($total - $abonado);

        return response()->json($restante);
    }

    public function registrarAbono(Request $request)
    {
        $userId = Auth::id();

        // Validar la solicitud
        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'forma' => 'required|exists:formas,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_credito' => 'required|exists:creditoclientes,id',
        ]);

        // Procesar la imagen del comprobante
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('abonos', 'public');
        } else {
            $fotoPath = null;
        }

        // Validar el saldo restante
        $abonado = Abonocliente::where('id_credito', $request->input('id_credito'))->sum('monto');
        $totalCredito = Creditocliente::findOrFail($request->input('id_credito'));

        $total = $totalCredito->monto;
        $restante = $total - $abonado;

        if ($request->input('monto') > $restante) {
            return response()->json([
                'icon' => 'warning',
                'title' => 'El monto ingresado excede el restante: ' . number_format($restante, 2)
            ]);
        }

        // Crear el abono
        $abono = Abonocliente::create([
            'monto' => $request->input('monto'),
            'foto' => $fotoPath, // Ruta relativa de la imagen
            'id_forma' => $request->input('forma'),
            'id_usuario' => $userId,
            'id_credito' => $request->input('id_credito'),
        ]);

        return response()->json([
            'icon' => 'success',
            'title' => 'Abono registrado correctamente.',
            'ticket' => $abono->id,
        ]);
    }

    public function ticket($id)
    {
        $data['company'] = Compania::first();

        $data['abono'] = Abonocliente::with('Creditocliente.prestamo.cliente', 'formapago')->findOrFail($id);

        $id_credito = $data['abono']->Creditocliente->id;

        $data['abonado'] = 0;

        $creditos = Creditocliente::with('abonos')
            ->where('id', $id_credito)
            ->get();
        foreach ($creditos as $credito) {
            $abonos_hasta_id = $credito->abonos->filter(function ($abono) use ($id) {
                return $abono->id <= $id;
            });
            $data['abonado'] = $abonos_hasta_id->sum('monto');
        }
        // Generar el contenido del ticket en HTML
        $html = View::make('admin.prestamos.creditos.ticket', $data)->render();
        //Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // Generar el PDF utilizando laravel-dompdf
        //$pdf = Pdf::loadHTML($html)->setPaper([0, 0, 226.77, 500], 'portrait')->setWarnings(false);
        $pdf = Pdf::loadHTML($html)->setPaper([0, 0, 140, 500], 'portrait')->setWarnings(false);

        return $pdf->stream('ticket.pdf');
    }
    public function generateExcelReport()
    {
        return Excel::download(new CreditoclienteExport, 'creditos.xlsx');
    }

    public function generatePdfReport()
    {
        $userId = Auth::id();

        $consulta = Prestamo::with('creditos.abonos', 'cliente')
            ->where('id_usuario', $userId)
            ->get();

        $creditos = [];

        foreach ($consulta as $prestamo) {

            foreach ($prestamo->creditos as $credito) {

                $abonado = $credito->abonos->sum('monto');
                $restante = $prestamo->total - $abonado;

                $creditos[] = [
                    'id' => $credito->id,
                    'total' => number_format($prestamo->total, 2),
                    'nombre' => $prestamo->cliente->nombre,
                    'telefono' => $prestamo->cliente->telefono,
                    'abonado' => number_format($abonado, 2),
                    'restante' => number_format($restante, 2),
                    'fecha' => $credito->created_at->format('Y-m-d H:i:s'),
                ];
            }
        }

        // Generar el contenido del ticket en HTML
        $html = View::make('admin.prestamos.creditos.reporte', compact('creditos'))->render();

        // Generar el PDF utilizando laravel-dompdf con orientación horizontal
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false);

        return $pdf->stream('reporte.pdf');
    }

    public function actualizarComentario(Request $request, $id)
    {
        try {
            $credito = Creditocliente::findOrFail($id);
            $credito->comentario = $request->comentario;
            $credito->save();

            return response()->json([
                'success' => true,
                'message' => 'Comentario actualizado correctamente.',
                'comentario' => $credito->comentario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el comentario: ' . $e->getMessage()
            ], 500);
        }
    }
}
