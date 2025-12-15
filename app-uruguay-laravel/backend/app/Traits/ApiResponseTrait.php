<?php

namespace App\Traits;

trait ApiResponseTrait
{
    /**
     * Respuesta exitosa.
     */
    public function success($data, $message = 'Operación exitosa', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Respuesta de error general.
     */
    public function fail($message = 'Error en la operación', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    /**
     * Respuesta de error natural (errores no controlados).
     */
    public function naturalFail($message = 'Ocurrió un error inesperado', $code = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
