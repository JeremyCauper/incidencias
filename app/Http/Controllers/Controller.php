<?php

namespace App\Http\Controllers;

use Exception as error;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function DropdownAcciones($arr_acciones)
    {
        // Validaciones
        if (!is_array($arr_acciones)) {
            throw new error("El parámetro enviado tiene que ser un array");
        }

        if (empty($arr_acciones)) {
            throw new error("El array no puede estar vacío");
        }

        if (!array_key_exists('button', $arr_acciones)) {
            throw new error("La clave 'button' no existe en el array.");
        }

        // Título del dropdown
        $str_title = '<h6 class="dropdown-header text-secondary d-flex justify-content-between align-items-center">:titulo <i class="fas fa-gear"></i></h6>';
        $tittle = str_replace(":titulo", $arr_acciones['tittle'] ?? "Acciones", $str_title);

        // Botones del dropdown
        $str_button = '<button class="dropdown-item py-2 :claseb" onclick=":funcion">:texto</button>';
        $button = '';

        foreach ($arr_acciones['button'] as $val) {
            if ($val) {
                $arr_btn = [
                    ':claseb' => $val['claseb'] ?? '',
                    ':funcion' => $val['funcion'] ?? "alert('prueba de alerta')",
                    ':texto' => $val['texto'] ?? 'Alerta',
                ];
                $button .= str_replace(array_keys($arr_btn), array_values($arr_btn), $str_button);
            }
        }

        // Estructura del dropdown
        $dropDown = '
            <div class="btn-group dropup shadow-0">
                <button
                    type="button"
                    class="btn btn-tertiary hover-btn btn-sm px-2 shadow-0"
                    data-mdb-ripple-init
                    aria-expanded="false"
                    data-mdb-dropdown-init
                    data-mdb-ripple-color="dark"
                    data-mdb-dropdown-initialized="true">
                    <b><i class="icon-menu9"></i></b>
                </button>
                <div class="dropdown-menu">
                    ' . $tittle . $button . '
                </div>
            </div>';

        return $dropDown;
    }

    public static function getParseData($data)
    {
        $arr_data = [];
        foreach ($data as $val) {
            $arr_data[$val->id] = $val;
        }
        return $arr_data;
    }

    /**
     * Fetch and parse data from API.
     */
    public function fetchAndParseApiData($tabla)
    {
        $url = "https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla={$tabla}";
        $data = json_decode(file_get_contents($url));
        return $this->getParseData($data);
    }

    /**
     * Fetch and parse data from Database.
     */
    public function fetchAndParseDbData($table, $selectFields, $withConcat = false)
    {
        if ($withConcat) {
            $selectFields[] = DB::raw($withConcat);
        }
        $data = DB::table($table)->select($selectFields)->where('estatus', 1)->get();
        return $this->getParseData($data);
    }

    public function parseCreateFile($name, $dir, $data)
    {
        $foto_b64 = explode(',', base64_decode($data));
        $imgInfo = base64_decode($foto_b64[1]);
        $imgFormat = getimagesizefromstring($imgInfo);
        $formato = '.' . explode('/', $imgFormat['mime'])[1];
        $filename = $name . $formato;

        $path = public_path("front/images/$dir");
        $filePath = "$path/$filename";

        if (file_put_contents($filePath, $imgInfo)) {
            return ['success' => true, 'filename' => $filename];
        }
        return ['success' => false];
    }

    public function mesageError(
        error $exception,
        $message = "Hubo un problema en el servidor. Estamos trabajando para solucionarlo. Por favor, inténtalo más tarde o contacta con soporte si persiste.",
        $codigo = 200
    ) {
        $error = "Error inesperado - linea {$exception->getLine()}: {$exception->getMessage()}";
        Log::error($error);
        return response()->json(["success" => false, "message" => $message, "error" => $error], $codigo);
    }
}
