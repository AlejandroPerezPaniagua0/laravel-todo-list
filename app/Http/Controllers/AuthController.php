<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct() {}

    public function showLoginForm() 
    {
        return view("auth.login");
    }
    public function showRegisterForm() 
    {
        return view('auth.register');
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required|string"
        ]);
        // Use generic authentication error to prevent user enumeration
        if (!Auth::attempt($validated)) {
            return back()->withErrors(["email" => "invalid credentials"])->withInput();
        }

        $request->session()->regenerate();
        // Temporary redirect until UI is implemented
        return redirect()->route('home');

    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "email" => "required|unique:users,email",
            "password" => "required|string|min:8"
        ]);

        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"])
        ]);
        Auth::login($user);
        $request->session()->regenerate();

        // Temporary redirect until UI is implemented
        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
