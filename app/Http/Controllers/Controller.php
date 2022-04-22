<?php

namespace App\Http\Controllers;

use App\Tools\Errors;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function jsonResponse(array $data)
    {
        return new JsonResponse($data, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param int $errorCode
     * @param null $message
     * @param array $details
     * @return JsonResponse
     */
    protected function errorResponse(int $errorCode, $message = null, array $details = []): JsonResponse
    {
        return $this->jsonResponse([
            'status' => $errorCode,
            'message' => $message ?: Errors::$messages[$errorCode],
            'details' => $details
        ]);
    }
}
