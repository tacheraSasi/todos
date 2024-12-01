<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    #showing the login view
    public function showLoginForm(){
        return view("auth.login");
    }

    #handling the login logic
    public function login(Request $request){
        $credentials = $request->only("email","password");

        if(Auth::attempt($credentials)){
            return redirect(route("tasks"));

        }

        return back()->withErrors([
            "email"=> "Invalid credentials"
        ]);
    }

    public function showRegistrationForm(){
        return view("auth.register");
    }

    public function register(Request $request){
        $request->validate([
            "name"=>"required",
            "email"=> "required|email|unique:users",
            "password"=> "required|min:6|confirmed"
        ]);
        $user = User::create([
            "name"=> $request->name,
            "email"=> $request->email,
            "password"=>Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect(route("tasks"));
        // dd($request);
    }

    public function logout(){
        Auth::logout();
        return redirect(route("login"));
    }
}
