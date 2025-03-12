<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\ExpectantMother;
use App\Models\EmergencyMessage;
use Illuminate\Support\Facades\Auth;

class MotherController extends Controller
{
    public function dashboard()
    {
        $mother = Auth::guard('expectant_mother')->user();
        $appointments = Appointment::where('mother_id', $mother->mother_id)->get();
        return view('mother', compact('mother', 'appointments'));
    }

    public function notifications(Request $request)
    {
        $mother = Auth::guard('expectant_mother')->user();
        if (!$mother) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $appointments = Appointment::where('mother_id', $mother->mother_id)
                                   ->select('date', 'time', 'reason', 'return_date')
                                   ->get()
                                   ->toArray();

        $prescriptions = Prescription::where('mother_id', $mother->mother_id)
                                     ->select('medication_name', 'dosage_instructions')
                                     ->get()
                                     ->toArray();

        $careMessages = [
            "CARE DURING PREGNANCY",
            "Eat one extra meal every day during pregnancy",
            "Eat plenty of fruits and vegetables",
            "Drink plenty of water at least 8 glasses per day (2 litres)",
            "Take iron and folic acid tablets",
            "Avoid heavy work, rest more",
            "Sleep under a long-lasting insecticidal net (LLIN)",
            "Go for ANC visit as soon as possible, and at least 4 times during the pregnancy"
        ];

        $latestAppointment = Appointment::where('mother_id', $mother->mother_id)
                                        ->whereNotNull('return_date')
                                        ->latest()
                                        ->first();
        $returnDate = $latestAppointment ? $latestAppointment->return_date : null;

        return response()->json([
            'messages' => $careMessages,
            'appointments' => $appointments,
            'prescriptions' => $prescriptions,
            'return_date' => $returnDate
        ]);
    }
    public function sendEmergencyMessage(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);
    
        // Store the emergency message in the database (assuming you have an EmergencyMessage model)
        EmergencyMessage::create([
            'mother_id' => Auth::guard('expectant_mother')->id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);
    
        return response()->json(['success' => 'Emergency message sent successfully!']);
    }
}