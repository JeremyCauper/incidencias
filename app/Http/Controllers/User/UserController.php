<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function DataTableUser()
    {
        $usuarios = DB::table('usuarios')
            ->join('tipo_usuario', 'usuarios.tipo_acceso', '=', 'tipo_usuario.id_tipo_acceso')
            ->select('usuarios.id_usuario', 'usuarios.nombres', 'usuarios.apellidos', 'tipo_usuario.descripcion', 'usuarios.usuario', 'usuarios.contrasena', 'usuarios.estatus')
            ->get();

        return $usuarios;
    }

    public function RegisterUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_area' => 'required|integer',
            'n_doc' => 'required|integer',
            'nom_usu' => 'required|string',
            'ape_usu' => 'required|string',
            'email_usu' => 'required|email',
            'fechan_usu' => 'required|date',
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
            'email' => $request->email_usu,
            'fecha_nacimiento' => $request->fechan_usu,
            'usuario' => $request->usuario,
            'contrasena' => Hash::make($request->contrasena),  // Encriptar la contraseña
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

    public function UpdateUser(Request $request)
    {
        //
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
