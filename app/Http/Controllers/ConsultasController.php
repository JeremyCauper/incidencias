<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsultasController extends Controller
{
    /*public function ConsultaDni(int $dni) {
        $curl = curl_init();
        $url = 'https://eldni.com/pe/buscar-datos-por-dni';
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookies_temp.txt');
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookies_temp.txt');
        
        if (curl_exec($curl) === false)
            die($this->Msg_json(success: false, cod_error: 4001, message: 'No autorizado'));
        
        if (!preg_match('/<input type="hidden" name="_token" value="([^"]+)"/', curl_exec($curl), $obtener_token))
            die($this->Msg_json(success: false, cod_error: 4005, message: 'Error al intentar obtener el token' . curl_error($curl)));
        $token = $obtener_token[1];
        
        $postData = array('_token' => $token, 'dni' => $dni);
        
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
        
        $contenido = curl_exec($curl);
        
        if ($contenido === false)
            die($this->Msg_json(success: false, cod_error: 4004, message: 'Error al enviar datos: ' . curl_error($curl)));
        
        curl_close($curl);

        if (preg_match('/<h3 class="text-error"/', $contenido, $matches_error))
            die($this->Msg_json(success: false, cod_error: 4022, message: 'No se encontraron datos para el DNI que ingresaste.'));

        $tags_search = array(
            "completos" => '/<input type="text" id="completos" value="([^"]+)"/',
            "nombres" => '/<input type="text" id="nombres" value="([^"]+)"/',
            "apellidop" => '/<input type="text" id="apellidop" value="([^"]+)"/',
            "apellidom" => '/<input type="text" id="apellidom" value="([^"]+)"/',
            "dni_digito" => '/<input type="text" id="dni_digito" value="([^"]+)"/',
            "ruc10" => '/<input type="text" id="ruc10" value="([^"]+)"/'
        );

        $data = [];
        $data['tipo_documento'] = 1;
        foreach ($tags_search as $key => $val) {
            if (preg_match($val, $contenido, $matches)) {
                if ($key != 'dni_digito')
                    $data[$key] = $matches[1];
                else {
                    $data['dni'] = explode('-', $matches[1])[0];
                    $data['cod_verificador'] = explode('-', $matches[1])[1];
                }
            } else {
                $data = [];
                break;
            }
        }

        return response()->json(["success" => true, "message" => 'Documento encontrado', "data" => $data], 200);
    }*/

    public function ConsultaDni(string $dni)
    {
        $curl = curl_init();
        $url = 'https://eldni.com/pe/buscar-datos-por-dni';
        $cookieFile = 'cookies_temp.txt';

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_COOKIEJAR => $cookieFile,
        ]);

        $contenido = curl_exec($curl);

        if ($contenido === false) {
            return $this->Msg_json(false, 4001, 'No autorizado');
        }

        if (!preg_match('/<input type="hidden" name="_token" value="([^"]+)"/', $contenido, $obtener_token)) {
            return $this->Msg_json(false, 4005, 'Error al intentar obtener el token: ' . curl_error($curl));
        }

        $token = $obtener_token[1];
        $postData = ['_token' => $token, 'dni' => $dni];

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
        ]);

        $contenido = curl_exec($curl);

        if ($contenido === false) {
            curl_close($curl);
            return $this->Msg_json(false, 4004, 'Error al enviar datos: ' . curl_error($curl));
        }

        curl_close($curl);

        if (preg_match('/<h3 class="text-error"/', $contenido)) {
            return $this->Msg_json(false, 4022, 'No se encontraron datos para el DNI que ingresaste.');
        }

        $tags_search = [
            "complet" => '/<input type="text" id="completos" value="([^"]+)"/',
            "nom" => '/<input type="text" id="nombres" value="([^"]+)"/',
            "apep" => '/<input type="text" id="apellidop" value="([^"]+)"/',
            "apem" => '/<input type="text" id="apellidom" value="([^"]+)"/',
            "dni_digito" => '/<input type="text" id="dni_digito" value="([^"]+)"/',
            "ruc10" => '/<input type="text" id="ruc10" value="([^"]+)"/'
        ];

        $data = ['tipDoc' => 1];
        foreach ($tags_search as $key => $val) {
            if (preg_match($val, $contenido, $matches)) {
                if ($key != 'dni_digito') {
                    $data[$key] = $matches[1];
                } else {
                    list($data['dni'], $data['codVeri']) = explode('-', $matches[1]);
                }
            } else {
                return $this->Msg_json(false, 4006, 'Error al extraer datos del contenido');
            }
        }

        return $this->Msg_json(true, 200, 'Documento encontrado', $data);
    }

    private function Msg_json(bool $success, int $status, string $message, $data = [])
    {
        return response()->json(["success" => $success, "status" => $status, "message" => $message, "data" => $data]);
    }
}
