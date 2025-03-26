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

            $data['usuarios'] = DB::table('usuarios')->where(['estatus' => 1, 'eliminado' => 0, 'id_area' => 1])->get()->keyBy('id_usuario')->map(function ($u) {
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
            $turnos = DB::table('tb_cronograma_turno')->where('eliminado', 0)->whereYear('fecha_ini_s', $anio)->get()->keyBy('id');
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
                'shoraIni' => 'required|string',
                'sfechaFin' => 'required|date',
                'shoraFin' => 'required|string',
                'spersonal' => 'required|integer',
                'afechaIni' => 'required|date',
                'ahoraIni' => 'required|string',
                'afechaFin' => 'required|date',
                'ahoraFin' => 'required|string',
                'apersonal' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            DB::beginTransaction();
            $idTurno = DB::table('tb_cronograma_turno')->insertGetId([
                'fecha_ini_s' => $request->sfechaIni,
                'hora_ini_s' => $request->shoraIni,
                'fecha_fin_s' => $request->sfechaFin,
                'hora_fin_s' => $request->shoraFin,
                'personal_s' => $request->spersonal,
                'fecha_ini_a' => $request->afechaIni,
                'hora_ini_a' => $request->ahoraIni,
                'fecha_fin_a' => $request->afechaFin,
                'hora_fin_a' => $request->ahoraFin,
                'personal_a' => $request->apersonal,
                'creador' => Auth::user()->id_usuario,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            $data = [];

            return $this->message(message: "Turnos asignados exitosamente.", data: ["turno" => $idTurno]);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'sfechaIni' => 'required|date',
                'shoraIni' => 'required|string',
                'sfechaFin' => 'required|date',
                'shoraFin' => 'required|string',
                'spersonal' => 'required|integer',
                'afechaIni' => 'required|date',
                'ahoraIni' => 'required|string',
                'afechaFin' => 'required|date',
                'ahoraFin' => 'required|string',
                'apersonal' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            DB::beginTransaction();
            DB::table('tb_cronograma_turno')->where('id', $request->id)->update([
                'fecha_ini_s' => $request->sfechaIni,
                'hora_ini_s' => $request->shoraIni,
                'fecha_fin_s' => $request->sfechaFin,
                'hora_fin_s' => $request->shoraFin,
                'personal_s' => $request->spersonal,
                'fecha_ini_a' => $request->afechaIni,
                'hora_ini_a' => $request->ahoraIni,
                'fecha_fin_a' => $request->afechaFin,
                'hora_fin_a' => $request->ahoraFin,
                'personal_a' => $request->apersonal,
                'creador' => Auth::user()->id_usuario,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return $this->message(message: "Turnos actualizados exitosamente.", data: ["turno" => $request->id]);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            DB::beginTransaction();
            DB::table('tb_cronograma_turno')->where('id', $request->id)->update([
                'eliminado' => 1,
                'creador' => Auth::user()->id_usuario,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return $this->message(message: "Registro eliminado exitosamente.", data: ["turno" => $request->id]);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
