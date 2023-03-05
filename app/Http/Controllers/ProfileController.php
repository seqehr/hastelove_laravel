<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function userProfile(Request $req, $id)
    {
        if ($id == 'self') {
            $user = User::where('id', $req->user()->id)->first();
            return Controller::Response($user, true, '');
        } else {
            $user = User::where('id', $id)->first();
            return Controller::Response($user, true, '');
        }
    }
}
