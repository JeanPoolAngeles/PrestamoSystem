<?php

namespace App\Http\Controllers;

use App\Models\Compania;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class ClienteController
 * @package App\Http\Controllers
 */
class CompaniaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compania = Compania::first();
        return view('admin.compania.index', compact('compania'));
    }

    public function update(Request $request, Compania $compania)
    {
        request()->validate(Compania::$rules);

        if ($request->hasFile('foto')) {
            $imagePath = $request->file('foto')->store('logo', 'public');
            // Eliminar la imagen anterior si existe
            if ($compania->foto) {
                Storage::disk('public')->delete($compania->foto);
            }
        } else {
            $imagePath = $compania->foto;
        }

        $compania->update(
            [
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'telefono' => $request->telefono,
                'RUC' => $request->RUC,
                'direccion' => $request->direccion,
                'foto' => $imagePath,
            ]
        );

        return redirect()->route('admin.compania.index')->with('success', 'Datos actualizado');
    }
}
