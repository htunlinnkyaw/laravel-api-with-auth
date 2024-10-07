<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['message' => 'register success', 'token' => $token], 200);

    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'login success',
                'token' => $token
            ], 200);
        }

        return response()->json([
            'message' => 'login failed'
        ], 401);



    }

    public function logout(Request $request)
    {
        return $request->user()->currentAccessToken();


    }

}
