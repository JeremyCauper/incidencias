<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    private $validator = [
        "u_ndoc_usuario" => "n_doc",
        "u_usuario" => "usuario",
        "u_contrasena" => "contrasena",
        "u_email_personal" => "emailp_usu",
        "u_email_corporativo" => "emailc_usu",
        "u_tel_personal" => "telp_usu",
        "u_tel_corporativo" => "telc_usu"
    ];

    public function view()
    {
        $this->validarPermisos(5, 6);
        try {
            $data = [];
            $data['areas'] = DB::table('tb_area')->where('estatus', 1)->get();
            $data['tipoAcceso'] = DB::table('tipo_usuario')->where('estatus', 1)->get();

            $tipo_menu = [0];
            if (session('tipo_acceso') == 5) {
                array_push($tipo_menu, 1);
            }

            $menu = DB::table('tb_menu')->select(['id_menu', 'descripcion', 'icon'])
                ->where('estatus', 1)
                ->whereIn('sistema', $tipo_menu)->get();
            $submenus = DB::table('tb_submenu')->select(['id_submenu', 'id_menu', 'descripcion', 'categoria'])->where('estatus', 1)->get()->groupBy('id_menu');

            $data['menus'] = $menu->map(function ($item) use ($submenus) {
                $menuId = $item->id_menu;
                if ($submenus->has($menuId)) {
                    // Agrupar submenús por categoría
                    $groupedByCategory = $submenus[$menuId]->groupBy('categoria');
                    // Transformar cada categoría en una clave dentro de submenu
                    $item->submenu = $groupedByCategory->mapWithKeys(function ($submenusList, $category) {
                        return [$category ?: 'sin_categoria' => $submenusList->values()];
                    });
                } else {
                    $item->submenu = [];
                }

                return $item;
            });
            // return $data['menus'];

            return view('usuario.usuario', $data);
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
            $tipoAcceso = DB::table('tipo_usuario')->get()->keyBy('id_tipo_acceso');
            $usuarios = DB::table('usuarios')
                ->select('ndoc_usuario', 'id_usuario', 'nombres', 'apellidos', 'usuario', 'pass_view', 'estatus', 'tipo_acceso')
                ->where('eliminado', 0)
                ->when(session('tipo_acceso') != 5, function ($query) {
                    return $query->whereNot('tipo_acceso', 5);
                })
                ->get()->map(function ($usu) use ($tipoAcceso) {
                    $estado = [
                        ['color' => 'danger', 'text' => 'Inactivo'],
                        ['color' => 'success', 'text' => 'Activo']
                    ];
                    $usu->personal = $this->formatearNombre($usu->nombres, $usu->apellidos);
                    $usu->descripcion = '<label class="badge badge-' . $tipoAcceso[$usu->tipo_acceso]->color . '" style="font-size: .7rem;">' . $tipoAcceso[$usu->tipo_acceso]->descripcion . '</label>';
                    $usu->estado = '<label class="badge badge-' . $estado[$usu->estatus]['color'] . '" style="font-size: .7rem;">' . $estado[$usu->estatus]['text'] . '</label>';
                    $usu->acciones = $this->DropdownAcciones([
                        'tittle' => 'Acciones',
                        'button' => [
                            ['funcion' => "Editar($usu->id_usuario)", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                            ['funcion' => "CambiarEstado($usu->id_usuario, $usu->estatus)", 'texto' => '<i class="fas fa-rotate me-2 text-' . $estado[$usu->estatus]['color'] . '"></i>Cambiar Estado'],
                            // ['funcion' => "ShowAssign(this, $usu->acciones)", 'texto' => '<i class="fas fa-user-plus me-2"></i> Asignar'],
                        ],
                    ]);
                    return $usu;
                });

            return $usuarios;
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
            $validator = Validator::make($request->all(), [
                'id_area' => 'required|integer',
                'n_doc' => 'required|integer',
                'nom_usu' => 'required|string',
                'ape_usu' => 'required|string',
                'emailp_usu' => 'nullable|email',
                'emailc_usu' => 'nullable|email',
                'fechan_usu' => 'required|date',
                'telp_usu' => 'nullable|string',
                'telc_usu' => 'nullable|string',
                'usuario' => 'required|string',
                'contrasena' => 'required|string',
                'foto_perfil' => 'nullable|string',
                'firma_digital' => 'nullable|string',
                'tipo_acceso' => 'required|integer',
                'permisos' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }


            DB::beginTransaction();
            $filenameFP = "user_auth.jpg";
            if ($request->foto_perfil) {
                $result = $this->parseFile('fp_' . $request->usuario, 'auth', $request->foto_perfil);
                if (!$result['success']) {
                    return $this->message(message: "Error al intentar crear la imagen del perfil", data: ['error' => $result['error']], status: 400);
                }
                $filenameFP = $result['filename'];
            }

            $filenameFD = "";
            if ($request->firma_digital) {
                $result = $this->parseFile('fd_' . $request->usuario, 'firms', $request->firma_digital);
                if (!$result['success']) {
                    return $this->message(message: "Error al intentar crear la firma digital", data: ['error' => $result['error']], status: 400);
                }
                $filenameFD = $result['filename'];
            }


            DB::table('usuarios')->insert([
                'ndoc_usuario' => $request->n_doc,
                'nombres' => $request->nom_usu,
                'apellidos' => $request->ape_usu,
                'email_personal' => $request->emailp_usu,
                'email_corporativo' => $request->emailc_usu,
                'fecha_nacimiento' => $request->fechan_usu,
                'tel_personal' => $request->telp_usu,
                'tel_corporativo' => $request->telc_usu,
                'usuario' => $request->usuario,
                'contrasena' => Hash::make($request->contrasena),  // Encriptar la contraseña
                'pass_view' => $request->contrasena,
                'foto_perfil' => $filenameFP,
                'firma_digital' => $filenameFD,
                'tipo_acceso' => $request->tipo_acceso,
                'id_area' => $request->id_area,
                'menu_usuario' => $request->permisos,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return $this->message(message: "Registro creado exitosamente.");
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $usuario = DB::table('usuarios')->where('id_usuario', $id)->first();
            return $this->message(data: ['data' => $usuario]);
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
                'id' => 'required|integer',
                'id_area' => 'required|integer',
                'n_doc' => 'required|integer',
                'nom_usu' => 'required|string|max:250',
                'ape_usu' => 'required|string',
                'emailp_usu' => 'nullable|email',
                'emailc_usu' => 'nullable|email',
                'fechan_usu' => 'required|date',
                'telp_usu' => 'nullable|string',
                'telc_usu' => 'nullable|string',
                'usuario' => 'required|string',
                'contrasena' => 'required|string',
                'foto_perfil' => 'nullable|string',
                'firma_digital' => 'nullable|string',
                'tipo_acceso' => 'required|integer',
                'permisos' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            $update = [
                'ndoc_usuario' => $request->n_doc,
                'nombres' => $request->nom_usu,
                'apellidos' => $request->ape_usu,
                'email_personal' => $request->emailp_usu,
                'email_corporativo' => $request->emailc_usu,
                'fecha_nacimiento' => $request->fechan_usu,
                'tel_personal' => $request->telp_usu,
                'tel_corporativo' => $request->telc_usu,
                'usuario' => $request->usuario,
                'contrasena' => Hash::make($request->contrasena),  // Encriptar la contraseña
                'pass_view' => $request->contrasena,
                'tipo_acceso' => $request->tipo_acceso,
                'id_area' => $request->id_area,
                'menu_usuario' => $request->permisos,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ];

            DB::beginTransaction();
            if ($request->foto_perfil) {
                $result = $this->parseFile('fp_' . $request->usuario, 'auth', $request->foto_perfil);
                if (!$result['success']) {
                    return $this->message(message: "Error al intentar crear la imagen del perfil", data: ['error' => $result['error']], status: 400);
                }
                $update['foto_perfil'] = $result['filename'];
            }

            if ($request->firma_digital) {
                $result = $this->parseFile('fd_' . $request->usuario, 'firms', $request->firma_digital);
                if (!$result['success']) {
                    return $this->message(message: "Error al intentar crear la firma digital", data: ['error' => $result['error']], status: 400);
                }
                $update['firma_digital'] = $result['filename'];
            }

            DB::table('usuarios')->where('id_usuario', $request->id)->update($update);
            DB::commit();

            return $this->message(message: "Registro actualizado exitosamente.");
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

    public function changeStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'estatus' => 'required|integer'
            ]);

            if ($validator->fails())
                return $this->message(data: ['required' => $validator->errors()], status: 422);

            DB::beginTransaction();
            DB::table('usuarios')->where('id_usuario', $request->id)->update([
                'estatus' => $request->estatus,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return $this->message(message: "Cambio de estado exitoso.");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function parseFile($name, $dir, $data)
    {
        $foto_b64 = explode(',', base64_decode($data));
        $imgInfo = base64_decode($foto_b64[1]);
        $imgFormat = getimagesizefromstring($imgInfo);
        $formato = '.' . explode('/', $imgFormat['mime'])[1];
        $filename = $name . $formato;

        $path = public_path("front/images/$dir");
        $filePath = "$path/$filename";

        if (file_put_contents($filePath, $imgInfo)) {
            return ['success' => true, 'filename' => $filename];
        }
        return ['success' => false];
    }
}
