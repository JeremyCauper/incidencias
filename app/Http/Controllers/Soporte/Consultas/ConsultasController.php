<?php

namespace App\Http\Controllers\Soporte\Consultas;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class ConsultasController extends Controller
{
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
            "completo" => '/<input type="text" id="completos" value="([^"]+)"/',
            "nombres" => '/<input type="text" id="nombres" value="([^"]+)"/',
            "apellidop" => '/<input type="text" id="apellidop" value="([^"]+)"/',
            "apellidom" => '/<input type="text" id="apellidom" value="([^"]+)"/',
            //"dni_digito" => '/<input type="text" id="dni_digito" value="([^"]+)"/',
            //"ruc10" => '/<input type="text" id="ruc10" value="([^"]+)"/'
        ];

        $data = ['tipDoc' => 1];
        foreach ($tags_search as $key => $val) {
            if (preg_match($val, $contenido, $matches)) {
                //if ($key != 'dni_digito') {
                $data[$key] = $matches[1];
                /*} else {
                    list($data['dni'], $data['codVeri']) = explode('-', $matches[1]);
                }*/
            } else {
                return $this->Msg_json(false, 4006, 'Error al extraer datos del contenido');
            }
        }

        return $this->Msg_json(true, 200, 'Documento encontrado', $data);
    }

    public function ConsultaDoc(Request $request)
    {
        try {
            $doc = $request->query('doc');

            if (empty($doc) || !ctype_digit($doc)) {
                return $this->message(data: ['error' => 'Número de documento inválido'], status: 400);
            }

            $ip = env('APP_ENV') == "local" ? "192.168.1.113" : "190.187.193.78";

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "http://$ip:8282/apugescom.api/api/ConsultaExterna/Consulta?numDoc=$doc",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ]);

            $responseRaw = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
                curl_close($curl);
                return $this->message(data: ['error' => "Error al consultar el servicio: $error_msg"], status: 502);
            }
            curl_close($curl);

            $response = json_decode($responseRaw);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->message(data: ['error' => 'Respuesta JSON inválida del servicio externo'], status: 500);
            }

            if (!empty($response) && strlen($doc) == 8 && !empty($response->EstadoErrorConsulta) && isset($response->RazonSocialCliente)) {
                $rsCliente = explode(" ", $response->RazonSocialCliente);
                $apellidos = array_splice($rsCliente, -2);

                $response->Nombres = implode(" ", $rsCliente);
                $response->ApePaterno = $apellidos[0] ?? '';
                $response->ApeMaterno = $apellidos[1] ?? '';
                $EstadoErrorConsulta = $response->EstadoErrorConsulta;
            } else {
                $EstadoErrorConsulta = false;
            }

            return $this->message(
                status: $EstadoErrorConsulta ? 200 : 400,
                data: ['message' => $EstadoErrorConsulta ? 'Consulta exitosa' : 'Documento consultado invalido', 'data' => $response]
            );
        } catch (Exception $e) {
            return $this->message(data: [
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], status: 500);
        }
    }

    private function Msg_json(bool $success, int $status, string $message, $data = [])
    {
        return response()->json(["success" => $success, "status" => $status, "message" => $message, "data" => $data]);
    }
}
