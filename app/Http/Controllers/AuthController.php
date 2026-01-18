<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct() {}

    /**
     * Show the login form
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return view("auth.login");
    }
    /**
     * Show the register form
     * @return \Illuminate\Contracts\View\View
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    /**
     * Login the user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
        
        return redirect()->route('home');
    }

    /**
     * Register the user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        return redirect()->route('home');
    }

    /**
     * Logout the user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
