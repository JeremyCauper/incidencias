<?php

namespace App\Http\Controllers\Soporte\Inicio;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function view()
    {
        try {
            return view('soporte.inicio.panel');
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
