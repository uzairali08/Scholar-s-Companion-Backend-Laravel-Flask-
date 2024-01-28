<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    
    function register(Request $req)
    {
        $user= new User;
        $user->firstName=$req->input('firstName');
        $user->lastName=$req->input('lastName');
        $user->email=$req->input('email');
        $user->cnic=$req->input('cnic');
        $user->password=Hash::make($req->input('password'));
        $user->gender=$req->input('gender');
        $user->nationality=$req->input('nationality');
        $user->dateOfBirth=$req->input('dateOfBirth');
        $user->save();
        return $user;
    }


    public function signin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return response()->json(['status' => 'success', 'user' => Auth::user()], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }
    }
}
