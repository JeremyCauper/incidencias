<?php

namespace App\Http\Controllers\Incidencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidenciaController extends Controller
{
    public function resumenInc()
    {
        try {
            $data = [
                'empresas' => [],
                'sucursales' => []
            ];
            $empresas = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=empresas'));
            foreach ($empresas as $val) {
                array_push($data['empresas'], ['id' => $val->id, 'ruc' => $val->Ruc, 'empresa' => $val->Ruc . ' - ' . $val->RazonSocial]);
            }
            $sucursales = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=sucursales'));
            foreach ($sucursales as $val) {
                $data['sucursales'][$val->ruc][] = ['id' => $val->id, 'sucursal' => $val->Nombre];
            }
            $usuarios = DB::table('usuarios')->where('estatus', 1)->get();
            foreach ($usuarios as $val) {
                $data['usuarios'][] = ['value' => $val->id_usuario . "|" . $val->ndoc_usuario . "|" . $val->nombres . " " . $val->apellidos, 'text' => $val->ndoc_usuario . " - " . $val->nombres . " " . $val->apellidos];
            }
            $data['cargo_contaco'] = DB::table('cargo_contacto')->select('descripcion')->where('estatus', 1)->get();
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            $data['cEmpresa'] = count($empresas);
            $data['cSucursal'] = count($sucursales);
            return $data;
        } catch (\Throwable $th) {
            return "Service Unavailable : " . $th;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function DataTableInc()
    {
        $data = [];
        $empresas = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=empresas'));

        $sucursales = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=sucursales'));

        $usuarios = DB::table('usuarios')
            ->join('tipo_usuario', 'usuarios.tipo_acceso', '=', 'tipo_usuario.id_tipo_acceso')
            ->select('usuarios.ndoc_usuario', 'usuarios.id_usuario', 'usuarios.nombres', 'usuarios.apellidos', 'tipo_usuario.descripcion', 'usuarios.usuario', 'usuarios.pass_view', 'usuarios.estatus')
            ->where('usuarios.estatus', 1)
            ->get();

        /*foreach ($usuarios as $key => $val) {
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
        }*/

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
        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Insertar en la primera tabla
            $id_primera_tabla = DB::table('primera_tabla')->insertGetId([
                'columna1' => 'valor1',
                'columna2' => 'valor2',
            ]);

            // Insertar en la segunda tabla
            DB::table('segunda_tabla')->insert([
                'columna1' => 'valor1',
                'columna2' => 'valor2',
                'id_primera_tabla' => $id_primera_tabla,
            ]);

            // Confirmar transacción
            DB::commit();
        } catch (\Exception $e) {
            // Deshacer transacción si ocurre un error
            DB::rollBack();
            throw $e;
        }
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
