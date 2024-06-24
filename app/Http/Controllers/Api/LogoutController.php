<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //remove token
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if ($removeToken) {
            auth()->logout();
            //return response JSON
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil!',
            ]);
        }
    }
    public function refresh()
{
    try {
        $newToken = JWTAuth::parseToken()->refresh();
        return response()->json(['token' => $newToken]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Could not refresh token'], 401);
    }
}
}
