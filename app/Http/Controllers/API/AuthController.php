<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');
        if (Auth()->attempt($credentials)) {
            $user = $request->user();
            if ($user->tokens) {
                $user->tokens()->delete();
            }

            $token = $user->createToken('Personal Access Token')->plainTextToken;
            return response()->json([
                'status' => 'login success',
                'token_type' => 'Bearer',
                'access_token' => $token,
            ], 201);
        } else {
            return response()->json([
                'status' => 'login failed'
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 'logout success!'
        ], 200);
    }
}
