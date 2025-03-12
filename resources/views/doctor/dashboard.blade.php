<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Maternity Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; }
        .navbar-brand { font-weight: bold; }
        .header { color: white; padding: 2rem 0; text-align: center; background: rgba(0, 0, 0, 0.5); }
        footer { background-color: #343a40; color: white; padding: 1rem 0; text-align: center; }
        .container { margin-top: 20px; }
        .doctor-form { margin-top: 50px; }
        .section { display: none; }
        #patientDetails { margin-top: 20px; }
        .notification-item { margin-bottom: 10px; padding: 10px; border-bottom: 1px solid #ddd; } /* Added for styling */
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
                <div class="modal-body" id="notificationBody"> <!-- Updated to add id="notificationBody" -->
                    <p>Doctor notifications will appear here.</p>
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

    <div class="container doctor-form">
        @if ($doctor)
            <h3>Welcome, Dr. {{ $doctor->full_name }}</h3>
            <div class="mb-4">
                <label for="motherId" class="form-label">Enter Patient Mother ID</label>
                <form method="POST" action="{{ route('doctor.patient.search') }}" aria-label="Patient Search Form">
                    @csrf
                    <input type="text" id="motherId" name="motherId" class="form-control" placeholder="e.g., 1" required>
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($patient)
                <div id="patientDetails">
                    <h3>Patient Details</h3>
                    <p><strong>Full Name:</strong> {{ $patient->full_name }}</p>
                    <p><strong>Email:</strong> {{ $patient->email }}</p>
                    <p><strong>Date of Birth:</strong> {{ $patient->dob }}</p>
                    <p><strong>Age:</strong> {{ $patient->age }}</p>
                    <p><strong>Place of Residence:</strong> {{ $patient->place_of_residence }}</p>
                    <p><strong>Phone Number:</strong> {{ $patient->phone }}</p>
                    <button class="btn btn-primary mt-2" onclick="toggleSection('physicalExamFindings')">Physical Exam Findings</button>
                </div>

                <div id="physicalExamFindings" class="section" style="display: none;">
                    <h4>Physical Exam Findings</h4>
                    <form method="POST" action="{{ route('doctor.save.exam.findings') }}" id="examFindingsForm">
                        @csrf
                        <input type="hidden" name="mother_id" value="{{ $patient->mother_id }}">
                        <div class="mb-3">
                            <label for="abdominal_exam" class="form-label">Abdominal Exam</label>
                            <textarea class="form-control" id="abdominal_exam" name="abdominal_exam" rows="3" placeholder="Enter abdominal examination findings">{{ $examFinding->abdominal_exam ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="urinalysis" class="form-label">Urinalysis</label>
                            <textarea class="form-control" id="urinalysis" name="urinalysis" rows="3" placeholder="Enter urinalysis results">{{ $examFinding->urinalysis ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="blood_test" class="form-label">Blood Test</label>
                            <textarea class="form-control" id="blood_test" name="blood_test" rows="3" placeholder="Enter blood test results">{{ $examFinding->blood_test ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="blood_pressure" class="form-label">Blood Pressure</label>
                            <textarea class="form-control" id="blood_pressure" name="blood_pressure" rows="3" placeholder="Enter blood pressure findings">{{ $examFinding->blood_pressure ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ultrasound" class="form-label">Ultrasound Findings</label>
                            <textarea class="form-control" id="ultrasound" name="ultrasound" rows="3" placeholder="Enter ultrasound results">{{ $examFinding->ultrasound ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success mb-3">Save Patient Records</button>

                        <div class="mb-3">
                            <label for="treatment_plan" class="form-label">Treatment Plan</label>
                            <textarea class="form-control" id="treatment_plan" name="treatment_plan" rows="3" placeholder="Enter treatment plan">{{ $examFinding->treatment_plan ?? '' }}</textarea>
                        </div>
                        <button type="button" class="btn btn-primary mb-3" onclick="saveTreatmentPlan('{{ $patient->mother_id }}')">Save Treatment Plan</button>

                        <div class="mb-3">
                            <label for="return_date" class="form-label">Return Date</label>
                            <input type="date" class="form-control" id="return_date" name="return_date" value="{{ $appointment->return_date ?? '' }}">
                        </div>
                        <button type="submit" formaction="{{ route('doctor.save.return.date') }}" class="btn btn-success mb-3">Submit Date</button>

                        <button type="button" class="btn btn-secondary mt-2" onclick="toggleSection('patientDetails')">Back to Patient Details</button>
                    </form>
                </div>
            @endif

            <form action="{{ route('doctor.logout') }}" method="POST" class="mt-3" onsubmit="clearSessionStorage()">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
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

        function toggleSection(sectionId) {
            const sections = ['patientDetails', 'physicalExamFindings'];
            sections.forEach(id => {
                const section = document.getElementById(id);
                if (section) {
                    section.style.display = id === sectionId ? 'block' : 'none';
                }
            });
        }

        function saveTreatmentPlan(motherId) {
            const treatmentPlan = document.getElementById('treatment_plan').value;

            if (!treatmentPlan) {
                alert('Please enter a treatment plan before saving.');
                return;
            }

            fetch('/doctor/save-treatment-plan', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    mother_id: motherId,
                    treatment_plan: treatmentPlan
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                alert('Treatment plan saved successfully!');
            })
            .catch(error => {
                console.error('Error saving treatment plan:', error);
                alert('Error saving treatment plan.');
            });
        }

        // Added new code below without altering existing functions
        document.getElementById('notificationModal').addEventListener('shown.bs.modal', function () {
            fetch('/doctor/emergency-messages', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                const notificationBody = document.getElementById('notificationBody');
                notificationBody.innerHTML = ''; // Clear previous notifications
                data.forEach(message => {
                    const div = document.createElement('div');
                    div.className = 'notification-item';
                    div.innerHTML = `<p><strong>${message.name}</strong>: ${message.message} (Phone: ${message.phone})</p>`;
                    notificationBody.appendChild(div);
                });
            })
            .catch(error => {
                console.error('Error fetching emergency messages:', error);
                document.getElementById('notificationBody').innerHTML = '<p>Error loading notifications.</p>';
            });
        });
    </script>
</body>
</html>