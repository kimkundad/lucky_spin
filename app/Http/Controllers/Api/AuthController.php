<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rules;
use Auth;



class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (auth()->attempt($request->all())) {
            return response([
                'user' => auth()->user(),
                'access_token' => auth()->user()->createToken('qbsDC4Tn4z7gCnGbFoyQamC7sOdx9velpl6FUJzT')->accessToken
            ], Response::HTTP_OK);
        }


        return response([
            'message' => 'This User does not exist'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function user()
    {
       dd(auth()->user());

        return response([
            'user' => auth()->user()
        ], Response::HTTP_OK);
    }

    public function logout(){
        return response([
            'user' => auth()->logout()
        ], Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);


        return response($user, Response::HTTP_CREATED);
    }
}
