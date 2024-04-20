<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

trait ApiCommonResponses
{
    public function success(array $data, array $additional=null): JsonResponse
    {
        if ($additional) {
            return response()->json(array_merge([
                'data' => $data,
            ], $additional), Response::HTTP_OK);
        }

        return response()->json([
            'data' => $data,
        ], Response::HTTP_OK);
    }

	public function error(Exception|Throwable $e): JsonResponse
    {
        $code = $e->getCode();

        logger()->error($e->getMessage());

        return response()->json([
            'message' => $e->getMessage(),
        ], (gettype($code) == 'integer' && $code != 0) ? $code : Response::HTTP_BAD_REQUEST);
    }
}
