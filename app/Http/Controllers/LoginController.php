<?php

namespace App\Http\Controllers;

use App\Services\TokenService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use ApiResponse;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->toArray(), 'validation error');
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->sendError(['login' => 'Invalid Login Details.'], 'login error');
        }

        return $this->sendSuccess([
            'user' => Auth::user(),
            'token' => TokenService::generate()
        ], 'login Success');
    }
}
