<?php

namespace App\Helpers;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

class CargoEstacion extends Controller
{
    protected $filePath = 'config/jsons/cargo_estacion.json';

    /**
     * Obtiene todos los registros del JSON
     */
    public function all()
    {
        if (!Storage::exists($this->filePath)) {
            return [];
        }

        return json_decode(Storage::get($this->filePath), false); 
    }
    

    /**
     * Busca un registro por ID
     */
    public function show($id)
    {
        $registro = collect($this->all())->select('id', 'descripcion', 'estatus')->firstWhere('id', $id);
        if (empty($registro)) {
            return $this->message(message: "El tipo de estacion buscada no exite.", status: 404);
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
        Storage::put($this->filePath, json_encode($items, JSON_PRETTY_PRINT));
    }

    /**
     * Actualiza un registro por ID
     */
    public function update($id, array $newData)
    {
        $items = $this->all();

        foreach ($items as &$item) {
            if ($item['id'] == $id) {
                $item = array_merge($item, $newData);
                $item['updated_at'] = now()->format('Y-m-d H:i:s');
            }
        }

        Storage::put($this->filePath, json_encode($items, JSON_PRETTY_PRINT));
    }

    /**
     * Elimina un registro por ID
     */
    public function delete($id)
    {
        $items = array_filter($this->all(), fn($item) => $item['id'] != $id);
        Storage::put($this->filePath, json_encode(array_values($items), JSON_PRETTY_PRINT));
    }
}