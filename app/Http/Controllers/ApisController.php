<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApisController extends Controller
{
    public function consultaDni(Request $request)
    {
        Log::info('consultarDni called', ['request' => $request->all()]);

        $dni = $request->input('dni');
        $token = '*******';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v2/dni?numero=' . $dni,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json(['error' => 'Error al consultar el DNI']);
        } else {
            $data = json_decode($response, true);
            return response()->json($data);
        }
    }

    public function consultaRuc(Request $request)
    {
        Log::info('consultarRuc called', ['request' => $request->all()]);

        $ruc = $request->input('ruc');
        $token = '******';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v2/ruc?numero=' . $ruc,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json(['error' => 'Error al consultar el DNI']);
        } else {
            $data = json_decode($response, true);
            return response()->json($data);
        }
    }
}
