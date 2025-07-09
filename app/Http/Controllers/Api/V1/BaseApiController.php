<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseApiController extends Controller {
    public function __construct() {
        JsonResource::withoutWrapping();
    }

    protected function success($data = [], $status = 200): JsonResponse {
        return response()->json($data, $status);
    }

    protected function error($message, $status = 400): JsonResponse {
        return response()->json([
            'error' => $message,
        ], $status);
    }
}
