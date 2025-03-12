<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Added CSRF token -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Dashboard - Maternity Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; }
        .navbar-brand { font-weight: bold; }
        .header { color: white; padding: 2rem 0; text-align: center; background: rgba(0, 0, 0, 0.5); }
        footer { background-color: #343a40; color: white; padding: 1rem 0; text-align: center; }
        .container { margin-top: 20px; }
        .pharmacist-form { margin-top: 50px; }
        #patientDetails { margin-top: 20px; }
        #treatmentPlan { display: none; margin-top: 10px; padding: 10px; border: 1px solid #ddd; background: #f9f9f9; }
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

    <div class="container pharmacist-form">
        @if ($pharmacist)
            <h3>Welcome, Pharmacist {{ $pharmacist->full_name }}</h3>
            <div class="mb-4">
                <label for="motherId" class="form-label">Enter Patient Mother ID</label>
                <form method="POST" action="{{ route('pharmacist.patient.search') }}" aria-label="Patient Search Form">
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

            @if (session('patient'))
                <div id="patientDetails">
                    <h3>Patient Details</h3>
                    <p><strong>Full Name:</strong> {{ session('patient')->full_name }}</p>
                    <p><strong>Email:</strong> {{ session('patient')->email }}</p>
                    <p><strong>Date of Birth:</strong> {{ session('patient')->dob }}</p>
                    <p><strong>Age:</strong> {{ session('patient')->age }}</p>
                    <p><strong>Place of Residence:</strong> {{ session('patient')->place_of_residence }}</p>
                    <p><strong>Phone Number:</strong> {{ session('patient')->phone }}</p>
                    <!-- Prescription Section -->
                    <div class="mt-3">
                        <button type="button" class="btn btn-info mb-3" onclick="viewTreatmentPlan('{{ session('patient')->mother_id }}')">View Treatment Plan</button>
                        <div id="treatmentPlan"></div>
                        <div class="mb-3">
                            <label for="medication_name" class="form-label">Medication Name</label>
                            <input type="text" class="form-control" id="medication_name" name="medication_name" placeholder="Enter medication name">
                        </div>
                        <div class="mb-3">
                            <label for="dosage" class="form-label">Dosage Instructions</label>
                            <textarea class="form-control" id="dosage" rows="3" placeholder="Enter dosage instructions"></textarea>
                        </div>
                        <button type="button" class="btn btn-success" onclick="savePrescription()">Save Prescription</button>
                    </div>
                </div>
            @endif

            <form action="{{ route('pharmacist.logout') }}" method="POST" class="mt-3" onsubmit="clearSessionStorage()">
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

        function savePrescription() {
    const motherId = '{{ session('patient')->mother_id ?? '' }}';
    const medicationName = document.getElementById('medication_name').value;
    const dosageInstructions = document.getElementById('dosage').value;

    if (!motherId || !medicationName || !dosageInstructions) {
        alert('Please fill in all prescription fields and ensure a patient is selected.');
        return;
    }

    fetch('/pharmacist/dispense-medication', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            mother_id: motherId,
            medication_name: medicationName,
            dosage_instructions: dosageInstructions
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        alert('Prescription saved successfully!');
        document.getElementById('medication_name').value = ''; // Clear fields
        document.getElementById('dosage').value = '';
    })
    .catch(error => {
        console.error('Error saving prescription:', error);
        alert('Error saving prescription.');
    });
}

        function viewTreatmentPlan(motherId) {
            fetch(`/pharmacist/treatment-plan/${motherId}`, {
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
                const treatmentPlanDiv = document.getElementById('treatmentPlan');
                if (data.treatment_plan) {
                    treatmentPlanDiv.innerHTML = `<p><strong>Treatment Plan:</strong> ${data.treatment_plan}</p>`;
                    treatmentPlanDiv.style.display = 'block';
                } else {
                    treatmentPlanDiv.innerHTML = '<p>No treatment plan available.</p>';
                    treatmentPlanDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching treatment plan:', error);
                document.getElementById('treatmentPlan').innerHTML = '<p>Error loading treatment plan.</p>';
                document.getElementById('treatmentPlan').style.display = 'block';
            });
        }
    </script>
</body>
</html>