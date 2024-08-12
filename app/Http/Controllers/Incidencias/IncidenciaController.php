<?php

namespace App\Http\Controllers\Incidencias;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IncidenciaController extends Controller
{
    public function dataInd()
    {
        try {
            $data = [];

            $empresas = GlobalHelper::getCompany();
            $data['empresas'] = array_map(function ($empresas) {
                return ['id' => $empresas->id, 'ruc' => $empresas->Ruc, 'empresa' => "{$empresas->Ruc} - {$empresas->RazonSocial}"];
            }, $empresas);

            $sucursales = GlobalHelper::getBranchOffice();
            foreach ($sucursales as $val) {
                $data['sucursales'][$val->ruc][] = ['id' => $val->id, 'sucursal' => $val->Nombre];
            }

            $data['usuarios'] = GlobalHelper::getUsuarios()->map(function ($u) {
                return [
                    'value' => "{$u->id_usuario}|{$u->ndoc_usuario}|{$u->nombres} {$u->apellidos}",
                    'text' => "{$u->ndoc_usuario} - {$u->nombres} {$u->apellidos}"
                ];
            });

            $data['count_panel'] = [
                "count" => count(GlobalHelper::getIncDataTable()),
                "inc_a" => 0,
                "inc_p" => 0,
                "inc_s" => 0,
            ];
            foreach (GlobalHelper::getIncDataTable() as $val) {
                switch ($val->estado_informe) {
                    case 0:
                        $data['count_panel']['inc_s']++;
                        break;
                    case 1:
                        $data['count_panel']['inc_a']++;
                        break;
                    case 2:
                        $data['count_panel']['inc_p']++;
                        break;
                }
            }

            $data['cargo_contaco'] = GlobalHelper::getCargoContact();
            $data['tipo_estacion'] = GlobalHelper::getTipEstacion();
            $data['tipo_soporte'] = GlobalHelper::getTipSoporte();
            $data['tipo_incidencia'] = GlobalHelper::getTipIncidencia();
            $data['problema'] = GlobalHelper::getProblema();
            $data['subproblema'] = GlobalHelper::getSubProblema();
            $data['materiales'] = GlobalHelper::getMateriales();
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;

            return $data;
        } catch (\Throwable $th) {
            Log::error('Error retrieving data: ' . $th->getMessage());
            return ['error' => 'Service Unavailable', 'message' => $th->getMessage()];
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function datatable()
    {
        $e_informe = [
            ['c' => 'warning', 't' => 'Sin Asignar'],
            ['c' => 'info', 't' => 'Asignada'],
            ['c' => 'primary', 't' => 'En Proceso']
        ];

        $empresas = GlobalHelper::getCompany();
        $company = [];
        foreach ($empresas as $val) {
            $company[$val->id] = "{$val->Ruc} - {$val->RazonSocial}";
        }

        $sucursales = GlobalHelper::getBranchOffice();
        $subcompany = [];
        foreach ($sucursales as $val) {
            $subcompany[$val->id] = [$val->Nombre, $val->Direccion];
        }

        $tipo_estacion = GlobalHelper::getTipEstacion();
        $__estacion = $this->getparsedata($tipo_estacion);
        $tipo_incidencia = GlobalHelper::getTipIncidencia();
        $__incidencia = $this->getparsedata($tipo_incidencia);
        $problema = GlobalHelper::getProblema();
        $__problema = $this->getparsedata($problema);
        $subproblema = GlobalHelper::getSubProblema();
        $__subproblema = $this->getparsedata($subproblema);

        $incidencias = GlobalHelper::getIncDataTable(true);
        foreach ($incidencias as $val) {
            $val->id_empresa = $company[$val->id_empresa];
            $val->direccion = $subcompany[$val->id_sucursal][1];
            $val->id_sucursal = $subcompany[$val->id_sucursal][0];
            $val->id_tipo_estacion = $__estacion[$val->id_tipo_estacion];
            $val->id_tipo_incidencia = $__incidencia[$val->id_tipo_incidencia];
            $val->id_problema = $__problema[$val->id_problema];
            $val->id_subproblema = $__subproblema[$val->id_subproblema];
            $text_e_informe = '<label class="badge badge-' . $e_informe[$val->estado_informe]['c'] . '" style="font-size: .7rem;">' . $e_informe[$val->estado_informe]['t'] . '</label>';
            $val->acciones = '
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
                    <h6 class="dropdown-header text-secondary d-flex justify-content-between align-items-center">' . $text_e_informe . '<i class="fas fa-gear"></i></h6>
                    <button class="dropdown-item py-2" onclick="showDetail(this, ' . ("'{$val->cod_incidencia}'") . ')"><i class="fas fa-eye text-success me-2"></i> Ver Detalle</button>
                    <button class="dropdown-item py-2" onclick="showEdit(' . $val->acciones . ')"><i class="fas fa-pen text-info me-2"></i> Editar</button>
                    <button class="dropdown-item py-2" onclick="assign(this, ' . $val->acciones . ')"><i class="fas fa-user-plus me-2"></i> Asignar</button>'
                . ($val->estado_informe == 1 ? '<button class="dropdown-item py-2" onclick="reloadInd(' . ("'{$val->cod_incidencia}'") . ', ' . $val->estado_informe . ')"><i class="' . ($val->estado_informe != 2 ? 'far fa-clock' : 'fas fa-clock-rotate-left') . ' text-warning me-2"></i> ' . ($val->estado_informe != 2 ? 'Iniciar' : 'Reiniciar') . ' Incidencia</button>' : '')
                . ($val->estado_informe == 2 ? '<button class="dropdown-item py-2" onclick="createOrden(this, ' . ("'{$val->cod_incidencia}'") . ')"><i class="fas fa-book-medical text-primary me-2"></i> Orden de servicio</button>' : '') .
                '<button class="dropdown-item py-2" onclick="idelete(' . $val->acciones . ')"><i class="far fa-trash-can text-danger me-2"></i> Eliminar</button>
                </div>
            </div>';
            $val->estado_informe = $text_e_informe;
        }

        $_count = [
            "count" => count(GlobalHelper::getIncDataTable()),
            "inc_a" => 0,
            "inc_p" => 0,
            "inc_s" => 0,
        ];
        foreach (GlobalHelper::getIncDataTable() as $val) {
            switch ($val->estado_informe) {
                case 0:
                    $_count['inc_s']++;
                    break;
                case 1:
                    $_count['inc_a']++;
                    break;
                case 2:
                    $_count['inc_p']++;
                    break;
            }
        }

        return ['data' => $incidencias, 'count' => $_count];
    }

    /**
     * Display a view of the resource.
     */
    public function view()
    {
        try {
            $dataInd = $this->dataInd();

            if (isset($dataInd['error']))
                return response()->json(['error' => $dataInd['message']], 400);

            return view('dashboard.soporte.panel', ['dataInd' => $dataInd]);
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_inc' => 'required|string',
                'id_empresa' => 'required|integer',
                'id_sucursal' => 'required|integer',
                'tip_estacion' => 'required|integer',
                'priori_inc' => 'required|string',
                'tip_soport' => 'required|integer',
                'tip_incidencia' => 'required|integer',
                'inc_problem' => 'required|integer',
                'inc_subproblem' => 'required|integer',
                'observasion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i',
                'tel_contac' => 'nullable|string',
                'nro_doc' => 'nullable|integer',
                'nom_contac' => 'nullable|string',
                'car_contac' => 'nullable|string',
                'cor_contac' => 'nullable|string'
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            $personal_asig = $request->personal_asig;
            $personal_asig[0]['creador'] = Auth::user()->id_usuario;
            $personal_asig[0]['updated_at'] = now();
            $personal_asig[0]['created_at'] = now();
            $estado_info = count($personal_asig) ? 1 : 0;

            DB::beginTransaction();
            DB::table('tb_incidencias')->insert([
                'cod_incidencia' => $request->cod_inc,
                'id_empresa' => $request->id_empresa,
                'id_sucursal' => $request->id_sucursal,
                'id_tipo_estacion' => $request->tip_estacion,
                'prioridad' => $request->priori_inc,
                'id_tipo_soporte' => $request->tip_soport,
                'id_tipo_incidencia' => $request->tip_incidencia,
                'id_problema' => $request->inc_problem,
                'id_subproblema' => $request->inc_subproblem,
                'observasion' => $request->observasion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe . GlobalHelper::gDate(':s'),
                'estado_informe' => $estado_info,
                'id_usuario' => Auth::user()->id_usuario,
                'created_at' => GlobalHelper::gDate(),
                'updated_at' => GlobalHelper::gDate()
            ]);

            if (
                $request->tel_contac ||
                $request->nro_doc ||
                $request->nom_contac ||
                $request->car_contac
            ) {
                DB::table('contactos_empresas')->insert([
                    'telefono' => $request->tel_contac,
                    'nro_doc' => $request->nro_doc,
                    'nombres' => $request->nom_contac,
                    'cargo' => $request->car_contac,
                    'correo' => $request->cor_contac,
                    'id_empresa' => $request->id_empresa,
                    'id_sucursal' => $request->id_sucursal,
                    'created_at' => GlobalHelper::gDate(),
                    'updated_at' => GlobalHelper::gDate()
                ]);
            }

            if (count($personal_asig))
                DB::table('tb_inc_asignadas')->insert($personal_asig);

            DB::commit();
            GlobalHelper::getIncDataTable(true);

            $data = [];
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            return response()->json([
                'success' => true,
                'message' => 'Insidencia registrada exitosamente.',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al registrar incidencia: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $incidencias = DB::table('tb_incidencias')->where('id_incidencia', $id)->first();

            if (!$incidencias) {
                return response()->json(['success' => false, 'message' => 'Incidencia no encontrada']);
            }

            $empresas = GlobalHelper::getCompany();
            $company = [];
            foreach ($empresas as $val) {
                $company[$val->id] = "{$val->Ruc} - {$val->RazonSocial}";
            }

            $sucursales = GlobalHelper::getBranchOffice();
            $subcompany = [];
            foreach ($sucursales as $val) {
                $subcompany[$val->id] = ['sucursal' => $val->Nombre, 'direccion' => $val->Direccion];
            }

            $contactos = DB::table('contactos_empresas')
                ->where([
                    ['id_empresa', $incidencias->id_empresa],
                    ['id_sucursal', $incidencias->id_sucursal]
                ])
                ->first();

            $incidencias->telefono = isset($contactos->telefono) ? $contactos->telefono : null;
            $incidencias->nro_doc = isset($contactos->nro_doc) ? $contactos->nro_doc : null;
            $incidencias->nombres = isset($contactos->nombres) ? $contactos->nombres : null;
            $incidencias->cargo = isset($contactos->cargo) ? $contactos->cargo : null;
            $incidencias->correo = isset($contactos->correo) ? $contactos->correo : null;

            $asignados = DB::table('tb_inc_asignadas')
                ->where('cod_incidencia', $incidencias->cod_incidencia)
                ->pluck('id_usuario')
                ->toArray();

            $usuarios = GlobalHelper::getUsuarios()->map(function ($usuario) {
                return [
                    'id' => $usuario->id_usuario,
                    'value' => "{$usuario->id_usuario}|{$usuario->ndoc_usuario}|{$usuario->nombres} {$usuario->apellidos}",
                    'text' => "{$usuario->ndoc_usuario} - {$usuario->nombres} {$usuario->apellidos}"
                ];
            });


            $incidencias->empresa = $company[$incidencias->id_empresa];
            $incidencias->sucursal = $subcompany[$incidencias->id_sucursal]['sucursal'];
            $incidencias->direccion = $subcompany[$incidencias->id_sucursal]['direccion'];
            $incidencias->personal_asig = $usuarios->whereIn('id', $asignados)->values();

            return $incidencias;
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
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
                'id_sucursal' => 'required|integer',
                'tip_estacion' => 'required|integer',
                'priori_inc' => 'required|string',
                'tip_soport' => 'required|integer',
                'tip_incidencia' => 'required|integer',
                'inc_problem' => 'required|integer',
                'inc_subproblem' => 'required|integer',
                'observasion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i:s',
                'tel_contac' => 'nullable|string',
                'nro_doc' => 'nullable|integer',
                'nom_contac' => 'nullable|string',
                'car_contac' => 'nullable|string',
                'cor_contac' => 'nullable|string'
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            $personal_asig = $request->personal_asig;
            $estado_info = count($personal_asig) ? 1 : 0;

            $update = [
                'cod_incidencia' => $request->cod_inc,
                'id_empresa' => $request->id_empresa,
                'id_sucursal' => $request->id_sucursal,
                'id_tipo_estacion' => $request->tip_estacion,
                'prioridad' => $request->priori_inc,
                'id_tipo_soporte' => $request->tip_soport,
                'id_tipo_incidencia' => $request->tip_incidencia,
                'id_problema' => $request->inc_problem,
                'id_subproblema' => $request->inc_subproblem,
                'observasion' => $request->observasion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe,
                'estado_informe' => $estado_info,
                'id_usuario' => Auth::user()->id_usuario,
                'updated_at' => GlobalHelper::gDate()
            ];

            $updateContact = [
                'telefono' => $request->tel_contac,
                'nro_doc' => $request->nro_doc,
                'nombres' => $request->nom_contac,
                'cargo' => $request->car_contac,
                'correo' => $request->cor_contac,
                'id_empresa' => $request->id_empresa,
                'id_sucursal' => $request->id_sucursal,
                'updated_at' => GlobalHelper::gDate()
            ];

            DB::beginTransaction();
            DB::table('tb_incidencias')
                ->where('id_incidencia', $id)
                ->update($update);

            if (
                $updateContact['telefono'] ||
                $updateContact['nro_doc'] ||
                $updateContact['nombres'] ||
                $updateContact['cargo']
            ) {
                DB::table('contactos_empresas')->where([
                    ['id_empresa' => $request->id_empresa],
                    ['id_sucursal' => $request->id_sucursal]
                ])->update($updateContact);
            }

            if (count($personal_asig)) {
                DB::table('tb_inc_asignadas')->where('cod_incidencia', $update['cod_incidencia'])->delete();
                DB::table('tb_inc_asignadas')->insert($personal_asig);
            }

            DB::commit();
            GlobalHelper::getIncDataTable(true);

            $data = [];
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            return response()->json([
                'success' => true,
                'message' => 'Incidencia editada con éxito',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al editar incidencia: ' . $e->getMessage(),
                'data' => []
            ], 500);
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
                GlobalHelper::getIncDataTable(true);

                return response()->json([
                    'success' => true,
                    'message' => 'Incidencia eliminada'
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => 'No tiene los permisos requeridos'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al editar incidencia: ' . $e->getMessage()
            ], 500);
        }
    }

    public function initInc(Request $request, string $cod)
    {
        try {
            $validator = Validator::make($request->all(), [
                'estado' => 'required|integer',
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            DB::beginTransaction();

            $accion = $request->estado == 1 ? 2 : 1;
            if ($accion == 1) {
                DB::table('tb_inc_seguimiento')->where('cod_incidencia', $cod)->delete();
            } else {
                DB::table('tb_inc_seguimiento')->insert([
                    'id_usuario' => Auth::user()->id_usuario,
                    'cod_incidencia' => $cod,
                    'updated_at' => now(),
                    'created_at' => now()
                ]);
            }
            DB::table('tb_incidencias')->where('cod_incidencia', $cod)->update(['estado_informe' => $accion]);

            DB::commit();
            GlobalHelper::getIncDataTable(true);

            return response()->json([
                'success' => true,
                'message' => 'Incidencia ' . ($accion == 2 ? '' : 're') . 'iniciada'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al editar incidencia: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editAssign(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_inc' => 'required|string'
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            $personal_asig = $request->personal_asig;
            $personal_asig[0]['creador'] = Auth::user()->id_usuario;
            $personal_asig[0]['updated_at'] = now();
            $personal_asig[0]['created_at'] = now();
            $estado_info = count($personal_asig) ? 1 : 0;

            DB::beginTransaction();

            if (count($personal_asig)) {
                DB::table('tb_inc_asignadas')->where('cod_incidencia', $request->cod_inc)->delete();
                DB::table('tb_inc_asignadas')->insert($personal_asig);
            }

            DB::table('tb_incidencias')
                ->where('cod_incidencia', $request->cod_inc)
                ->update(['estado_informe' => 1]);
            DB::commit();
            GlobalHelper::getIncDataTable(true);

            $data = [];
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            return response()->json([
                'success' => true,
                'message' => 'Incidencia editada con éxito',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al editar incidencia: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function detail(string $cod)
    {
        $inc = DB::table('tb_incidencias')->where(['estatus' => 1, 'cod_incidencia' => $cod])->first();
        $inc_asig = DB::table('tb_inc_asignadas')->where('cod_incidencia', $cod)->get();
        $inc_seg = DB::table('tb_inc_seguimiento')->where('cod_incidencia', $cod)->get();
        $usuarios = [];
        foreach (GlobalHelper::getUsuarios() as $val) {
            $usuarios[$val->id_usuario] = $val;
        }

        $data = [
            [
                'img' => asset("front/images/auth/{$usuarios[$inc->id_usuario]->foto_perfil}"),
                'nombre' => "{$usuarios[$inc->id_usuario]->nombres} {$usuarios[$inc->id_usuario]->apellidos}",
                'text' => "Registro la incidencia el {$inc->created_at}",
                'data' => '<i class="fab fa-whatsapp"></i> ' . $usuarios[$inc->id_usuario]->email_corporativo . ' / <i class="far fa-envelope"></i> ' . $usuarios[$inc->id_usuario]->email_corporativo
            ]
        ];

        foreach ($inc_asig as $v) {
            array_push($data, [
                'img' => asset("front/images/auth/{$usuarios[$v->creador]->foto_perfil}"),
                'nombre' => "{$usuarios[$v->creador]->nombres} {$usuarios[$v->creador]->apellidos}",
                'text' => "Asigno la Incidencia a {$usuarios[$v->id_usuario]->nombres} {$usuarios[$v->id_usuario]->apellidos}",
                'data' => '<i class="fab fa-whatsapp"></i> ' . $usuarios[$v->id_usuario]->email_corporativo . ' / <i class="far fa-envelope"></i> ' . $usuarios[$v->id_usuario]->email_corporativo
            ]);
        }

        foreach ($inc_seg as $v) {
            array_push($data, [
                'img' => asset("front/images/auth/{$usuarios[$v->id_usuario]->foto_perfil}"),
                'nombre' => "{$usuarios[$v->id_usuario]->nombres} {$usuarios[$v->id_usuario]->apellidos}",
                'text' => $v->estado ? "Finalizó la incidencia" : "Dio inicio a la incidencia",
                'data' => '<i class="fab fa-whatsapp"></i> ' . $usuarios[$v->id_usuario]->email_corporativo . ' / <i class="far fa-envelope"></i> ' . $usuarios[$v->id_usuario]->email_corporativo
            ]);
        }

        $html = '';
        foreach ($data as $v) {
            $html .= '
            <li class="list-group-item">
                <div class="row">
                    <div style="width: 70px;">
                        <img class="rounded-circle" style="width: 55px;" src="' . $v['img'] . '" alt="Profile image">
                    </div>
                    <div class="col">
                        <h6 class="mb-1 text-info" style="font-size: .9rem;font-weight: 600;font-family: ' . ("'Roboto'") . ';">' . $v['nombre'] . '</h6>
                        <p class="mb-1" style="font-size: .73rem;font-family: ' . ("'Roboto'") . ';">' . $v['text'] . '</p>
                        <p class="mb-1" style="font-size: .73rem;font-family: ' . ("'Roboto'") . ';">' . $v['data'] . '</p>
                    </div>
                </div>
            </li>
            ';
        }


        return $html;
    }

    public function detailOrden(string $cod) {
        $data = [
            'tecnicos' => ""
        ];
        $inc_asig = DB::table('tb_inc_asignadas')->where('cod_incidencia', $cod)->get();
        
        foreach (GlobalHelper::getUsuarios() as $val) {
            $usuarios[$val->id_usuario] = $val;
        }

        foreach ($inc_asig as $v) {
            $tecnico = "{$usuarios[$v->id_usuario]->nombres} {$usuarios[$v->id_usuario]->apellidos}";
            $data['tecnicos'] .= '<div class="list-group-item"><span class="">' . $tecnico . '</span></div>';
        }

        return $data;
    }

    function getparsedata($data)
    {
        foreach ($data as $val) {
            $data[$val->id] = $val->descripcion;
        }
        return $data;
    }
}
