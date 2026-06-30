<?php

namespace App\Traits;

trait ApiResponse
{
    public function sendSuccess(array $data = [], string $message = "success", int $code = 200)
    {
        return response()->json([
            'success' => 1,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    public function sendError(array $error = [], string $message = "error", int $code = 400)
    {
        return response()->json([
            'success' => 0,
            'message' => $message,
            'error'  => $error,
        ], $code);
    }
}
