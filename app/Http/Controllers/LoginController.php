<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function loginUser(Request $req)
    {
        $user = User::where('email', $req->email)->first();

        if (!$user || !Hash::check($req->password, $user->password)) {
            return Controller::Response('', false, 'wrong credentials');
        }

        $user = User::where('email', $req->email)->first();
        $token = $user->createToken($req->email)->plainTextToken;
        return  Controller::Response(['token' => $token], true, '');
    }
    public function notLogin()
    {
        return Controller::Response('', false, 'login failed');
    }
}
