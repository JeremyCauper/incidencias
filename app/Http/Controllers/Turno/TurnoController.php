<?php

namespace App\Http\Controllers\Turno;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TurnoController extends Controller
{
    public function view()
    {
        // $this->validarPermisos(1);
        try {
            $data = [];

            $data['usuarios'] = DB::table('usuarios')->where('estatus', 1)->get()->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
                return [
                    'value' => $u->id_usuario,
                    'dValue' => base64_encode(json_encode(['id' => $u->id_usuario, 'doc' => $u->ndoc_usuario, 'nombre' => $nombre])),
                    'text' => "{$u->ndoc_usuario} - {$nombre}"
                ];
            });

            return view('turno.turno', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function index(Request $request)
    {
        $anio = $request->query('anio');
        try {
            $turnos = DB::table('tb_cronograma_turno')->where('eliminado', 0)->whereYear('fecha_ini_s', $anio)->get();
            return $turnos;
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sfechaIni' => 'required|date',
                'sfechaFin' => 'required|date',
                'spersonal' => 'required|integer',
                'afechaIni' => 'required|date',
                'afechaFin' => 'required|date',
                'apersonal' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            DB::beginTransaction();
            DB::table('tb_cronograma_turno')->insert([
                'fecha_ini_s' => $request->sfechaIni,
                'hora_ini_s' => "18:00:00",
                'fecha_fin_s' => $request->sfechaFin,
                'hora_fin_s' => "07:59:00",
                'personal_s' => $request->spersonal,
                'fecha_ini_a' => $request->afechaIni,
                'hora_ini_a' => "13:00:00",
                'fecha_fin_a' => $request->afechaFin,
                'hora_fin_a' => "07:59:00",
                'personal_a' => $request->apersonal,
                'creador' => Auth::user()->id_usuario,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            $data = [];

            return $this->message(message: "Turnos asignados exitosamente.");
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
