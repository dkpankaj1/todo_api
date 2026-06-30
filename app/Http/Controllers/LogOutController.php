<?php

namespace App\Http\Controllers;

use App\Services\TokenService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LogOutController extends Controller
{
    use ApiResponse;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        TokenService::destroy();
        return $this->sendSuccess(message: 'logout success');
    }
}
