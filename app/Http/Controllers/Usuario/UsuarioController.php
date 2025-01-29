<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        } catch (Exception $e) {
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
            ->where('eliminado', 0)
            ->get()->map(function ($usu) use($tipoAcceso) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                $usu->nombres = explode(' ', $usu->nombres)[0];
                $usu->apellidos = explode(' ', $usu->apellidos)[0];
                $usu->descripcion = $tipoAcceso[$usu->tipo_acceso]->descripcion;
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
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            
            DB::beginTransaction();
            $filenameFP = "user_auth.jpg";
            if ($request->foto_perfil) {
                $result = $this->parseFile('fp_' . $request->usuario, 'auth', $request->foto_perfil);
                if (!$result['success']) {
                    throw new Exception("Error al intentar crear la imagen del perfil");
                }
                $filenameFP = $result['filename'];
            }

            $filenameFD = "";
            if ($request->firma_digital) {
                $result = $this->parseFile('fd_' . $request->usuario, 'firms', $request->firma_digital);
                if (!$result['success']) {
                    throw new Exception("Error al intentar crear la firma digital");
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
                'menu_usuario' => '{}',
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Registro Exitoso.']);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $usuario = DB::table('usuarios')->where('id_usuario', $id)->first();
            return response()->json(["success" => true, "message" => "", "data" => $usuario]);
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
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
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
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
                'menu_usuario' => '{}',
                'updated_at' => now()->format('Y-m-d H:i:s')
            ];

            DB::beginTransaction();
            if ($request->foto_perfil) {
                $result = $this->parseFile('fp_' . $request->usuario, 'auth', $request->foto_perfil);
                if (!$result['success']) {
                    throw new Exception("Error al intentar crear la imagen del perfil");
                }
                $update['foto_perfil'] = $result['filename'];
            }

            if ($request->firma_digital) {
                $result = $this->parseFile('fd_' . $request->usuario, 'firms', $request->firma_digital);
                if (!$result['success']) {
                    throw new Exception("Error al intentar crear la firma digital");
                }
                $update['firma_digital'] = $result['filename'];
            }

            DB::table('usuarios')->where('id_usuario', $request->id)->update($update);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Registro Exitoso.']);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
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
                return response()->json(['errors' => $validator->errors()], 400);

            DB::beginTransaction();
            DB::table('usuarios')->where('id_usuario', $request->id)->update([
                'estatus' => $request->estatus,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Cambio de estado exitoso.']);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
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
