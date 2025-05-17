<?php

namespace App\Services;

class SqlStateHelper
{
    public static function getUserFriendlyMsg($sqlState): object
    {
        $codigo = is_numeric($sqlState) ? $sqlState : 500;
        $messages = [
            '23000' => 'No se pudo completar la operación porque ya existe un registro con los mismos datos.',
            '42000' => 'Ocurrió un error interno al procesar la solicitud. Intente nuevamente más tarde.',
            '42S02' => 'Uno de los datos solicitados no está disponible en este momento.',
            '28000' => 'No tiene permiso para realizar esta acción. Verifique sus credenciales.',
            'HY000' => 'Estamos teniendo problemas para procesar su solicitud. Por favor, inténtelo más tarde.',
            '22007' => 'La fecha o el formato ingresado no es válido.',
            '22003' => 'Uno de los valores ingresados es demasiado grande.',
            '22001' => 'Ha ingresado un texto demasiado largo. Redúzcalo e intente nuevamente.',
            '42S22' => 'Uno de los datos requeridos no fue encontrado. Puede que el formulario esté desactualizado.',
            '40001' => 'No se pudo completar la operación por un problema de concurrencia. Intente nuevamente.',
            '08S01' => 'No se pudo conectar al servidor. Por favor, revise su conexión a internet.',
        ];

        return (object)[
            'codigo' => $codigo,
            'message' => $messages[$sqlState] ?? 'Ha ocurrido un error inesperado. Por favor, inténtelo más tarde.'
        ];
    }
}
