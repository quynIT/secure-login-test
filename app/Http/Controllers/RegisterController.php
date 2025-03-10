<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:register,email',
            'password' => 'required|min:6',
            'phone' => 'required|regex:/^0[0-9]{9}$/',
        ]);
    
        $user = Register::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
        ]);
        Mail::to($user->email)->send(new WelcomeMail($user));
        return response()->json(['message' => 'Register success'], 201);
    }
    
}
