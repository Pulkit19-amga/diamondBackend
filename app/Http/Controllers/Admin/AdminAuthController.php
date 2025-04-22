<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            if (auth()->user()->user_type === 'admin') {
    
                // If request expects JSON (API)
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Login successful',
                        'user' => auth()->user()
                    ]);
                }
    
                // If it's a web request (browser)
                return redirect()->route('admin.dashboard');
            }
    
            Auth::logout();
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized. Not an admin user.'
                ], 401);
            }
    
            return back()->withErrors(['email' => 'Unauthorized']);
        }
    
        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }
    
        return back()->withErrors(['email' => 'Invalid credentials']);
    }
    

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin.login');
}

}

