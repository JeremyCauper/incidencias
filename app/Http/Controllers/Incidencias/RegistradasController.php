<?php

namespace App\Http\Controllers\Incidencias;

use App\Http\Controllers\Consultas\ConsultasController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mantenimientos\ContactoEmpresasController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegistradasController extends Controller
{
    public function view()
    {
        try {
            $data = [];

            // Obtener información externa de la API
            $data['company'] = DB::table('tb_empresas')->where('status', 1)->get()->keyBy('id'); //$this->fetchAndParseApiData('empresas');
            $data['scompany'] = DB::table('tb_sucursales')->where('status', 1)->get()->keyBy('id'); //$this->fetchAndParseApiData('sucursales');

            // Obtener información de base de datos local
            $data['CargoContacto'] = $this->fetchAndParseDbData('cargo_contacto', ['id_cargo as id', 'descripcion', 'estatus']);
            $data['tEstacion'] = $this->fetchAndParseDbData('tb_tipo_estacion', ["id_tipo_estacion as id", 'descripcion', 'estatus']);
            $data['tSoporte'] = $this->fetchAndParseDbData('tb_tipo_soporte', ["id_tipo_soporte as id", 'descripcion', 'estatus']);
            $data['tIncidencia'] = $this->fetchAndParseDbData('tb_tipo_incidencia', ["id_tipo_incidencia as id", 'descripcion', 'estatus']);
            $data['problema'] = $this->fetchAndParseDbData('tb_problema', ["id_problema as id", 'tipo_incidencia', 'estatus'], "CONCAT(codigo, ' - ', descripcion) AS text");
            $data['sproblema'] = $this->fetchAndParseDbData('tb_subproblema', ["id_subproblema as id", 'id_problema', 'estatus'], "CONCAT(codigo_sub, ' - ', descripcion) AS text");
            $data['eContactos'] = (new ContactoEmpresasController())->index();

            $data['materiales'] = db::table('tb_materiales')->where('estatus', 1)->get()->map(function ($m) {
                return [
                    'value' => $m->id_materiales,
                    'dValue' => base64_encode(json_encode(['id_material' => $m->id_materiales, 'producto' => $m->producto, 'cantidad' => 0])),
                    'text' => $m->producto
                ];
            });

            $data['usuarios'] = db::table('usuarios')->where('estatus', 1)->get()->map(function ($u) {
                $nombre = ucwords(strtolower("{$u->nombres} {$u->apellidos}"));
                return [
                    'value' => $u->id_usuario,
                    'dValue' => base64_encode(json_encode(['id' => $u->id_usuario, 'doc' => $u->ndoc_usuario, 'nombre' => $nombre])),
                    'text' => "{$u->ndoc_usuario} - {$nombre}"
                ];
            });
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            $data['cod_orden'] = DB::select("CALL GetCodeOrds(24)")[0]->num_orden;


            // Cargar vista de las incidencias, junto a la variable data
            return view('incidencias.registradas', ['data' => $data]);
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Obtener información externa de la API
            $company = DB::table('tb_empresas')->get()->keyBy('id'); //$this->fetchAndParseApiData('empresas');
            $subcompany = DB::table('tb_sucursales')->get()->keyBy('id'); //$this->fetchAndParseApiData('sucursales');

            // Obtener información de base de datos local
            $tipoEstacion = $this->fetchAndParseDbData('tb_tipo_estacion', ["id_tipo_estacion as id", 'descripcion', 'estatus']);
            $tipoIncidencia = $this->fetchAndParseDbData('tb_tipo_incidencia', ["id_tipo_incidencia as id", 'descripcion', 'estatus']);
            $problema = $this->fetchAndParseDbData('tb_problema', ["id_problema as id", 'descripcion', 'estatus'], "CONCAT(codigo, ' - ', descripcion) AS text");
            $subproblema = $this->fetchAndParseDbData('tb_subproblema', ["id_subproblema as id", 'descripcion', 'estatus'], "CONCAT(codigo_sub, ' - ', descripcion) AS text");

            // Consultar incidencias
            $incidencias = DB::table('tb_incidencias')
                ->select(['cod_incidencia', 'id_empresa', 'id_sucursal', 'created_at', 'id_tipo_estacion', 'id_tipo_incidencia', 'id_problema', 'id_subproblema', 'estado_informe', 'id_incidencia as acciones', 'estatus'])
                ->where('estatus', 1)
                ->whereNot('estado_informe', 3)
                ->get();

            $_count = [
                "totales" => 0,
                "tAsignadas" => 0,
                "tSinAsignar" => 0,
                "tEnProceso" => 0,
            ];

            // Procesar incidencias
            $incidencias = $incidencias->map(function ($val) use (&$_count, $company, $subcompany, $tipoEstacion, $tipoIncidencia, $problema, $subproblema) {
                // Mapear datos
                $val->empresa = optional($company[$val->id_empresa])->razon_social;
                $val->sucursal = optional($subcompany[$val->id_sucursal])->nombre;
                $val->tipo_estacion = optional($tipoEstacion[$val->id_tipo_estacion])->descripcion;
                $val->tipo_incidencia = optional($tipoIncidencia[$val->id_tipo_incidencia])->descripcion;
                $val->problema = optional($problema[$val->id_problema])->descripcion;
                $val->subproblema = optional($subproblema[$val->id_subproblema])->descripcion;

                // Configurar el estado del informe
                $estadoInforme = [
                    "0" => ['c' => 'warning', 't' => 'Sin Asignar'],
                    "1" => ['c' => 'info', 't' => 'Asignada'],
                    "2" => ['c' => 'primary', 't' => 'En Proceso'],
                    "4" => ['c' => 'danger', 't' => 'Faltan Datos']
                ];
                $badge_informe = '<label class="badge badge-' . $estadoInforme[$val->estado_informe]['c'] . '" style="font-size: .7rem;">' . $estadoInforme[$val->estado_informe]['t'] . '</label>';

                switch ($val->estado_informe) {
                    case 0:
                        $_count['tSinAsignar']++;
                        break;
                    case 1:
                        $_count['tAsignadas']++;
                        break;
                    case 2:
                        $_count['tEnProceso']++;
                        break;
                }
                $_count['totales']++;

                // Generar acciones
                $val->acciones = $this->DropdownAcciones([
                    'tittle' => $badge_informe,
                    'button' => [
                        ['funcion' => "ShowDetail(this, '$val->cod_incidencia')", 'texto' => '<i class="fas fa-eye text-success me-2"></i> Ver Detalle'],
                        $val->estado_informe != 4 ? ['funcion' => "ShowEdit($val->acciones)", 'texto' => '<i class="fas fa-pen text-info me-2"></i> Editar'] : null,
                        $val->estado_informe != 4 ? ['funcion' => "ShowAssign(this, $val->acciones)", 'texto' => '<i class="fas fa-user-plus me-2"></i> Asignar'] : null,
                        $val->estado_informe == 1 ? ['funcion' => "StartInc('$val->cod_incidencia', $val->estado_informe)", 'texto' => '<i class="' . ($val->estado_informe != 2 ? 'far fa-clock' : 'fas fa-clock-rotate-left') . ' text-warning me-2"></i> ' . ($val->estado_informe != 2 ? 'Iniciar' : 'Reiniciar') . ' Incidencia'] : null,
                        $val->estado_informe == 2 ? ['funcion' => "OrdenDetail(this, '$val->acciones')", 'texto' => '<i class="fas fa-book-medical text-primary me-2"></i> Orden de servicio'] : null,
                        $val->estado_informe != 4 ? ['funcion' => "DeleteInc($val->acciones)", 'texto' => '<i class="far fa-trash-can text-danger me-2"></i> Eliminar'] : null,
                        $val->estado_informe == 4 ? ['funcion' => "AddCodAviso(this, '$val->cod_incidencia')", 'texto' => '<i class="far fa-file-code text-warning me-2"></i> Añadir Cod. Aviso'] : null,
                    ],
                ]);
                $val->badge_informe = $badge_informe;
                return $val;
            });

            return ['data' => $incidencias, 'count' => $_count];
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $idContact = 0;
            $validator = Validator::make($request->all(), [
                'cod_inc' => 'required|string',
                'id_empresa' => 'required|integer',
                'sucursal' => 'required|integer',
                'tEstacion' => 'required|integer',
                'prioridad' => 'required|string',
                'tSoporte' => 'required|integer',
                'tIncidencia' => 'required|integer',
                'problema' => 'required|integer',
                'sproblema' => 'required|integer',
                'observasion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i:s',
                'tel_contac' => 'nullable|string',
                'nro_doc' => 'nullable|string',
                'nom_contac' => 'nullable|string',
                'car_contac' => 'nullable|string',
                'cor_contac' => 'nullable|string'
            ]);

            if ($validator->fails())
                return response()->json([ 'success' => false, 'message' => '', 'validacion' => $validator->errors() ]);

            $personal = $request->personal;
            $estado_info = count($personal) ? 1 : 0;

            DB::beginTransaction();
            if ($request->tel_contac || $request->nom_contac || $request->car_contac) {
                $idContact = DB::table('contactos_empresas')->insertGetId([
                    'telefono' => $request->tel_contac,
                    'nro_doc' => $request->nro_doc ?: null,
                    'nombres' => $request->nom_contac,
                    'cargo' => $request->car_contac,
                    'correo' => $request->cor_contac ?: null,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
            }

            if ($estado_info) {
                $arr_personal = [];
                foreach ($personal as $k => $val) {
                    $arr_personal[$k]['cod_incidencia'] = $request->cod_inc;
                    $arr_personal[$k]['id_usuario'] = $val['id'];
                    $arr_personal[$k]['creador'] = Auth::user()->id_usuario;
                    $arr_personal[$k]['created_at'] = now()->format('Y-m-d H:i:s');
                }
                DB::table('tb_inc_asignadas')->insert($arr_personal);
            }

            DB::table('tb_incidencias')->insert([
                'cod_incidencia' => $request->cod_inc,
                'id_empresa' => $request->id_empresa,
                'id_sucursal' => $request->sucursal,
                'id_tipo_estacion' => $request->tEstacion,
                'prioridad' => $request->prioridad,
                'id_tipo_soporte' => $request->tSoporte,
                'id_tipo_incidencia' => $request->tIncidencia,
                'id_problema' => $request->problema,
                'id_subproblema' => $request->sproblema,
                'id_contacto' => $idContact ?: null,
                'observasion' => $request->observasion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe,
                'estado_informe' => $estado_info,
                'id_usuario' => Auth::user()->id_usuario,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            $data = [];
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            return response()->json([
                'success' => true,
                'message' => 'Registro exito.',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
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
        try {
            // Consultamos la incidencia por ID
            $incidencia = DB::table('tb_incidencias')->where('id_incidencia', $id)->first();
            $usuarios = db::table('usuarios')->select(['id_usuario', 'ndoc_usuario', 'nombres', 'apellidos'])->where('estatus', 1)->get()->keyBy('id_usuario');

            // Verificamos si se encontró la incidencia
            if (!$incidencia)
                return response()->json(['success' => false, 'message' => 'Incidencia no encontrada']);
            $cod = $incidencia->cod_incidencia;

            $empresa = DB::table('tb_empresas')->where('id', $incidencia->id_empresa)->first();
            $incidencia->codigo_aviso = $empresa->codigo_aviso;
            
            // Consultamos los contactos asociados a la incidencia y los añadimos como propiedades del objeto incidencia
            $contacto = DB::table('contactos_empresas')->where('id_contact', $incidencia->id_contacto)->first();
            if ($contacto) {
                foreach ((array) $contacto as $key => $value) {
                    $incidencia->$key = $value;
                }
            }

            $incidencia->personal_asig = DB::table('tb_inc_asignadas')->where('cod_incidencia', $cod)->get()->map(function ($u) use ($usuarios) {
                $nombre = ucwords(strtolower("{$usuarios[$u->id_usuario]->nombres} {$usuarios[$u->id_usuario]->apellidos}"));
                return [
                    'id' => $u->id_usuario,
                    'dni' => $usuarios[$u->id_usuario]->ndoc_usuario,
                    'tecnicos' => $nombre
                ];
            });

            // Retornamos la incidencia con la información de contacto y personal asignado
            return response()->json(['success' => true, 'message' => '', 'data' => $incidencia]);
        } catch (Exception $e) {
            // Manejamos errores y retornamos un mensaje de error claro
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_inc' => 'required|string',
                'id_empresa' => 'required|integer',
                'sucursal' => 'required|integer',
                'tEstacion' => 'required|integer',
                'prioridad' => 'required|string',
                'tSoporte' => 'required|integer',
                'tIncidencia' => 'required|integer',
                'problema' => 'required|integer',
                'sproblema' => 'required|integer',
                'observasion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i:s',
                'cod_contact' => 'nullable|integer',
                'tel_contac' => 'nullable|string',
                'nro_doc' => 'nullable|string',
                'nom_contac' => 'nullable|string',
                'car_contac' => 'nullable|string',
                'cor_contac' => 'nullable|string'
            ]);

            if ($validator->fails())
                return response()->json([ 'success' => false, 'message' => '', 'validacion' => $validator->errors() ]);

            $rContact = [
                'telefono' => $request->tel_contac,
                'nro_doc' => $request->nro_doc,
                'nombres' => $request->nom_contac,
                'cargo' => $request->car_contac,
                'correo' => $request->cor_contac
            ];
            $idContact = $request->cod_contact;

            DB::beginTransaction();
            if ($rContact['telefono'] || $rContact['nombres'] || $rContact['cargo']){
                if ($idContact) {
                    $rContact['updated_at'] = now()->format('Y-m-d H:i:s');
                    DB::table('contactos_empresas')->where('id_contact', $idContact)->update($rContact);
                } else {
                    $rContact['created_at'] = now()->format('Y-m-d H:i:s');
                    $idContact = DB::table('contactos_empresas')->insertGetId($rContact);
                }
            } else {
                $idContact = null;
            }

            DB::table('tb_incidencias')->where('id_incidencia', $id)->update([
                'cod_incidencia' => $request->cod_inc,
                'id_empresa' => $request->id_empresa,
                'id_sucursal' => $request->sucursal,
                'id_tipo_estacion' => $request->tEstacion,
                'prioridad' => $request->prioridad,
                'id_tipo_soporte' => $request->tSoporte,
                'id_tipo_incidencia' => $request->tIncidencia,
                'id_problema' => $request->problema,
                'id_subproblema' => $request->sproblema,
                'id_contacto' => $idContact,
                'observasion' => $request->observasion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe,
                'id_usuario' => Auth::user()->id_usuario,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            $cod_inc = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Edicion con éxito',
                'data' => ['cod_inc' => $cod_inc]
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    public function assignPer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_inc' => 'required|string'
            ]);
            if ($validator->fails())
                return response()->json([ 'success' => false, 'message' => '', 'validacion' => $validator->errors() ]);

            DB::beginTransaction();
            if ($request->estado)
                DB::table('tb_inc_asignadas')->where('cod_incidencia', $request->cod_inc)->delete();

            $estado_info = count($request->personal_asig) ? 1 : 0;
            if ($estado_info) {
                $personal = [];
                foreach ($request->personal_asig as $k => $val) {
                    $personal[$k]['cod_incidencia'] = $request->cod_inc;
                    $personal[$k]['id_usuario'] = $val['id'];
                    $personal[$k]['creador'] = Auth::user()->id_usuario;
                    $personal[$k]['fecha'] = now()->format('Y-m-d');
                    $personal[$k]['hora'] = now()->format('H:i:s');
                    $personal[$k]['updated_at'] = now()->format('Y-m-d H:i:s');
                    $personal[$k]['created_at'] = now()->format('Y-m-d H:i:s');
                }
                DB::table('tb_inc_asignadas')->insert($personal);
            }

            if ($request->estado)
                DB::table('tb_incidencias')->where('cod_incidencia', $request->cod_inc)->update(['estado_informe' => $estado_info]);

            $cod_inc = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Asignado con éxito',
                'data' => ['cod_inc' => $cod_inc, 'estado' => $request->estado ? $estado_info : 2]
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
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
        try {
            if (Auth::user()->tipo_acceso == 1) {
                DB::beginTransaction();
                DB::table('tb_incidencias')->where('id_incidencia', $id)->update(['estatus' => 0]);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'La incidencia se eliminó con exito'
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => 'No tiene los permisos requeridos'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    public function startInc(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'codigo' => 'required|string',
                'estado' => 'required|integer'
            ]);

            if ($validator->fails())
                return response()->json([ 'success' => false, 'message' => '', 'validacion' => $validator->errors() ]);

            $validacion = DB::table('tb_inc_asignadas')->where('cod_incidencia', $request->codigo)->count();
            if (!$validacion) {
                return response()->json(['success' => true, 'message' => 'No se puede iniciar la incidencia, ya que no tienen un tecnico asignado.'], 500);
            }
            DB::beginTransaction();
            $accion = $request->estado == 1 ? 2 : 1;
            $cod = $request->codigo;
            if ($accion == 1) {
                DB::table('tb_inc_seguimiento')->where('cod_incidencia', $cod)->delete();
            } else {
                DB::table('tb_inc_seguimiento')->insert([
                    'id_usuario' => Auth::user()->id_usuario,
                    'cod_incidencia' => $cod,
                    'fecha' => now()->format('Y-m-d'),
                    'hora' => now()->format('H:i:s'),
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
            }
            DB::table('tb_incidencias')->where('cod_incidencia', $cod)->update(['estado_informe' => $accion]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'La incidencia se ' . ($accion == 2 ? '' : 're') . 'inició con exito.'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    public function detail(string $cod)
    {
        try {
            // Consultas iniciales para obtener datos de la incidencia, asignaciones y seguimiento
            $incidencia = DB::table('tb_incidencias')->where(['estatus' => 1, 'cod_incidencia' => $cod])->first();
            $orden = DB::table('tb_orden_servicio')->where('cod_incidencia', $cod)->first();
            $asignados = DB::table('tb_inc_asignadas')->where('cod_incidencia', $cod)->get();
            $seguimiento = DB::table('tb_inc_seguimiento')->where('cod_incidencia', $cod)->get();
            // Obtenemos todos los usuarios activos y los almacenamos en un array asociativo por id
            $usuarios = DB::table('usuarios')->where('estatus', 1)->get()->keyBy('id_usuario')->map(function ($u) {
                $nombre = ucwords(strtolower("{$u->nombres} {$u->apellidos}"));
                return (object) [
                    "foto" => $u->foto_perfil,
                    "tecnico" => $nombre,
                    "email" => $u->email_corporativo,
                    "telefono" => $u->tel_corporativo
                ];
            });

            $incidencia->cod_orden = $orden ? $orden->cod_ordens : null;

            // Construcción del arreglo de datos
            $data = [
                $this->formatInfoData($usuarios, $incidencia->id_usuario, $incidencia->id_usuario, $incidencia->created_at, "Registró la incidencia")
            ];

            // Procesamos las asignaciones de la incidencia
            foreach ($asignados as $v) {
                $data[] = $this->formatInfoData($usuarios, $v->creador, $v->id_usuario, $v->created_at, "Asignó la Incidencia a <b>{$usuarios[$v->id_usuario]->tecnico}</b>");
            }

            // Procesamos el seguimiento de la incidencia
            foreach ($seguimiento as $v) {
                $estadoTexto = $v->estado ? "Finalizó la incidencia" : "Inició la incidencia";
                $data[] = $this->formatInfoData($usuarios, $v->id_usuario, $v->id_usuario, $v->created_at, $estadoTexto);
            }
            return response()->json(['success' => true, 'message' => '', 'data' => ['incidencia' => $incidencia, 'seguimiento' => $data]]);
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Formatea la información de contacto del usuario.
     */
    private function formatInfoData($usuario, $creador, $personal, $date, $text)
    {
        return [
            'img' => asset("front/images/auth/{$usuario[$creador]->foto}"),
            'nombre' => $usuario[$creador]->tecnico,
            'text' => $text,
            'contacto' => '<i class="fab fa-whatsapp text-success"></i> ' . $usuario[$personal]->telefono . ' / <i class="far fa-envelope text-danger"></i> ' . $usuario[$personal]->email,
            'date' => $date
        ];
    }

    public function searchCliente(string $dni)
    {
        $data = ['success' => true, 'message' => '', 'data' => []];
        $datos = [ 'id' => null, 'documento' => $dni, 'nombre' => null, 'firma_digital' => null, 'consulta' => true ];

        $cliente = DB::table('tb_contac_ordens')->where('nro_doc', $dni)->first();
        if ($cliente) {
            $datos['id'] = $cliente->id;
            $datos['nombre'] = $cliente->nombre_cliente;
            $datos['firma_digital'] = $cliente->firma_digital;
        } else {
            $rs = (new ConsultasController())->ConsultaDni($dni);
            $response = json_decode($rs->getContent(), true);

            if ($response['status'] == 200) {
                $datos['nombre'] = $response['data']['completo'];
                $datos['consulta'] = false;
            }
            else {
                $data['success'] = false;
                $data['message'] = $response['message'];
            }
        }

        if ($data['success']) $data['data'] = $datos;
        return $data;
    }
}
