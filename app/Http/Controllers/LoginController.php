<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function auth(Request $request)
    {
        // return $request->all();
        $user= User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([ 'message' => ['These credentials do not match our records.'] ], 404);
        }
        
        $token = $user->createToken('my-app-token')->plainTextToken;
        
        $response = [ 'user' => $user, 'token' => $token ];

        return response($response, 201);
    }
}
