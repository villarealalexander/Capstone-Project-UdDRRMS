<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email|',
        'password' => 'required',
    ]);
    

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        ActivityLogService::log('Logged in.', 'User logged in successfully.');

        switch (auth()->user()->role) {
            case 'MIS':
                return redirect()->route('MIS');
            case 'HeadRegistrar':
                return redirect()->route('HeadRegistrar');
            case 'Archiver':
                return redirect()->route('Archiver');
            case 'RegistrarStaff':
                return redirect()->route('RegistrarStaff');
            default:
                return redirect()->intended('/');
        }
    } else {
        $emailExists = User::where('email', $request->email)->exists();

        if ($emailExists) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.'
            ])->onlyInput('email');
        } else {
            return back()->withErrors([
                'email' => 'Email does not exist.'
            ]);
        }
    }
}
    public function logout(Request $request)
    {   
        
        ActivityLogService::log('Logged out.', 'User logged out successfully.');

        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect('/login');
    }
}
