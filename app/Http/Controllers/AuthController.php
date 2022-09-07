<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->all(['email', 'password']);

        $token = auth('api')->attempt($credentials);

        if (!$token)
        {
            return response()->json(['error' => 'Usuário ou senha inválidos'], 403);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth('logout')->logout();

        return response()->json(['message'=>'Logout foi realizado com sucesso.']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
