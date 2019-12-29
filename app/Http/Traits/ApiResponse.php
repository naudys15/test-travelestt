<?php

namespace Travelestt\Http\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Construye respuesta satisfactorias
     *
     * @param array|string $data
     * @param int $code
     * @return Http\JsonResponse
     */
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json([ 'status' => 'success', 'message' => $data ], $code);
    }

    /**
     * Construye respuesta de error
     *
     * @param string $message
     * @param int $code
     * @return Http\JsonResponse
     */
    public function errorResponse($message, $code)
    {
        return response()->json([ 'status' => 'error', 'message' => $message], $code);
    }
}