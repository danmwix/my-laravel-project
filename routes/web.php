<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MotherController;
use App\Http\Controllers\NurseAuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PharmacistController;

// Role Selection Routes
Route::get('/', [RoleController::class, 'showRoleSelection'])->name('role.selection');
Route::post('/role/select', [RoleController::class, 'selectRole'])->name('role.select');

// Expectant Mother (patient) Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registration', [AuthController::class, 'showRegister'])->name('registration');
Route::post('/registration', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth:expectant_mother')->group(function () {
    Route::get('/mother/dashboard', [MotherController::class, 'dashboard'])->name('mother.dashboard');
    Route::get('/mother/notifications', [MotherController::class, 'notifications'])->name('mother.notifications');
    
    // New route for sending emergency messages
    Route::post('/mother/send-emergency-message', [MotherController::class, 'sendEmergencyMessage'])->name('mother.send.emergency.message');
});

// Nurse Routes
Route::prefix('nurse')->group(function () {
    Route::get('/login', [NurseAuthController::class, 'showLoginForm'])->name('nurse.showlogin');
    Route::post('/login', [NurseAuthController::class, 'login'])->name('nurse.login');
    Route::get('/registration', [NurseAuthController::class, 'showRegisterForm'])->name('nurse.registration');
    Route::post('/registration', [NurseAuthController::class, 'register'])->name('nurse.register');
    Route::post('/logout', [NurseAuthController::class, 'logout'])->name('nurse.logout');

    Route::middleware('auth:nurse')->group(function () {
        Route::get('/dashboard', [NurseAuthController::class, 'dashboard'])->name('nurse.dashboard');
        Route::post('/patient', [NurseAuthController::class, 'searchPatient'])->name('nurse.patient.search');
        Route::post('/appointment', [NurseAuthController::class, 'scheduleAppointment'])->name('nurse.appointment.schedule');
        Route::post('/records', [NurseAuthController::class, 'saveMaternityRecords'])->name('nurse.save.records');
    });
});

// Doctor Routes
Route::prefix('doctor')->group(function () {
    Route::get('/login', [DoctorController::class, 'showLoginForm'])->name('doctor.login');
    Route::post('/login', [DoctorController::class, 'login'])->name('doctor.login.submit');
    Route::get('/register', [DoctorController::class, 'showRegistrationForm'])->name('doctor.registration');
    Route::post('/register', [DoctorController::class, 'register'])->name('doctor.register');

    Route::middleware('auth:doctor')->group(function () {
        Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('doctor.dashboard');
        Route::post('/logout', [DoctorController::class, 'logout'])->name('doctor.logout');
        Route::post('/patient/search', [DoctorController::class, 'searchPatient'])->name('doctor.patient.search');
        Route::post('/save-exam-findings', [DoctorController::class, 'savePhysicalExamFindings'])->name('doctor.save.exam.findings');
        Route::post('/save-return-date', [DoctorController::class, 'saveReturnDate'])->name('doctor.save.return.date');
        Route::post('/save-treatment-plan', [DoctorController::class, 'saveTreatmentPlan'])->name('doctor.save.treatment.plan');

        // New route for fetching emergency messages
        Route::get('/emergency-messages', [DoctorController::class, 'fetchEmergencyMessages'])->name('doctor.fetch.emergency.messages');
    });
});

// Pharmacist Routes
Route::prefix('pharmacist')->group(function () {
    Route::get('/login', [PharmacistController::class, 'showLoginForm'])->name('pharmacist.login');
    Route::post('/login', [PharmacistController::class, 'login'])->name('pharmacist.login.submit');
    Route::get('/register', [PharmacistController::class, 'showRegistrationForm'])->name('pharmacist.registration');
    Route::post('/register', [PharmacistController::class, 'register'])->name('pharmacist.register');

    Route::middleware('auth:pharmacist')->group(function () {
        Route::get('/dashboard', [PharmacistController::class, 'dashboard'])->name('pharmacist.dashboard');
        Route::post('/patient/search', [PharmacistController::class, 'searchPatient'])->name('pharmacist.patient.search');
        Route::get('/treatment-plan/{motherId}', [PharmacistController::class, 'getTreatmentPlan'])->name('pharmacist.treatment.plan');
        Route::post('/dispense-medication', [PharmacistController::class, 'dispenseMedication'])->name('pharmacist.dispense.medication');
        Route::post('/logout', [PharmacistController::class, 'logout'])->name('pharmacist.logout');
    });
});