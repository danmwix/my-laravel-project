<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function showRoleSelection()
    {
        return view('overall');
    }

    public function selectRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:expectant,healthcare',
            'providerType' => 'required_if:role,healthcare|in:doctor,nurse,pharmacist'
        ]);

        if ($request->role === 'expectant') {
            return redirect()->route('login')->with('success', 'Role selected successfully. Please log in.');
        } else {
            if ($request->providerType === 'nurse') {
                return redirect()->route('nurse.login')->with('success', 'Role selected successfully. Please log in or register as a nurse.');
            } elseif ($request->providerType === 'doctor') {
                return redirect()->route('doctor.login')->with('success', 'Role selected successfully. Please log in or register as a doctor.');
            } elseif ($request->providerType === 'pharmacist') {
                return redirect()->route('pharmacist.login')->with('success', 'Role selected successfully. Please log in or register as a pharmacist.');
            } else {
                return redirect()->route('login')->with('error', 'Role not yet supported.');
            }
        }
    }
}