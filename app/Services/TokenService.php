<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class TokenService
{
    public static function generate()
    {
        Auth::user()->tokens()->delete();
        return Auth::user()->createToken('access_token')->plainTextToken;
    }

    public static function destroy(): void
    {
        Auth::user()->tokens()->delete();
    }
}
