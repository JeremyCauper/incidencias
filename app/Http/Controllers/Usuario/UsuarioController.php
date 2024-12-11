<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function view()
    {
        try {
            $data = [];
            $data['areas'] = DB::table('tb_area')->where('estatus', 1)->get();
            $data['tipoAcceso'] = DB::table('tipo_usuario')->where('estatus', 1)->get();

            $menus = DB::table('tb_menu')->where('estatus', 1)->get();
            $submenus = DB::table('tb_submenu')->where('estatus', 1)->get();

            // Transformar los submenús en un mapa por id_menu para facilitar la búsqueda
            $submenusGrouped = $submenus->groupBy('id_menu');

            // Usar map para construir el resultado
            $data['menu'] = $menus->map(function ($m) use ($submenusGrouped) {
                return [
                    "id_m" => $m->id_menu,
                    "text" => $m->descripcion,
                    "icon" => $m->icon,
                    "submenu" => $m->submenu 
                        ? $submenusGrouped->get($m->id_menu, collect())->map(function ($sm) {
                            return [
                                "id_sm" => $sm->id_submenu,
                                "text" => $sm->descripcion
                            ];
                        })->toArray()
                        : []
                ];
            })->toArray();

            return view('usuario.usuario', $data);
        } catch (\Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $tipoAcceso = DB::table('tipo_usuario')->get()->keyBy('id_tipo_acceso');
        $usuarios = DB::table('usuarios')
            ->select('ndoc_usuario', 'id_usuario', 'nombres', 'apellidos', 'usuario', 'pass_view', 'estatus', 'tipo_acceso')
            ->where('estatus', 1)
            ->get()->map(function ($usu) use($tipoAcceso) {
                $usu->nombres = explode(' ', $usu->nombres)[0];
                $usu->apellidos = explode(' ', $usu->apellidos)[0];
                $usu->descripcion = $tipoAcceso[$usu->tipo_acceso]->descripcion;

                return $usu;
            });

        return $usuarios;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
