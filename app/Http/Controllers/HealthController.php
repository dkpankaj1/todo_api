<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    use ApiResponse;
    /**
     * Health check endpoint.
     */
    public function health(): JsonResponse
    {
        return $this->sendSuccess([
            'status' => 'ok',
            'service' => config('app.name'),
            'environment' => app()->environment(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'timestamp' => now()->toIso8601String(),
        ], 'System is healthy');
    }
}
