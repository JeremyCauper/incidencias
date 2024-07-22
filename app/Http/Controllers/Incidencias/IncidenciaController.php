<?php

namespace App\Http\Controllers\Incidencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidenciaController extends Controller
{
    public function countEmpresas()
    {
        try {
            $count = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=empresas'));
            return count($count);
        } catch (\Throwable $th) {
            return "Service Unavailable";
        }

    }
    
    /**
     * Display a listing of the resource.
     */
    public function DataTableIncidencias()
    {
        $usuarios = DB::table('usuarios')
            ->join('tipo_usuario', 'usuarios.tipo_acceso', '=', 'tipo_usuario.id_tipo_acceso')
            ->select('usuarios.ndoc_usuario', 'usuarios.id_usuario', 'usuarios.nombres', 'usuarios.apellidos', 'tipo_usuario.descripcion', 'usuarios.usuario', 'usuarios.pass_view', 'usuarios.estatus')
            ->where('usuarios.estatus', 1)
            ->get();

        foreach ($usuarios as $key => $val) {
            $val->nombres = explode(' ', $val->nombres)[0];
            $val->apellidos = explode(' ', $val->apellidos)[0];

            $val->id_usuario = '
            <div class="btn-group dropstart shadow-0">
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
                <div class="dropdown-menu shadow-6">
                    <h6 class="dropdown-header text-primary"><b>Acciones</b></h6>
                    <button class="dropdown-item py-2" onclick="showUsuario(' . $val->id_usuario . ')"><i class="fas fa-user-pen text-info me-2"></i> Editar</button>
                    <button class="dropdown-item py-2" onclick="cambiarEstado(' . $val->id_usuario . ', ' . $val->estatus . ')"><i class="fas fa-rotate text-danger me-2"></i> Cambiar Estado</button>
                </div>
            </div>';
            $val->estatus = '<label class="badge badge-' . ($val->estatus ? 'success' : 'danger') . '" style="font-size: .7rem;">' . ($val->estatus ? 'ACTIVO' : 'INACTIVO') . '</label>';
        }

        return $usuarios;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
