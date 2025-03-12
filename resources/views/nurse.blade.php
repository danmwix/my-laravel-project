<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maternity Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .header {
            color: white;
            padding: 2rem 0;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 1rem 0;
            text-align: center;
        }
        .container {
            margin-top: 20px;
        }
        .nurse-form {
            margin-top: 50px;
        }
        .section {
            display: none;
        }
        #patientDetails {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Maternity Care</a>
        </div>
    </nav>

    <header class="header">
        <div class="container">
            <h1>Welcome to Maternity Care Management</h1>
            <p>Your partner in comprehensive maternity services</p>
        </div>
    </header>

    <div class="container nurse-form">
        @if (auth('nurse')->check())
            <h3>Welcome, {{ auth('nurse')->user()->full_name }}</h3>
            <div class="mb-4">
                <label for="registrationNumber" class="form-label">Enter Patient Registration Number</label>
                <form method="POST" action="{{ route('nurse.patient.search') }}" aria-label="Patient Search Form">
                    @csrf
                    <input type="text" id="registrationNumber" name="registrationNumber" class="form-control" placeholder="e.g., 1234" required>
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('patient'))
                <div id="patientDetails" class="section">
                    <h3>Patient Details</h3>
                    <p><strong>Full Name:</strong> {{ session('patient')->full_name }}</p>
                    <p><strong>Email:</strong> {{ session('patient')->email }}</p>
                    <p><strong>Date of Birth:</strong> {{ session('patient')->dob }}</p>
                    <p><strong>Age:</strong> {{ session('patient')->age }}</p>
                    <p><strong>Place of Residence:</strong> {{ session('patient')->place_of_residence }}</p>
                    <p><strong>Phone Number:</strong> {{ session('patient')->phone }}</p>
                    <button class="btn btn-secondary mt-2" onclick="showSection('patientDetails')">Back to Patient Details</button>
                </div>
            @endif
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId) {
            const sections = ['patientDetails'];
            sections.forEach(id => {
                document.getElementById(id).style.display = id === sectionId ? 'block' : 'none';
            });
            sessionStorage.setItem('currentSection', sectionId);
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('patientDetails') && sessionStorage.getItem('currentSection') === 'patientDetails') {
                showSection('patientDetails');
            } else if (document.getElementById('patientDetails')) {
                showSection('patientDetails');
            }
        });
    </script>
</body>
</html>
