<?php

namespace App\Helpers;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

class TipoSoporte extends Controller
{
    protected $filePath = 'config/jsons/tipo_soporte.json';

     /**
     * Obtiene todos los registros del JSON
     */
    public function all()
    {
        $file = storage_path($this->filePath);

        if (!file_exists($file)) {
            return [];
        }

        $content = file_get_contents($file);
        return json_decode($content, false);
    }



    /**
     * Busca un registro por ID
     */
    public function show($id)
    {
        $registro = collect($this->all())->select('id', 'descripcion', 'estatus')->firstWhere('id', $id);
        if (empty($registro)) {
            return $this->message(message: "El tipo de soporte buscado no exite.", status: 404);
        }
        return $registro;
    }

    /**
     * Guarda un nuevo registro
     */
    public function create(array $data)
    {
        $items = $this->all();

        // Asignar un nuevo ID automático
        $data['id'] = count($items) > 0 ? max(array_column($items, 'id')) + 1 : 1;
        $data['created_at'] = now()->format('Y-m-d H:i:s');

        $items[] = $data;

        file_put_contents(storage_path($this->filePath), json_encode($items, JSON_PRETTY_PRINT));
    }

    /**
     * Actualiza un registro por ID
     */
    public function update($id, array $newData)
    {
        $items = $this->all();

        foreach ($items as &$item) {
            if ($item->id == $id) { // Usamos -> en lugar de []
                foreach ($newData as $key => $value) {
                    $item->$key = $value; // Usamos -> para actualizar los valores
                }
                $item->updated_at = now()->format('Y-m-d H:i:s');
            }
        }

        file_put_contents(storage_path($this->filePath), json_encode($items, JSON_PRETTY_PRINT));
    }

    /**
     * Elimina un registro por ID
     */
    public function delete($id)
    {
        $items = array_filter($this->all(), fn($item) => $item->id != $id); // Usamos -> en lugar de []

        file_put_contents(storage_path($this->filePath), json_encode(array_values($items), JSON_PRETTY_PRINT));
    }
}