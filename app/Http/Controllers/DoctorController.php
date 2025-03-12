<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\ExpectantMother;
use App\Models\EmergencyMessage;
use App\Models\PhysicalExamFinding;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class DoctorController extends Controller
{
    // Show Login Form
    public function showLoginForm()
    {
        return view('doctor.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'registration_number' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('doctor')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('doctor.dashboard')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors(['registration_number' => 'Invalid credentials. Please register if you don’t have an account.']);
    }

    // Show Registration Form
    public function showRegistrationForm()
    {
        return view('doctor.register');
    }

    // Handle Registration
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:doctors,registration_number',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $doctor = Doctor::create([
            'full_name' => $request->full_name,
            'registration_number' => $request->registration_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('doctor.login')->with('success', 'Registration successful. Please log in.');
    }

    // Show Dashboard
    public function dashboard()
    {
        if (!Auth::guard('doctor')->check()) {
            return redirect()->route('doctor.login')->withErrors(['error' => 'Please log in first.']);
        }

        $doctor = Auth::guard('doctor')->user();
        $patient = Session::get('patient');
        $examFinding = $patient ? PhysicalExamFinding::where('mother_id', $patient->mother_id)->first() : null;
        $appointment = $patient ? Appointment::where('mother_id', $patient->mother_id)->latest()->first() : null;

        Log::info('Doctor Dashboard loaded. Doctor: ' . $doctor->registration_number . ', Patient: ' . ($patient ? $patient->mother_id : 'None'));

        return view('doctor.dashboard', compact('doctor', 'patient', 'examFinding', 'appointment'));
    }

    // Search Patient
    public function searchPatient(Request $request)
    {
        $request->validate([
            'motherId' => 'required|string|exists:expectant_mothers,mother_id',
        ]);

        $patient = ExpectantMother::where('mother_id', $request->motherId)->first();

        if ($patient) {
            Session::put('patient', $patient);
            Log::info('Patient found and stored in session: ' . $patient->mother_id);
            return redirect()->route('doctor.dashboard')->with('success', 'Patient found.');
        }

        Session::forget('patient');
        Log::info('Patient not found for mother_id: ' . $request->motherId);
        return back()->withErrors(['motherId' => 'Patient not found.']);
    }

    // Save Physical Exam Findings
    public function savePhysicalExamFindings(Request $request)
    {
        $request->validate([
            'abdominal_exam' => 'nullable|string',
            'urinalysis' => 'nullable|string',
            'blood_test' => 'nullable|string',
            'blood_pressure' => 'nullable|string',
            'ultrasound' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
        ]);

        $patient = Session::get('patient');
        if (!$patient) {
            return redirect()->route('doctor.dashboard')->withErrors(['error' => 'No patient selected']);
        }

        PhysicalExamFinding::updateOrCreate(
            ['mother_id' => $patient->mother_id],
            [
                'abdominal_exam' => $request->abdominal_exam,
                'urinalysis' => $request->urinalysis,
                'blood_test' => $request->blood_test,
                'blood_pressure' => $request->blood_pressure,
                'ultrasound' => $request->ultrasound,
                'treatment_plan' => $request->treatment_plan,
            ]
        );

        return redirect()->route('doctor.dashboard')->with('success', 'Physical exam findings saved successfully');
    }

    // Save Return Date
    public function saveReturnDate(Request $request)
    {
        $request->validate([
            'return_date' => 'required|date',
        ]);
    
        $patient = Session::get('patient');
        if (!$patient) {
            return redirect()->route('doctor.dashboard')->withErrors(['error' => 'No patient selected']);
        }
    
        $appointment = Appointment::where('mother_id', $patient->mother_id)->latest()->first();
        if ($appointment) {
            $appointment->update(['return_date' => $request->return_date]);
        } else {
            Appointment::create([
                'mother_id' => $patient->mother_id,
                'nurse_id' => null,
                'date' => null,
                'time' => null,
                'reason' => 'Doctor scheduled return',
                'return_date' => $request->return_date,
            ]);
        }
    
        return redirect()->route('doctor.dashboard')->with('success', 'Return date submitted successfully');
    }

    // Save Treatment Plan (Updated with debugging)
    public function saveTreatmentPlan(Request $request)
    {
        // Log request data for debugging
        Log::info('saveTreatmentPlan Request Data:', $request->all());

        try {
            $request->validate([
                'mother_id' => 'required|exists:expectant_mothers,mother_id',
                'treatment_plan' => 'required|string'
            ]);

            $examFinding = PhysicalExamFinding::updateOrCreate(
                ['mother_id' => $request->mother_id],
                ['treatment_plan' => $request->treatment_plan]
            );

            return response()->json(['success' => 'Treatment plan saved successfully']);
        } catch (\Exception $e) {
            Log::error('Error in saveTreatmentPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save treatment plan: ' . $e->getMessage()], 500);
        }
    }
    public function fetchEmergencyMessages() {
        $messages = EmergencyMessage::all(); // Fetch all emergency messages
        return response()->json($messages);
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::guard('doctor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('doctor.login')->with('success', 'Logged out successfully.');
    }
}