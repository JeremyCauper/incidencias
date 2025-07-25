<?php

namespace App\Http\Controllers\Soporte\Incidencias;

use App\Helpers\CargoEstacion;
use App\Helpers\Problema;
use App\Helpers\SubProblema;
use App\Helpers\TipoEstacion;
use App\Helpers\TipoIncidencia;
use App\Helpers\TipoSoporte;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mantenimientos\ContactoEmpresasController;
use App\Http\Controllers\Soporte\Consultas\ConsultasController;
use App\Services\SqlStateHelper;
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
            $data['company'] = DB::table('tb_empresas')->select(['id', 'ruc', 'razon_social', 'direccion', 'contrato', 'codigo_aviso', 'status', 'eliminado'])->get()->keyBy('ruc'); //$this->fetchAndParseApiData('empresas');
            $data['scompany'] = DB::table('tb_sucursales')->select(['id', 'ruc', 'nombre', 'direccion', 'status'])->get()->keyBy('id'); //$this->fetchAndParseApiData('sucursales');

            // Obtener información de base de datos local
            $data['CargoEstacion'] = collect((new CargoEstacion())->all())->select('id', 'descripcion', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['tEstacion'] = collect((new TipoEstacion())->all())->select('id', 'descripcion', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['tSoporte'] = collect((new TipoSoporte())->all())->select('id', 'descripcion', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['tIncidencia'] = collect((new TipoIncidencia())->all())->select('id', 'descripcion', 'tipo', 'color', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['problema'] = collect((new Problema())->all())->select('id', 'codigo', 'descripcion', 'tipo_soporte', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['sproblema'] = collect((new SubProblema())->all())->select('id', 'codigo_problema', 'descripcion', 'prioridad', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $telefono_empresas = DB::table('contactos_telefono_empresas')->select('id', 'id_contact', 'telefono')->get()->groupBy('id_contact');
            $data['eContactos'] = DB::table('contactos_empresas')->select('id_contact', 'nro_doc', 'nombres', 'cargo', 'correo', 'consultado', 'estatus')->get()
                ->map(function ($val) use ($telefono_empresas) {
                    $val->telefonos = $telefono_empresas->get($val->id_contact) ?? [];
                    return $val;
                });

            // $materiales = db::table('tb_materiales')->select('id_materiales', 'producto', 'estatus', 'eliminado')->get();
            // $data['materiales'] = db::table('tb_inventario_tecnico')->where('id_usuario', Auth::user()->id_usuario)->get()
            //     ->map(function ($m) use ($materiales) {
            //         $material = $materiales->where('id_materiales', $m->id_material)->first();

            //         $m->producto = $material->producto;
            //         $m->estatus = $material->estatus;
            //         $m->eliminado = $material->eliminado;

            //         return $m;
            //     });
            $data['materiales'] = db::table('tb_materiales')->where('estatus', 1)->get()->map(function ($m) {
                return [
                    'id_material' => $m->id_materiales,
                    'producto' => $m->producto,
                    'estatus' => $m->estatus,
                    'eliminado' => $m->eliminado
                ];
            });

            $data['usuarios'] = db::table('tb_personal')->where(['id_area' => 1])->get()->keyBy('id_usuario')->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
                return [
                    'id' => $u->id_usuario,
                    'doc' => $u->ndoc_usuario,
                    'nombre' => $nombre,
                    'estatus' => $u->estatus,
                    'eliminado' => $u->eliminado
                ];
            });
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            $data['cod_orden'] = DB::select('CALL GetCodeOrds(?)', [date('y')])[0]->num_orden;

            // Cargar vista de las incidencias, junto a la variable data
            return view('soporte.incidencias.registradas', ['data' => $data]);
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
            $telefono_empresas = DB::table('contactos_telefono_empresas')->select('id', 'id_contact', 'telefono')->get()->groupBy('id_contact');
            $contactos_empresas = DB::table('contactos_empresas')->select('id_contact', 'nro_doc', 'nombres', 'cargo', 'correo', 'consultado', 'estatus')->get()
                ->map(function ($val) use ($telefono_empresas) {
                    $val->telefonos = $telefono_empresas->get($val->id_contact) ?? [];
                    return $val;
                });
            $conteo_data = [
                "totales" => 0,
                "tAsignadas" => 0,
                "tSinAsignar" => 0,
                "tEnProceso" => 0,
            ];

            $incidencias = DB::table('tb_incidencias')->select(['cod_incidencia', 'ruc_empresa', 'id_sucursal', 'created_at', 'id_tipo_estacion', 'id_tipo_soporte', 'id_problema', 'id_subproblema', 'estado_informe', 'id_incidencia as id', 'estatus'])
                ->where('estatus', 1)->whereNot('estado_informe', 3)->get();

            $cod_incidencias = $incidencias->pluck('cod_incidencia')->toArray();

            $inc_asig = DB::table('tb_inc_asignadas')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia');
            $inc_tipo = DB::table('tb_inc_tipo')->select('cod_incidencia', 'id_tipo_inc')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia');
            // Procesar incidencias
            $incidencias = $incidencias->map(function ($val) use (&$conteo_data, $inc_asig, $inc_tipo) {
                if (!empty($inc_asig[$val->cod_incidencia])) {
                    $asignados = collect($inc_asig[$val->cod_incidencia])->pluck('id_usuario')->toArray();
                }
                $tipoInc = collect($inc_tipo[$val->cod_incidencia])->pluck('id_tipo_inc')->toArray();

                // Configurar el estado del informe
                $estadoInforme = [
                    "0" => ['c' => 'warning', 't' => 'Sin Asignar'],
                    "1" => ['c' => 'info', 't' => 'Asignada'],
                    "2" => ['c' => 'primary', 't' => 'En Proceso'],
                    "4" => ['c' => 'danger', 't' => 'Faltan Datos'],
                    "5" => ['c' => 'danger', 't' => 'Cierre Sistema'],
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
                    'tecnicos' => $asignados ?? [],
                    'tipo_incidencia' => $tipoInc,
                    'tipo_estacion' => $val->id_tipo_estacion,
                    'tipo_soporte' => $val->id_tipo_soporte,
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
                'tIncidencia' => 'required|integer',
                'tSoporte' => 'required|integer',
                'problema' => 'required|integer',
                'sproblema' => 'required|integer',
                'observacion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i:s',
                'cod_contact' => 'nullable|integer',
                'consultado_api' => 'nullable|integer',
                'nro_doc' => 'nullable|string',
                'nom_contac' => 'nullable|string',
                'tel_contac' => 'nullable|string',
                'car_contac' => 'nullable|string',
                'cor_contac' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            $vPersonal = $request->personal;
            $estado_info = count($vPersonal) ? 1 : 0;

            DB::beginTransaction();

            $idContact = $request->cod_contact;
            if (!empty($request->nro_doc) || !empty($request->nom_contac)) {
                $registro_contacto = [
                    'nro_doc' => $request->nro_doc ?: null,
                    'nombres' => $request->nom_contac ?: null,
                    'cargo' => $request->car_contac ?: null,
                    'correo' => $request->cor_contac ?: null,
                    'consultado' => $request->consultado_api ?: 0,
                    (empty($idContact) ? 'created_at' : 'updated_at') => now()->format('Y-m-d H:i:s')
                ];
                if (empty($idContact)) {
                    $idContact = DB::table('contactos_empresas')->insertGetId($registro_contacto);
                } else {
                    DB::table('contactos_empresas')->where('id_contact', $idContact)->update($registro_contacto);
                }
            }

            $idTelefono = $request->tel_contac;
            if (!empty($idTelefono) && str_starts_with($idTelefono, "nuevo:")) {
                $newTelefono = substr($idTelefono, strlen("nuevo:"));
                $idTelefono = DB::table('contactos_telefono_empresas')->insertGetId([
                    'id_contact' => $idContact,
                    'telefono' => $newTelefono,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
            }

            $validar_codigo = "";
            $new_codigo = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            if ($new_codigo != $request->cod_inc) {
                $validar_codigo = "<b class='text-danger'>Importante:</b> El código de incidencia <b>$request->cod_inc</b> ya estaba en uso. Se asignó el nuevo código <b>$new_codigo</b>";
            } else {
                $new_codigo = $request->cod_inc;
            }

            DB::table('tb_incidencias')->insert([
                'cod_incidencia' => $new_codigo,
                'ruc_empresa' => $request->empresa,
                'id_sucursal' => $request->sucursal,
                'id_tipo_estacion' => $request->tEstacion,
                'id_tipo_soporte' => $request->tSoporte,
                'id_problema' => $request->problema,
                'id_subproblema' => $request->sproblema,
                'id_contacto' => $idContact ?: null,
                'id_telefono' => $idTelefono ?: null,
                'observacion' => $request->observacion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe,
                'estado_informe' => $estado_info,
                'id_usuario' => Auth::user()->id_usuario,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);

            DB::table('tb_inc_tipo')->insert([
                'cod_incidencia' => $new_codigo,
                'creador' => Auth::user()->id_usuario,
                'id_tipo_inc' => $request->tIncidencia,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);

            if ($estado_info) {
                $arr_personal = collect($vPersonal)
                    ->filter(fn($val) => $val['registro'])
                    ->map(function ($val) use ($new_codigo) {
                        return [
                            'cod_incidencia' => $new_codigo,
                            'id_usuario' => $val['id'],
                            'creador' => Auth::user()->id_usuario,
                            'fecha' => now()->format('Y-m-d'),
                            'hora' => now()->format('H:i:s'),
                            'created_at' => now()->format('Y-m-d H:i:s'),
                        ];
                    })->values()->toArray();
                DB::table('tb_inc_asignadas')->insert($arr_personal);
            }

            DB::commit();

            $data = [];
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;

            return $this->message(message: "<p>Registro creado exitosamente.</p><p style='font-size: small;'>$validar_codigo</p>", data: ['data' => $data]);
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo registrar la incidencia." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $cod)
    {
        try {
            // Consultamos la incidencia por ID
            $incidencia = DB::table('tb_incidencias')->where('cod_incidencia', $cod)->first();
            $orden = DB::table('tb_orden_servicio')->select('cod_ordens')->where('cod_incidencia', $cod)->first();
            if ($orden) {
                $incidencia->cod_orden = $orden->cod_ordens;
                $incidencia->new_cod_orden = DB::select('CALL GetCodeOrds(?)', [date('y')])[0]->num_orden;
            }

            // Verificamos si se encontró la incidencia
            if (!$incidencia)
                return $this->message(message: "Incidencia no encontrada", status: 204);

            // Consultamos los contactos asociados a la incidencia y los añadimos como propiedades del objeto incidencia
            $contactos_empresas = DB::table('contactos_empresas')->select('id_contact', 'nro_doc', 'nombres', 'cargo', 'correo', 'consultado', 'estatus')
                ->where('id_contact', $incidencia->id_contacto)->first();
            if ($contactos_empresas) {
                $contactos_empresas->telefonos = DB::table('contactos_telefono_empresas')->select('id', 'telefono')->where('id_contact', $incidencia->id_contacto)->get();
            }
            $incidencia->contacto = $contactos_empresas ?: null;

            $asignados = DB::table('tb_inc_asignadas')->where('cod_incidencia', $cod)->pluck('id_usuario')->toArray();
            $incidencia->personal_asig = DB::table('tb_personal')
                ->whereIn('id_usuario', $asignados)
                ->select(['id_usuario', 'ndoc_usuario', 'nombres', 'apellidos'])->get()->map(function ($usu) {
                    $nombre = $this->formatearNombre($usu->nombres, $usu->apellidos);
                    return [
                        'id' => $usu->id_usuario,
                        'dni' => $usu->ndoc_usuario,
                        'tecnicos' => $nombre
                    ];
                });

            $incidencia->tipo_incidencia = DB::table('tb_inc_tipo')->select('id_tipo_inc', 'created_at')->where('cod_incidencia', $cod)->get();

            // Retornamos la incidencia con la información de contacto y personal asignado
            return $this->message(data: ['data' => $incidencia]);
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo obtener informacion la incidencia." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
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
                'estado_info' => 'required|string',
                'empresa' => 'required|integer',
                'sucursal' => 'required|integer',
                'tEstacion' => 'required|integer',
                'tSoporte' => 'required|integer',
                'tIncidencia' => 'required|integer',
                'problema' => 'required|integer',
                'sproblema' => 'required|integer',
                'observacion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i:s',
                'cod_contact' => 'nullable|integer',
                'consultado_api' => 'nullable|integer',
                'nro_doc' => 'nullable|string',
                'nom_contac' => 'nullable|string',
                'tel_contac' => 'nullable|string',
                'car_contac' => 'nullable|string',
                'cor_contac' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            DB::beginTransaction();

            $idContact = $request->cod_contact;
            if ($request->nom_contac && $request->tel_contac && $request->car_contac) {
                $registro_contacto = [
                    'nro_doc' => $request->nro_doc ?: null,
                    'nombres' => $request->nom_contac ?: null,
                    'cargo' => $request->car_contac ?: null,
                    'correo' => $request->cor_contac ?: null,
                    'consultado' => $request->consultado_api ?: 0,
                    (empty($idContact) ? 'created_at' : 'updated_at') => now()->format('Y-m-d H:i:s')
                ];
                if (empty($idContact)) {
                    $idContact = DB::table('contactos_empresas')->insertGetId($registro_contacto);
                } else {
                    DB::table('contactos_empresas')->where('id_contact', $idContact)->update($registro_contacto);
                }

                $idTelefono = $request->tel_contac;
                if (!empty($idTelefono) && str_starts_with($idTelefono, "nuevo:")) {
                    $newTelefono = substr($idTelefono, strlen("nuevo:"));
                    $idTelefono = DB::table('contactos_telefono_empresas')->insertGetId([
                        'id_contact' => $idContact,
                        'telefono' => $newTelefono,
                        'created_at' => now()->format('Y-m-d H:i:s')
                    ]);
                }
            }

            DB::table('tb_incidencias')->where('id_incidencia', $request->id_inc)->update([
                'cod_incidencia' => $request->cod_inc,
                'ruc_empresa' => $request->empresa,
                'id_sucursal' => $request->sucursal,
                'id_tipo_estacion' => $request->tEstacion,
                'id_tipo_soporte' => $request->tSoporte,
                'id_problema' => $request->problema,
                'id_subproblema' => $request->sproblema,
                'id_contacto' => $idContact ?: null,
                'id_telefono' => $idTelefono ?: null,
                'observacion' => $request->observacion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe,
                'id_usuario' => Auth::user()->id_usuario,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);

            if ($request->estado_info == 2) {
                $tipo_incidencia = DB::table('tb_inc_tipo')->where(['cod_incidencia' => $request->cod_inc, 'id_tipo_inc' => $request->tIncidencia])->first();
                if (empty($tipo_incidencia)) {
                    DB::table('tb_inc_tipo')->insert([
                        'cod_incidencia' => $request->cod_inc,
                        'id_tipo_inc' => $request->tIncidencia,
                        'creador' => Auth::user()->id_usuario,
                        'fecha' => now()->format('Y-m-d'),
                        'hora' => now()->format('H:i:s'),
                        'created_at' => now()->format('Y-m-d H:i:s')
                    ]);
                }
            } else {
                DB::table('tb_inc_tipo')->where('cod_incidencia', $request->cod_inc)->update([
                    'id_tipo_inc' => $request->tIncidencia,
                    'creador' => Auth::user()->id_usuario
                ]);
            }


            $estado_save = false;
            $estado_dalete = false;
            $vPersonal = $request->personal;
            if (count($request->personal)) {
                $arr_personal_new = [];
                $indice_new = 0;
                $arr_personal_del = [];
                foreach ($vPersonal as $k => $val) {
                    if (!$val['eliminado'] && $val['registro']) {
                        $arr_personal_new[$indice_new]['cod_incidencia'] = $request->cod_inc;
                        $arr_personal_new[$indice_new]['id_usuario'] = $val['id'];
                        $arr_personal_new[$indice_new]['creador'] = Auth::user()->id_usuario;
                        $arr_personal_new[$indice_new]['fecha'] = now()->format('Y-m-d');
                        $arr_personal_new[$indice_new]['hora'] = now()->format('H:i:s');
                        $arr_personal_new[$indice_new]['created_at'] = now()->format('Y-m-d H:i:s');
                        $vPersonal[$val['id']]['registro'] = 0;
                        $indice_new++;
                    }
                    if ($val['eliminado'] && !$val['registro']) {
                        array_push($arr_personal_del, $val['id']);
                        unset($vPersonal[$val['id']]);
                    }
                }
            }

            if (!empty($arr_personal_new)) {
                DB::table('tb_inc_asignadas')->insert($arr_personal_new);
                $estado_save = true;
            }

            if (!empty($arr_personal_del) && $request->estado_info != 2) {
                DB::table('tb_inc_asignadas')->where('cod_incidencia', $request->cod_inc)->where('id_usuario', $arr_personal_del)->delete();
                $estado_dalete = true;
            }

            $estado_info = count($vPersonal) ? 1 : 0;
            if ($request->estado)
                DB::table('tb_incidencias')->where('cod_incidencia', $request->cod_inc)->update(['estado_informe' => $estado_info]);

            DB::commit();
            return $this->message(message: "Registro actualizado exitosamente.", data: ['data' => []]);
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo actualizar la incidencia." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
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

            $estado_save = false;
            $estado_dalete = false;
            $vPersonal = $request->personal_asig;
            if (count($request->personal_asig)) {
                $arr_personal_new = [];
                $indice_new = 0;
                $arr_personal_del = [];
                foreach ($vPersonal as $k => $val) {
                    if (!$val['eliminado'] && $val['registro']) {
                        $arr_personal_new[$indice_new]['cod_incidencia'] = $request->cod_inc;
                        $arr_personal_new[$indice_new]['id_usuario'] = $val['id'];
                        $arr_personal_new[$indice_new]['creador'] = Auth::user()->id_usuario;
                        $arr_personal_new[$indice_new]['fecha'] = now()->format('Y-m-d');
                        $arr_personal_new[$indice_new]['hora'] = now()->format('H:i:s');
                        $arr_personal_new[$indice_new]['created_at'] = now()->format('Y-m-d H:i:s');
                        $vPersonal[$val['id']]['registro'] = 0;
                        $indice_new++;
                    }
                    if ($val['eliminado'] && !$val['registro']) {
                        array_push($arr_personal_del, $val['id']);
                        unset($vPersonal[$val['id']]);
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

            $estado_info = count($vPersonal) ? 1 : 0;
            if ($request->estado)
                DB::table('tb_incidencias')->where('cod_incidencia', $request->cod_inc)->update(['estado_informe' => $estado_info]);
            DB::commit();

            $message = "Cambios realizados con éxito";
            if ($estado_save && !$estado_dalete) {
                $message = "Personal asignado con éxito";
            }
            if (!$estado_save && $estado_dalete) {
                $message = "Personal desasignado con éxito";
            }

            return $this->message(message: $message, data: ['data' => ['estado' => $request->estado ? $estado_info : 2, 'personal' => $vPersonal]]);
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo asignar personal la incidencia." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
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

            $iniciado = DB::table('tb_incidencias')->select('estado_informe')->where('cod_incidencia', $request->codigo)->first();
            if ($iniciado->estado_informe == 2) {
                return $this->message(message: "La incidencia ya fue iniciada y está en <b>proceso</b>.", status: 202);
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
                DB::table('tb_inc_tipo')->where('cod_incidencia', $cod)->update([
                    'fecha' => now()->format('Y-m-d'),
                    'hora' => now()->format('H:i:s')
                ]);
            }
            DB::table('tb_incidencias')->where('cod_incidencia', $cod)->update(['estado_informe' => $accion]);
            DB::commit();

            return $this->message(message: 'La incidencia se ' . ($accion == 2 ? '' : 're') . 'inició con exito.');
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo iniciar la incidencia." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
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
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se pudo eliminar la incidencia." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function detail(string $cod)
    {
        try {
            // Consultamos la incidencia activa
            $incidencia = DB::table('tb_incidencias')->where(['estatus' => 1, 'cod_incidencia' => $cod])->first();

            // Validamos que la incidencia exista
            if (!$incidencia) {
                return $this->message(message: "Incidencia no encontrada.", status: 404);
            }

            // Consultas adicionales
            $orden = DB::table('tb_orden_servicio')->where('cod_incidencia', $cod)->first();
            $asignados = DB::table('tb_inc_asignadas')->where('cod_incidencia', $cod)->get();
            $seguimiento = DB::table('tb_inc_seguimiento')->where('cod_incidencia', $cod)->get();
            $incidencia->tipo_incidencia = DB::table('tb_inc_tipo')->select('id_tipo_inc', 'fecha', 'hora', 'created_at')->where('cod_incidencia', $cod)->get();

            // Obtenemos la información del personal y la claveamos por id_usuario
            $personal = DB::table('tb_personal')->get()->keyBy('id_usuario')
                ->map(function ($u) {
                    return (object) [
                        "foto" => $u->foto_perfil,
                        "tecnico" => $this->formatearNombre($u->nombres, $u->apellidos),
                        "email" => $u->email_corporativo,
                        "telefono" => $u->tel_corporativo,
                    ];
                });

            // Asignamos el código de orden, si existe
            $incidencia->cod_orden = $orden ? $orden->cod_ordens : null;

            // Formateamos la información de registro
            $data = [
                "registro" => $this->formatInfoData(
                    $personal,
                    $incidencia->id_usuario,
                    $incidencia->id_usuario,
                    $incidencia->created_at
                )
            ];

            // Procesamos las asignaciones: agrupamos por creador usando groupBy
            $asignadosGrouped = $asignados->groupBy('creador');

            // Recorremos cada grupo de asignaciones y formateamos la información
            $data['asignado'] = $asignadosGrouped->map(function ($items, $creatorId) use ($personal) {
                // Información del usuario creador de la asignación
                $info = $this->formatInfoData($personal, $creatorId, $creatorId, "");
                // Se agregan los técnicos asignados
                $info['tecnicos'] = $items->map(function ($item) use ($personal) {
                    return $this->formatInfoData(
                        $personal,
                        $item->id_usuario,
                        $item->id_usuario,
                        $item->created_at
                    );
                })->toArray();
                return $info;
            })->values()->toArray();

            // Procesamos el seguimiento: se asigna a "inicio" o "final" según el estado
            foreach ($seguimiento as $item) {
                $estadoTexto = $item->estado ? "final" : "inicio";
                $data[$estadoTexto] = $this->formatInfoData(
                    $personal,
                    $item->id_usuario,
                    $item->id_usuario,
                    $item->created_at
                );
            }

            // Retornamos la respuesta estructurada
            return $this->message(data: ['data' => ['incidencia' => $incidencia, 'seguimiento' => $data]]);

        } catch (QueryException $e) {
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "Error al obtener informacion de la incidencia." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e->getMessage()], status: $sqlHelper->codigo);
        } catch (Exception $e) {
            // DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Formatea la información de contacto del usuario.
     */
    private function formatInfoData($usuario, $creador, $vPersonal, $date)
    {
        $imagen = $usuario[$creador]->foto ?? 'user_auth.jpg';
        return [
            'img' => asset("front/images/auth/{$imagen}"),
            'nombre' => $usuario[$creador]->tecnico,
            'telefono' => $usuario[$vPersonal]->telefono ?: "999999999",
            'email' => $usuario[$vPersonal]->email ?: "soporte01@rcingenieros.com",
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
