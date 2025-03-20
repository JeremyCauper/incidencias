<?php

namespace App\Helpers;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

class Menu extends Controller
{
    protected $filePath = 'config/jsons/menu.json';

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
}