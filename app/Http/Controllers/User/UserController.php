<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Type\Integer;

class UserController extends Controller
{
    public function consultaDni(string $dni) {
        $bdDni = DB::table('usuarios')
            ->select('ndoc_usuario')
            ->where('ndoc_usuario', $dni)
            ->get();

        if (count($bdDni))
            return ["success" => false, "message" => "El usuario que desea registrar ya existe", "data" => []];

        $consulta = json_decode(file_get_contents("http://localhost/new_dni/consulta.php?documento=$dni"), true);
        return $consulta;
    }
    
    public function DataTableUser()
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

    public function RegisterUser(Request $request)
    {
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

        $filenameFP = "user_auth.jpg";
        if ($request->foto_perfil) {
            $result = $this->parseFile('fp_' . $request->usuario, 'auth', $request->foto_perfil);
            if (!$result['success']) {
                return response()->json(['success' => false, 'message' => 'Error al intentar crear la imagen del perfil'], 500);
            }
            $filenameFP = $result['filename'];
        }

        $filenameFD = "";
        if ($request->firma_digital) {
            $result = $this->parseFile('fd_' . $request->usuario, 'firms', $request->firma_digital);
            if (!$result['success']) {
                return response()->json(['success' => false, 'message' => 'Error al intentar crear la firma digital'], 500);
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
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Usuario registrado con éxito'], 201);
    }

    public function ShowUser(string $id)
    {
        $showUsu = DB::table('usuarios')
            ->where('id_usuario', $id)
            ->get();

        return $showUsu;
    }

    public function EditUser(Request $request, string $id)
    {
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
            'updated_at' => now()
        ];

        if ($request->foto_perfil) {
            $result = $this->parseFile('fp_' . $request->usuario, 'auth', $request->foto_perfil);
            if (!$result['success']) {
                return response()->json(['success' => false, 'message' => 'Error al intentar crear la imagen del perfil'], 500);
            }
            $update['foto_perfil'] = $result['filename'];
        }

        if ($request->firma_digital) {
            $result = $this->parseFile('fd_' . $request->usuario, 'firms', $request->firma_digital);
            if (!$result['success']) {
                return response()->json(['success' => false, 'message' => 'Error al intentar crear la firma digital'], 500);
            }
            $update['firma_digital'] = $result['filename'];
        }

        DB::table('usuarios')
        ->where('id_usuario', $id)
        ->update($update);

        return response()->json(['message' => 'Usuario editado con éxito'], 201);
    }

    public function UpdateEstatus(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'estatus' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $estatus = $request->estatus ? 0 : 1;
        DB::table('usuarios')
        ->where('id_usuario', $id)
        ->update(['estatus' => $estatus]);

        return response()->json(['message' => 'Usuario ' . ($estatus ? '' : 'in') . 'habilitado con éxito'], 201);
    }

    public function parseFile($name, $dir, $data)
    {
        $foto_b64 = explode(',', base64_decode($data));
        $imgInfo = base64_decode($foto_b64[1]);
        $imgFormat = getimagesizefromstring($imgInfo);
        $formato = '.' . explode('/', $imgFormat['mime'])[1];
        $filename = $name . $formato;

        $path = public_path("assets/images/$dir");
        $filePath = "$path/$filename";

        if (file_put_contents($filePath, $imgInfo)) {
            return ['success' => true, 'filename' => $filename];
        }
        return ['success' => false];
    }
}
