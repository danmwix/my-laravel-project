<?php

namespace App\Http\Controllers;

use App\Models\ExpectantMother;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (Auth::guard('expectant_mother')->attempt($credentials)) {
            $mother = Auth::guard('expectant_mother')->user();
            return redirect()->route('mother.dashboard')->with('success', 'Welcome, ' . $mother->full_name . '!');
        }

        return back()->withErrors(['email' => 'Invalid email or password']);
    }

    public function showRegister()
    {
        return view('auth.registration');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:expectant_mothers,email',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required|string|max:20',
            'dob' => 'required|date',
            'age' => 'required|integer|min:1|max:120',
            'place_of_residence' => 'required|string|max:255',
        ]);

        $mother = ExpectantMother::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'phone' => $request->phone,
            'dob' => $request->dob,
            'age' => $request->age,
            'place_of_residence' => $request->place_of_residence,
        ]);

        Auth::guard('expectant_mother')->login($mother);
        return redirect()->route('mother.dashboard')->with('success', 'Welcome, ' . $mother->full_name . '!');
    }

    public function logout(Request $request)
    {
        Auth::guard('expectant_mother')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
