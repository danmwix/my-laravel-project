<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Dashboard - Maternity Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; }
        .navbar-brand { font-weight: bold; }
        .header { color: white; padding: 2rem 0; text-align: center; background: rgba(0, 0, 0, 0.5); }
        footer { background-color: #343a40; color: white; padding: 1rem 0; text-align: center; }
        .container { margin-top: 20px; }
        .nurse-form { margin-top: 50px; }
        .section { display: none; }
        #patientDetails { margin-top: 20px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Maternity Care</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center">
                        <a href="#" class="nav-link me-2" data-bs-toggle="modal" data-bs-target="#notificationModal">
                            <i class="bi bi-bell"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Nurse notifications will appear here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <header class="header">
        <div class="container">
            <h1>Welcome to Maternity Care Management</h1>
            <p>Your partner in comprehensive maternity services</p>
        </div>
    </header>

    <div class="container nurse-form">
        @if ($nurse)
            <h3>Welcome Nurse {{ $nurse->full_name }}</h3>
            <div class="mb-4">
                <label for="registrationNumber" class="form-label">Enter Patient Registration Number</label>
                <form method="POST" action="{{ route('nurse.patient.search') }}" aria-label="Patient Search Form">
                    @csrf
                    <input type="text" id="registrationNumber" name="registrationNumber" class="form-control" placeholder="e.g., 1234" required>
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif

            @if ($patient)
            <div id="patientDetails" class="section">
                <h3>Patient Details</h3>
                <p><strong>Full Name:</strong> {{ $patient->full_name }}</p>
                <p><strong>Email:</strong> {{ $patient->email }}</p>
                <p><strong>Date of Birth:</strong> {{ $patient->dob }}</p>
                <p><strong>Age:</strong> {{ $patient->age }}</p>
                <p><strong>Place of Residence:</strong> {{ $patient->place_of_residence }}</p>
                <p><strong>Phone Number:</strong> {{ $patient->phone }}</p>
                <button class="btn btn-primary mt-2" onclick="showSection('maternityRecords')">View Maternity Records</button>
                <button class="btn btn-primary mt-2" onclick="showSection('appointmentSection')">Schedule Appointments</button>
            </div>

            <div id="maternityRecords" class="section" style="display:none;">
                <h3>Maternity Records</h3>
                <form method="POST" action="{{ route('nurse.save.records') }}">
                    @csrf
                    <label for="weight" class="form-label">Weight (kg):</label>
                    <input type="number" id="weight" name="weight" class="form-control" value="{{ $record->weight ?? '' }}" placeholder="Enter weight">

                    <label for="bloodPressure" class="form-label">Blood Pressure (mmHg):</label>
                    <input type="text" id="bloodPressure" name="bloodPressure" class="form-control" value="{{ $record->blood_pressure ?? '' }}" placeholder="Enter blood pressure">

                    <label for="temperature" class="form-label">Temperature (°C):</label>
                    <input type="number" id="temperature" name="temperature" class="form-control" value="{{ $record->temperature ?? '' }}" placeholder="Enter temperature">

                    <label for="height" class="form-label">Height (cm):</label>
                    <input type="number" id="height" name="height" class="form-control" value="{{ $record->height ?? '' }}" placeholder="Enter height">

                    <label for="respiratoryRate" class="form-label">Respiratory Rate (breaths per minute):</label>
                    <input type="number" id="respiratoryRate" name="respiratoryRate" class="form-control" value="{{ $record->respiratory_rate ?? '' }}" placeholder="Enter respiratory rate">

                    <button type="submit" class="btn btn-primary mt-2">Save Records</button>
                    <button type="button" class="btn btn-secondary mt-2" onclick="showSection('patientDetails')">Back to Patient Details</button>
                </form>
            </div>

            <div id="appointmentSection" class="section" style="display:none;">
                <h3>Schedule an Appointment</h3>
                <form method="POST" action="{{ route('nurse.appointment.schedule') }}">
                    @csrf
                    <label for="appointmentDate" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="appointmentDate" name="appointmentDate" required>
                    <label for="appointmentTime" class="form-label">Time:</label>
                    <input type="time" class="form-control" id="appointmentTime" name="appointmentTime" required>
                    <label for="appointmentReason" class="form-label">Reason:</label>
                    <input type="text" class="form-control" id="appointmentReason" name="appointmentReason" required>
                    <button type="submit" class="btn btn-primary mt-2">Schedule Appointment</button>
                    <button type="button" class="btn btn-secondary mt-2" onclick="showSection('patientDetails')">Back to Patient Details</button>
                </form>
            </div>
            @endif

            <form action="{{ route('nurse.logout') }}" method="POST" class="mt-3" onsubmit="clearSessionStorage()">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        @else
            <h3>Nurse Dashboard</h3>
            <div class="mb-4">
                <label for="nurseRegistration" class="form-label">Enter Nurse Registration Number</label>
                <form method="POST" action="{{ route('nurse.login') }}" aria-label="Nurse Login Form">
                    @csrf
                    <input type="text" id="nurseRegistration" name="registration_number" class="form-control" placeholder="e.g., N1234" required>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <button type="submit" class="btn btn-primary mt-2">Login</button>
                </form>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
    </div>

    <footer>
        <div class="container">
            <p>© 2025 Maternity Care Management. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function clearSessionStorage() {
            sessionStorage.clear();
        }
        function showSection(sectionId) {
            const sections = ['patientDetails', 'maternityRecords', 'appointmentSection'];
            sections.forEach(id => {
                const section = document.getElementById(id);
                if (section) {
                    section.style.display = id === sectionId ? 'block' : 'none';
                }
            });
            sessionStorage.setItem('currentSection', sectionId);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const patientDetails = document.getElementById('patientDetails');
            if (patientDetails) {
                const savedSection = sessionStorage.getItem('currentSection');
                if (savedSection && ['patientDetails', 'maternityRecords', 'appointmentSection'].includes(savedSection)) {
                    showSection(savedSection);
                } else {
                    showSection('patientDetails');
                }
            }
        });
    </script>
</body>
</html>