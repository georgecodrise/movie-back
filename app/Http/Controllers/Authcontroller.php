<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authcontroller extends Controller
{
    public function register(RegisterRequest $request){
        
        $user = $request->validated();

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return ['message' => 'Usuario creado'];

        return dd($user);

    }

    public function login(LoginRequest $request){
        
        $data = $request->validated();

        //Revisar el password
        if(!Auth::attempt($data)){
            return response([
               
               'errors' => ['Email o Contraseña incorrectos']
               
            ],422);
        }

        $user = Auth::user();
        return[
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user,
         ];

    }

    public function logout(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return[
            'user'=>null,
        ];
    }
}
