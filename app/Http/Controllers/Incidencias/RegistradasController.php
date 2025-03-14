<?php

namespace App\Http\Controllers\Incidencias;

use App\Helpers\CargoEstacion;
use App\Helpers\TipoEstacion;
use App\Helpers\TipoIncidencia;
use App\Helpers\TipoSoporte;
use App\Http\Controllers\Consultas\ConsultasController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mantenimientos\ContactoEmpresasController;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegistradasController extends Controller
{
    private $validator = [
        "cod_incidencia" => "cod_inc",
    ];

    public function view()
    {
        $this->validarPermisos(1);
        try {
            $data = [];

            // Obtener información externa de la API
            $data['company'] = DB::table('tb_empresas')->select(['id', 'ruc', 'razon_social', 'direccion', 'contrato', 'codigo_aviso', 'status'])->get()->keyBy('ruc'); //$this->fetchAndParseApiData('empresas');
            $data['scompany'] = DB::table('tb_sucursales')->select(['id', 'ruc', 'nombre', 'direccion', 'status'])->get()->keyBy('id'); //$this->fetchAndParseApiData('sucursales');

            // Obtener información de base de datos local
            $data['CargoEstacion'] = collect((new CargoEstacion())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['tEstacion'] = collect((new TipoEstacion())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['tSoporte'] = collect((new TipoSoporte())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['tIncidencia'] = collect((new TipoIncidencia())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['problema'] = $this->fetchAndParseDbData('tb_problema', ["id_problema as id", 'tipo_incidencia', 'estatus'], "CONCAT(codigo, ' - ', descripcion) AS text");
            $data['sproblema'] = $this->fetchAndParseDbData('tb_subproblema', ["id_subproblema as id", 'id_problema', 'estatus'], "CONCAT(codigo_sub, ' - ', descripcion) AS text");
            $data['eContactos'] = DB::table('contactos_empresas')->where('estatus', 1)->get()->keyBy('telefono');

            $data['materiales'] = db::table('tb_materiales')->where('estatus', 1)->get()->map(function ($m) {
                return [
                    'value' => $m->id_materiales,
                    'dValue' => base64_encode(json_encode(['id_material' => $m->id_materiales, 'producto' => $m->producto, 'cantidad' => 0])),
                    'text' => $m->producto
                ];
            });

            $data['usuarios'] = db::table('usuarios')->where('estatus', 1)->get()->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
                return [
                    'value' => $u->id_usuario,
                    'dValue' => base64_encode(json_encode(['id' => $u->id_usuario, 'doc' => $u->ndoc_usuario, 'nombre' => $nombre])),
                    'text' => "{$u->ndoc_usuario} - {$nombre}"
                ];
            });
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            $data['cod_orden'] = DB::select('CALL GetCodeOrds(?)', [date('y')])[0]->num_orden;


            // Cargar vista de las incidencias, junto a la variable data
            return view('incidencias.registradas', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $contactos_empresas = DB::table('contactos_empresas')->where('estatus', 1)->get()->keyBy('telefono');
            $conteo_data = [
                "totales" => 0,
                "tAsignadas" => 0,
                "tSinAsignar" => 0,
                "tEnProceso" => 0,
            ];

            // Procesar incidencias
            $incidencias = DB::table('tb_incidencias')
                ->select(['cod_incidencia', 'ruc_empresa', 'id_sucursal', 'created_at', 'id_tipo_estacion', 'id_tipo_incidencia', 'id_problema', 'id_subproblema', 'estado_informe', 'id_incidencia as id', 'estatus'])
                ->where('estatus', 1)
                ->whereNot('estado_informe', 3)
                ->get()->map(function ($val) use (&$conteo_data) {

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
                            $conteo_data['tSinAsignar']++;
                            break;
                        case 1:
                            $conteo_data['tAsignadas']++;
                            break;
                        case 2:
                            $conteo_data['tEnProceso']++;
                            break;
                    }
                    $conteo_data['totales']++;

                    return [
                        'incidencia' => $val->cod_incidencia,
                        'empresa' => $val->ruc_empresa,
                        'sucursal' => $val->id_sucursal,
                        'tipo_estacion' => $val->id_tipo_estacion,
                        'tipo_incidencia' => $val->id_tipo_incidencia,
                        'problema' => $val->id_problema,
                        'subproblema' => $val->id_subproblema,
                        'estado_informe' => $val->estado_informe,
                        'estado' => $badge_informe,
                        'registrado' => $val->created_at,
                        'acciones' => $this->DropdownAcciones([
                            'tittle' => $badge_informe,
                            'button' => [
                                ['funcion' => "ShowDetail(this, '$val->cod_incidencia')", 'texto' => '<i class="fas fa-eye text-info me-2"></i> Ver Detalle'],
                                $val->estado_informe != 4 ? ['funcion' => "ShowEdit('$val->cod_incidencia')", 'texto' => '<i class="fas fa-pen text-secondary me-2"></i> Editar'] : null,
                                $val->estado_informe != 4 ? ['funcion' => "ShowAssign(this, '$val->cod_incidencia')", 'texto' => '<i class="fas fa-user-plus me-2"></i> Asignar'] : null,
                                $val->estado_informe == 1 ? ['funcion' => "StartInc('$val->cod_incidencia', $val->estado_informe)", 'texto' => '<i class="' . ($val->estado_informe != 2 ? 'far fa-clock' : 'fas fa-clock-rotate-left') . ' text-warning me-2"></i> ' . ($val->estado_informe != 2 ? 'Iniciar' : 'Reiniciar') . ' Incidencia'] : null,
                                $val->estado_informe == 2 ? ['funcion' => "OrdenDetail(this, '$val->cod_incidencia')", 'texto' => '<i class="fas fa-book-medical text-primary me-2"></i> Orden de servicio'] : null,
                                $val->estado_informe != 4 ? ['funcion' => "DeleteInc($val->id)", 'texto' => '<i class="far fa-trash-can text-danger me-2"></i> Eliminar'] : null,
                                $val->estado_informe == 4 ? ['funcion' => "AddCodAviso(this, '$val->cod_incidencia')", 'texto' => '<i class="far fa-file-code text-warning me-2"></i> Añadir Cod. Aviso'] : null,
                            ],
                        ])
                    ];
                });

            return ['data' => $incidencias, 'conteo_data' => $conteo_data, 'contact' => $contactos_empresas];
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
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
                'empresa' => 'required|string',
                'sucursal' => 'required|integer',
                'tEstacion' => 'required|integer',
                'prioridad' => 'required|string',
                'tSoporte' => 'required|integer',
                'tIncidencia' => 'required|integer',
                'problema' => 'required|integer',
                'sproblema' => 'required|integer',
                'observacion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i:s',
                'tel_contac' => 'nullable|string',
                'nro_doc' => 'nullable|string',
                'nom_contac' => 'nullable|string',
                'car_contac' => 'nullable|string',
                'cor_contac' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

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
                    if ($val['registro']) {
                        $arr_personal[$k]['cod_incidencia'] = $request->cod_inc;
                        $arr_personal[$k]['id_usuario'] = $val['id'];
                        $arr_personal[$k]['creador'] = Auth::user()->id_usuario;
                        $arr_personal[$k]['fecha'] = now()->format('Y-m-d');
                        $arr_personal[$k]['hora'] = now()->format('H:i:s');
                        $arr_personal[$k]['created_at'] = now()->format('Y-m-d H:i:s');
                        $personal[$val['id']]['registro'] = 0;
                    }
                }
                DB::table('tb_inc_asignadas')->insert($arr_personal);
            }

            DB::table('tb_incidencias')->insert([
                'cod_incidencia' => $request->cod_inc,
                'ruc_empresa' => $request->empresa,
                'id_sucursal' => $request->sucursal,
                'id_tipo_estacion' => $request->tEstacion,
                'prioridad' => $request->prioridad,
                'id_tipo_soporte' => $request->tSoporte,
                'id_tipo_incidencia' => $request->tIncidencia,
                'id_problema' => $request->problema,
                'id_subproblema' => $request->sproblema,
                'id_contacto' => $idContact ?: null,
                'observacion' => $request->observacion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe,
                'estado_informe' => $estado_info,
                'id_usuario' => Auth::user()->id_usuario,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            $data = [];
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;

            return $this->message(message: "Registro creado exitosamente.", data: ['data' => $data]);
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {
                $response = $this->validatorUnique($e->getMessage(), $this->validator);
                return $this->message(data: ['unique' => $response], status: 422);
            }
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
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
    public function show(string $cod)
    {
        try {
            // Consultamos la incidencia por ID
            $incidencia = DB::table('tb_incidencias')->where('cod_incidencia', $cod)->first();

            // Verificamos si se encontró la incidencia
            if (!$incidencia)
                return $this->message(message: "Incidencia no encontrada", status: 204);

            // Consultamos los contactos asociados a la incidencia y los añadimos como propiedades del objeto incidencia
            $contacto = DB::table('contactos_empresas')->where('id_contact', $incidencia->id_contacto)->first();
            if ($contacto) {
                foreach ((array) $contacto as $key => $value) {
                    $incidencia->$key = $value;
                }
            }

            $asignados = DB::table('tb_inc_asignadas')->where('cod_incidencia', $cod)->pluck('id_usuario')->toArray();
            $incidencia->personal_asig = DB::table('usuarios')
                ->whereIn('id_usuario', $asignados)
                ->select(['id_usuario', 'ndoc_usuario', 'nombres', 'apellidos'])->get()->map(function ($usu) {
                    $nombre = $this->formatearNombre($usu->nombres, $usu->apellidos);
                    return [
                        'id' => $usu->id_usuario,
                        'dni' => $usu->ndoc_usuario,
                        'tecnicos' => $nombre
                    ];
                });

            // Retornamos la incidencia con la información de contacto y personal asignado
            return $this->message(data: ['data' => $incidencia]);
        } catch (QueryException $e) {
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_inc' => 'required|integer',
                'cod_inc' => 'required|string',
                'empresa' => 'required|integer',
                'sucursal' => 'required|integer',
                'tEstacion' => 'required|integer',
                'prioridad' => 'required|string',
                'tSoporte' => 'required|integer',
                'tIncidencia' => 'required|integer',
                'problema' => 'required|integer',
                'sproblema' => 'required|integer',
                'observacion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i:s',
                'cod_contact' => 'nullable|integer',
                'tel_contac' => 'nullable|string',
                'nro_doc' => 'nullable|string',
                'nom_contac' => 'nullable|string',
                'car_contac' => 'nullable|string',
                'cor_contac' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            $rContact = [
                'telefono' => $request->tel_contac,
                'nro_doc' => $request->nro_doc,
                'nombres' => $request->nom_contac,
                'cargo' => $request->car_contac,
                'correo' => $request->cor_contac
            ];
            $idContact = $request->cod_contact;

            DB::beginTransaction();
            if ($rContact['telefono'] || $rContact['nombres'] || $rContact['cargo']) {
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

            DB::table('tb_incidencias')->where('id_incidencia', $request->id_inc)->update([
                'cod_incidencia' => $request->cod_inc,
                'ruc_empresa' => $request->empresa,
                'id_sucursal' => $request->sucursal,
                'id_tipo_estacion' => $request->tEstacion,
                'prioridad' => $request->prioridad,
                'id_tipo_soporte' => $request->tSoporte,
                'id_tipo_incidencia' => $request->tIncidencia,
                'id_problema' => $request->problema,
                'id_subproblema' => $request->sproblema,
                'id_contacto' => $idContact,
                'observacion' => $request->observacion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe,
                'id_usuario' => Auth::user()->id_usuario,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            $cod_inc = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;

            DB::commit();
            return $this->message(message: "Registro actualizado exitosamente.", data: ['data' => ['cod_inc' => $cod_inc]]);
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {
                $response = $this->validatorUnique($e->getMessage(), $this->validator);
                return $this->message(data: ['unique' => $response], status: 422);
            }
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function assignPer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_inc' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            $estado_save = false ;
            $estado_dalete = false ;
            $personal = $request->personal_asig;
            if (count($request->personal_asig)) {
                $arr_personal_new = [];
                $indice_new = 0;
                $arr_personal_del = [];
                foreach ($personal as $k => $val) {
                    if (!$val['eliminado'] && $val['registro']) {
                        $arr_personal_new[$indice_new]['cod_incidencia'] = $request->cod_inc;
                        $arr_personal_new[$indice_new]['id_usuario'] = $val['id'];
                        $arr_personal_new[$indice_new]['creador'] = Auth::user()->id_usuario;
                        $arr_personal_new[$indice_new]['fecha'] = now()->format('Y-m-d');
                        $arr_personal_new[$indice_new]['hora'] = now()->format('H:i:s');
                        $arr_personal_new[$indice_new]['created_at'] = now()->format('Y-m-d H:i:s');
                        $personal[$val['id']]['registro'] = 0;
                        $indice_new++;
                    }
                    if ($val['eliminado'] && !$val['registro']) {
                        array_push($arr_personal_del, $val['id']);
                        unset($personal[$val['id']]);
                    }
                }
            }

            DB::beginTransaction();
            if (!empty($arr_personal_new)) {
                DB::table('tb_inc_asignadas')->insert($arr_personal_new);
                $estado_save = true;
            }

            if (!empty($arr_personal_del) && $request->estado) {
                DB::table('tb_inc_asignadas')->where('cod_incidencia', $request->cod_inc)->where('id_usuario', $arr_personal_del)->delete();
                $estado_dalete = true;
            }

            $estado_info = count($personal) ? 1 : 0;
            if ($request->estado)
                DB::table('tb_incidencias')->where('cod_incidencia', $request->cod_inc)->update(['estado_informe' => $estado_info]);

            $cod_inc = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            DB::commit();

            $message = "Cambios realizados con éxito";
            if ($estado_save && !$estado_dalete) {
                $message = "Personal asignado con éxito";
            }
            if (!$estado_save && $estado_dalete) {
                $message = "Personal desasignado con éxito";
            }

            return $this->message(message: $message, data: ['data' => ['cod_inc' => $cod_inc, 'estado' => $request->estado ? $estado_info : 2, 'personal' => $personal]]);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function startInc(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'codigo' => 'required|string',
                'estado' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            $validacion = DB::table('tb_inc_asignadas')->where('cod_incidencia', $request->codigo)->count();
            if (!$validacion) {
                return $this->message(message: "No se puede iniciar la incidencia, ya que no tienen un tecnico asignado.", status: 500);
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

            return $this->message(message: 'La incidencia se ' . ($accion == 2 ? '' : 're') . 'inició con exito.');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (Auth::user()->tipo_acceso != 3) {
                DB::beginTransaction();
                DB::table('tb_incidencias')->where('id_incidencia', $id)->update(['estatus' => 0]);
                DB::commit();
                return $this->message(message: "La incidencia se eliminó con exito.");
            }
            return $this->message(message: "No tiene los permisos requeridos.", status: 204);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
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
            $usuarios = DB::table('usuarios')->get()->keyBy('id_usuario')->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
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
            return $this->message(data: ['data' => ['incidencia' => $incidencia, 'seguimiento' => $data]]);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
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
        $datos = ['id' => null, 'documento' => $dni, 'nombre' => null, 'firma_digital' => null, 'consulta' => true];

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
            } else {
                $data['success'] = false;
                $data['message'] = $response['message'];
            }
        }

        if ($data['success'])
            $data['data'] = $datos;
        return $data;
    }
}
