<?php

namespace App\Http\Controllers;

use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\ExpectantMother;
use App\Models\Appointment;
use App\Models\MaternityRecord;

class NurseAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('nurse.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'registration_number' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('nurse')->attempt([
            'registration_number' => $request->registration_number,
            'password' => $request->password,
        ])) {
            $nurse = Auth::guard('nurse')->user();
            return redirect()->route('nurse.dashboard')->with('success', 'Welcome, ' . $nurse->full_name);
        }

        return back()->withErrors(['error' => 'Invalid credentials.']);
    }

    public function showRegisterForm()
    {
        return view('nurse.registration');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:nurses,registration_number',
            'email' => 'required|email|unique:nurses,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $nurse = Nurse::create([
            'full_name' => $request->full_name,
            'registration_number' => $request->registration_number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('nurse.showlogin')->with('success', 'Registration successful. Please log in.');
    }

    public function logout()
    {
        Auth::guard('nurse')->logout();
        Session::flush();
        return redirect()->route('nurse.showlogin')->with('success', 'Logged out successfully.');
    }

    public function dashboard()
    {
        if (!Auth::guard('nurse')->check()) {
            return redirect()->route('nurse.showlogin')->withErrors(['error' => 'Please log in first.']);
        }

        $nurse = Auth::guard('nurse')->user();
        $patient = Session::get('patient');
        $record = $patient ? MaternityRecord::where('mother_id', $patient->mother_id)->first() : null;

        return view('nurse.dashboard', compact('nurse', 'patient', 'record'));
    }

    public function searchPatient(Request $request)
    {
        $request->validate([
            'registrationNumber' => 'required|exists:expectant_mothers,mother_id',
        ]);

        $patient = ExpectantMother::where('mother_id', $request->registrationNumber)->first();

        if (!$patient) {
            return redirect()->route('nurse.dashboard')->withErrors(['error' => 'Patient not found.']);
        }

        Session::put('patient', $patient);
        return redirect()->route('nurse.dashboard')->with('success', 'Patient found.');
    }

    public function scheduleAppointment(Request $request)
    {
        $request->validate([
            'appointmentDate' => 'required|date',
            'appointmentTime' => 'required',
            'appointmentReason' => 'required|string',
        ]);

        $patient = Session::get('patient');
        $nurse = Auth::guard('nurse')->user();

        if (!$patient || !$nurse) {
            return redirect()->route('nurse.dashboard')->withErrors(['error' => 'No patient or nurse selected.']);
        }

        Appointment::create([
            'mother_id' => $patient->mother_id,
            'nurse_id' => $nurse->nurse_id, // Assuming nurse_id is the primary key
            'date' => $request->appointmentDate,
            'time' => $request->appointmentTime,
            'reason' => $request->appointmentReason,
        ]);

        return redirect()->route('nurse.dashboard')->with('success', 'Appointment scheduled successfully.');
    }

    public function saveMaternityRecords(Request $request)
    {
        $request->validate([
            'weight' => 'nullable|numeric',
            'bloodPressure' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'respiratoryRate' => 'nullable|integer',
        ]);

        $patient = Session::get('patient');
        if (!$patient) {
            return redirect()->route('nurse.dashboard')->withErrors(['error' => 'No patient selected.']);
        }

        MaternityRecord::updateOrCreate(
            ['mother_id' => $patient->mother_id],
            [
                'weight' => $request->weight,
                'blood_pressure' => $request->bloodPressure,
                'temperature' => $request->temperature,
                'height' => $request->height,
                'respiratory_rate' => $request->respiratoryRate,
            ]
        );

        return redirect()->route('nurse.dashboard')->with('success', 'Maternity records saved successfully.');
    }
}