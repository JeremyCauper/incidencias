<?php

namespace App\Http\Controllers;

use App\Helpers\Menu;
use App\Helpers\SubMenu;
use Exception as error;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function obtenerModulos($jsonBase64, $tipo_acceso)
    {
        // Decodificar el JSON base64 recibido con los IDs a filtrar
        $jsonString = base64_decode($jsonBase64);
        $filteredIds = json_decode($jsonString, true);
    
        // Definir los tipos de menú permitidos según el tipo de acceso
        $tipo_menu = [0];
        if ($tipo_acceso == 5) {
            array_push($tipo_menu, 1);
        }
    
        // Obtener y filtrar los menús del JSON
        $menu = collect((new Menu())->all())
            ->filter(function ($item) use ($tipo_menu, $filteredIds) {
                return $item->estatus == 1 &&
                       in_array($item->sistema, $tipo_menu) &&
                       array_key_exists($item->id, $filteredIds);
            })
            ->sortBy('orden')
            ->values();
    
        // Obtener y filtrar los submenús del JSON
        $submenus = collect((new SubMenu())->all())
            ->filter(function ($item) use ($filteredIds) {
                // Se valida que el menú padre (id_menu) esté dentro de los IDs filtrados
                return $item->estatus == 1 && isset($filteredIds[$item->id_menu]);
            })
            ->filter(function ($item) use ($filteredIds) {
                // Si en el JSON filtrado se especificaron submenús para el menú,
                // se valida que el submenú esté incluido
                $submenuIds = $filteredIds[$item->id_menu];
                if (!empty($submenuIds)) {
                    return in_array($item->id, $submenuIds);
                }
                return true;
            })
            ->groupBy('id_menu');
    
        // Determinar la ruta principal
        $rutaPrincipal = null;
        if ($menu->isNotEmpty()) {
            $primerMenu = $menu->first();
            // Si el JSON indica que este menú posee submenús y se tienen registros filtrados
            if (!empty($filteredIds[$primerMenu->id]) && $submenus->has($primerMenu->id)) {
                $primerSubmenu = $submenus[$primerMenu->id]->first();
                $rutaPrincipal = $primerSubmenu->ruta;
            } else {
                $rutaPrincipal = $primerMenu->ruta;
            }
        }
    
        // Combinar menús y submenús en la estructura deseada
        $menus = $menu->map(function ($item) use ($submenus, $filteredIds) {
            $menuId = $item->id;
    
            // Si el JSON dice que este menú no tiene submenús, se deja la propiedad vacía
            if (empty($filteredIds[$menuId])) {
                $item->submenu = [];
                return $item;
            }
    
            // Si existen submenús para este menú, agruparlos por categoría
            if ($submenus->has($menuId)) {
                $groupedByCategory = $submenus[$menuId]->groupBy(function ($submenu) {
                    return $submenu->categoria ?: 'sin_categoria';
                });
    
                // Se reasigna la propiedad "submenu" con los submenús agrupados y reindexados
                $item->submenu = $groupedByCategory->map(function ($submenusList) {
                    return $submenusList->values();
                });
            } else {
                $item->submenu = [];
            }
            return $item;
        });
    
        // Retornar la estructura con los menús y la ruta principal
        return (object)[
            "menus" => $menus,
            "ruta" => $rutaPrincipal
        ];
    }
    

    public function validarPermisos($menu, $submenu = "")
    {
        $arrayString = base64_decode(session('menu_usuario'));
        $modulos = json_decode($arrayString);
    
        if (isset($modulos->$menu)) {
            if (!empty($modulos->$menu)) {
                if (isset($modulos->$menu[$submenu])) {
                    return;
                }
            }
            return;
        }
        abort(403);
    }
    

    public function formatearNombre(...$args) {
        if (count($args) == 1) {
            // Si se pasa un solo argumento, separamos nombres y apellidos
            $partes = explode(" ", trim($args[0]));
            $cantidad = count($partes);
    
            if ($cantidad < 2) {
                return ucfirst(strtolower($args[0])); // Si solo hay una palabra, la devuelve con formato
            }
    
            $apellidos = array_slice($partes, -2); // Últimos dos elementos como apellidos
            $nombres = array_slice($partes, 0, -2); // El resto como nombres
        } else {
            // Si se pasan nombres y apellidos por separado
            $nombres = explode(" ", trim($args[0]));
            $apellidos = explode(" ", trim($args[1]));
        }
    
        $primerNombre = ucfirst(strtolower($nombres[0])); // Primer nombre con mayúscula inicial
        $primerApellido = ucfirst(strtolower($apellidos[0])); // Primer apellido con mayúscula inicial
        $inicialSegundoApellido = isset($apellidos[1]) ? strtoupper(substr($apellidos[1], 0, 1)) . '.' : ''; // Inicial del segundo apellido
    
        return trim("$primerNombre $primerApellido $inicialSegundoApellido");
    }
    

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
        try {
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
        } catch (\Throwable $th) {
            return ['success' => false, 'error' => $th->getMessage()];
        }
    }

    public function message($title = "", $message = "", $data = null, $status = 200, $e = null)
    {
        $response = ["success" => $status == 200 ? true : false, "title" => $title, "message" => $message, "status" => $status];
        $statuses = [
            "success" => ["title" => "Éxito", "range" => range(200, 201)],
            "info" => ["title" => "Atención", "range" => range(202, 399)],
            "warning"   => ["title" => "Proceso Fallido", "range" => range(400, 599)],
            "error"  => ["title" => "Error interno del Servidor", "range" => range(500, 599)],
        ];
        if (!empty($data)) {
            foreach ($data as $clave => $value) {
                $response[$clave] = $value;
                if (empty($message) && $clave == 'error') {
                    $response["success"] = false;
                    $response["message"] = "Hubo un problema en el servidor. Estamos trabajando para solucionarlo lo antes posible. Por favor, intenta de nuevo más tarde.";
                }
            }
        }
        foreach ($statuses as $icon => $info) {
            if (in_array($status, $info["range"])) {
                $response["icon"] = $icon;
                if (empty($title) ) {
                    $response["title"] = $info["title"];
                }
                break;
            }
        }
        if (empty($message)) {
            switch ($status) {
                case 500:
                    $response["message"] = "Ha ocurrido un error inesperado en el servidor.";
                    break;
                
                case 503:
                    $response["message"] = "El servidor está en mantenimiento o sobrecargado.";
                    break;
                
                case 422:
                    $response["title"] = "Datos inválidos (errores de validación).";
                    break;
            }
        }
        return response()->json($response, (int)$status);
    }

    public function validatorUnique($errorMessage, $data)
    {
        $datos = [];
        foreach ($data as $key => $value) {
            if (str_contains($errorMessage, "key '$key'")) {
                $datos[] = $value;
            }
        }
        return $datos;
    }
}
