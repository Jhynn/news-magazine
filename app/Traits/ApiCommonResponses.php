<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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

	public function error(\Exception $e): JsonResponse
    {
        return response()->json([
            'message' => $e->getMessage(),
        ], $e->getCode() ?? Response::HTTP_BAD_REQUEST);
    }
}
