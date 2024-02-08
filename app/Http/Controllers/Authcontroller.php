<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class Authcontroller extends Controller
{
    public function register(RegisterRequest $request){
        
        //$user = $request->validated();

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();


        return dd($user);

    }
}
