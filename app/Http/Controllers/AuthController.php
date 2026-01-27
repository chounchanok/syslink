<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin;

class AuthController extends Controller
{
    public function login(){
        return view('backoffice.login');
    }
    public function login_func(Request $request){
        if (auth()->guard('admin')->attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
            // $admin=Admin::find('');
            $user = Admin::where('username', $request['username'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->remember_token=$token;
            $user->save();
            // dd(auth()->guard('admin')->user());
            return redirect('/');
        } else {
            return redirect()->back()->with('fail','your username and password are wrong.');
        }
    }
    public function logout_admin(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->back();
    }
}
