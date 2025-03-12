<?php

namespace App\Http\Controllers;

use App\Models\Pharmacist;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\ExpectantMother;
use App\Models\PhysicalExamFinding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PharmacistController extends Controller
{
    public function showLoginForm()
    {
        return view('pharmacist.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'registration_number' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('pharmacist')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('pharmacist.dashboard')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors(['registration_number' => 'Invalid credentials. Please register if you don’t have an account.']);
    }

    public function showRegistrationForm()
    {
        return view('pharmacist.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:pharmacists,registration_number',
            'email' => 'required|email|unique:pharmacists,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $pharmacist = new Pharmacist();
        $pharmacist->full_name = $request->full_name;
        $pharmacist->registration_number = $request->registration_number;
        $pharmacist->email = $request->email;
        $pharmacist->password = Hash::make($request->password); // Single hash
        $pharmacist->save();

        return redirect()->route('pharmacist.login')->with('success', 'Registration successful. Please log in.');
    }

    public function dashboard()
    {
        $pharmacist = Auth::guard('pharmacist')->user();
        $patient = session('patient');
        return view('pharmacist.dashboard', compact('pharmacist', 'patient'));
    }

    public function logout(Request $request)
    {
        Auth::guard('pharmacist')->logout();
        $request->session()->forget('patient'); // Added to clear patient data on logout
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('pharmacist.login')->with('success', 'Logged out successfully.');
    }

    public function searchPatient(Request $request)
    {
        $request->validate([
            'motherId' => 'required|string',
        ]);

        $patient = ExpectantMother::where('mother_id', $request->motherId)->first();

        if ($patient) {
            $request->session()->put('patient', $patient);
            return redirect()->route('pharmacist.dashboard')->with('success', 'Patient found.');
        }

        return back()->withErrors(['motherId' => 'Patient not found.']);
    }
// app/Http/Controllers/PharmacistController.php
public function getTreatmentPlan($motherId)
{
    $examFinding = PhysicalExamFinding::where('mother_id', $motherId)->latest()->first(); // Changed from ExamFinding to PhysicalExamFinding
    return response()->json([
        'treatment_plan' => $examFinding ? $examFinding->treatment_plan : null
    ]);
}

    public function dispenseMedication(Request $request)
    {
        $request->validate([
            'mother_id' => 'required|exists:expectant_mothers,mother_id',
            'medication_name' => 'required|string|max:255',
            'dosage_instructions' => 'required|string'
        ]);

        $prescription = new Prescription();
        $prescription->mother_id = $request->mother_id;
        $prescription->medication_name = $request->medication_name;
        $prescription->dosage_instructions = $request->dosage_instructions;
        $prescription->pharmacist_id = Auth::guard('pharmacist')->user()->id; // Assuming pharmacist has an id
        $prescription->save();

        return response()->json(['success' => 'Prescription dispensed successfully']);
    }
}