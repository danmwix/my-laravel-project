<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mother Dashboard - Maternity Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .video-background { position: fixed; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1; }
        .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: -1; }
        .header { color: white; padding: 2rem 0; text-align: center; background: rgba(0, 0, 0, 0.5); }
        footer { background-color: #343a40; color: white; padding: 1rem 0; text-align: center; }
        .notification-item { margin-bottom: 10px; padding: 10px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <video autoplay muted loop class="video-background">
        <source src="{{ asset('videos/mother.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="overlay"></div>

    <header class="header">
        <div class="container">
            <h1>Welcome, {{ $mother->full_name ?? 'Guest' }}!</h1>
            <p>Your partner in comprehensive maternity services</p>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Maternity Care</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#pregnancyDueDateModal">Pregnancy Due Date</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#pregnancyTrackingModal">Pregnancy Tracking</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#contactModal">Emergency Contact</a></li>
                    <li class="nav-item d-flex align-items-center">
                        <a href="#" class="nav-link me-2" data-bs-toggle="modal" data-bs-target="#notificationModal">
                            <i class="bi bi-bell"></i>
                        </a>
                        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Pregnancy Due Date Modal -->
    <div class="modal fade" id="pregnancyDueDateModal" tabindex="-1" aria-labelledby="pregnancyDueDateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pregnancyDueDateModalLabel">Pregnancy Due Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="dueDateLMP" class="form-label">Last Menstrual Period (LMP):</label>
                    <input type="date" class="form-control" id="dueDateLMP">
                    <button type="button" class="btn btn-primary mt-3" onclick="calculateDueDate()">Calculate Due Date</button>
                    <div id="dueDateResult" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pregnancy Tracking Modal -->
    <div class="modal fade" id="pregnancyTrackingModal" tabindex="-1" aria-labelledby="pregnancyTrackingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pregnancyTrackingModalLabel">Pregnancy Tracking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="lastPeriodDate" class="form-label">Last Period Date:</label>
                    <input type="date" class="form-control" id="lastPeriodDate">
                    <label for="cycleLength" class="form-label">Cycle Length (Days):</label>
                    <input type="number" class="form-control" id="cycleLength" placeholder="Enter Cycle Length (e.g., 28)">
                    <label for="periodDuration" class="form-label">Duration of Period (Days):</label>
                    <input type="number" class="form-control" id="periodDuration" placeholder="Enter Period Duration (e.g., 5)">
                    <button type="button" class="btn btn-primary mt-3" onclick="calculatePregnancy()">Calculate Pregnancy Week</button>
                    <div id="pregnancyResult" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">Emergency Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="emergencyForm">
                        <div class="mb-3">
                            <label for="patientName" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="patientName" required>
                        </div>
                        <div class="mb-3">
                            <label for="patientPhone" class="form-label">Your Phone Number</label>
                            <input type="tel" class="form-control" id="patientPhone" required>
                        </div>
                        <div class="mb-3">
                            <label for="emergencyMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="emergencyMessage" rows="3" placeholder="Describe your emergency..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Send Emergency Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="notificationBody">
                    <!-- Populated dynamically by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <footer role="contentinfo">
        <div class="container">
            <p>© 2024 Maternity Care Management. All Rights Reserved.</p>
        </div>
    </footer>

<!-- Previous content remains unchanged until the script section -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function calculateDueDate() {
        const lmpDate = new Date(document.getElementById('dueDateLMP').value);
        if (isNaN(lmpDate.getTime())) {
            document.getElementById('dueDateResult').innerText = 'Please enter a valid date.';
            return;
        }
        const dueDate = new Date(lmpDate);
        dueDate.setFullYear(dueDate.getFullYear() + 1);
        dueDate.setMonth(dueDate.getMonth() - 3);
        dueDate.setDate(dueDate.getDate() + 7);
        document.getElementById('dueDateResult').innerText = `Estimated Due Date: ${dueDate.toLocaleDateString()}`;
    }

    function calculatePregnancy() {
        const lastPeriodDate = new Date(document.getElementById('lastPeriodDate').value);
        if (isNaN(lastPeriodDate.getTime())) {
            document.getElementById('pregnancyResult').innerText = 'Please fill in the Last Period Date correctly.';
            return;
        }
        const today = new Date();
        const diffTime = Math.abs(today - lastPeriodDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const pregnancyWeeks = Math.floor(diffDays / 7);
        let trimester;
        if (pregnancyWeeks < 13) {
            trimester = "First Trimester";
        } else if (pregnancyWeeks < 27) {
            trimester = "Second Trimester";
        } else {
            trimester = "Third Trimester";
        }
        document.getElementById('pregnancyResult').innerText = `You're ${pregnancyWeeks} weeks pregnant. Trimester: ${trimester}.`;
    }

    document.getElementById('emergencyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const patientName = document.getElementById('patientName').value;
        const patientPhone = document.getElementById('patientPhone').value;
        const emergencyMessage = document.getElementById('emergencyMessage').value;

        if (!patientName || !patientPhone) {
            alert('Please fill in all required fields.');
            return;
        }

        fetch('/mother/send-emergency-message', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: patientName,
                phone: patientPhone,
                message: emergencyMessage
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            alert('Emergency message sent successfully!');
            this.reset();
            bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
        })
        .catch(error => {
            console.error('Error sending emergency message:', error);
            alert('Error sending emergency message.');
        });
    });

    document.getElementById('notificationModal').addEventListener('shown.bs.modal', function () {
        fetch('/mother/notifications', {
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
            notificationBody.innerHTML = '';
            let delay = 0;

            // Display care messages
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach((message) => {
                    setTimeout(() => {
                        const div = document.createElement('div');
                        div.className = 'notification-item';
                        div.innerHTML = `<p>${message}</p>`;
                        notificationBody.appendChild(div);
                    }, delay);
                    delay += 2000;
                });
            }

            // Display appointments
            if (data.appointments && data.appointments.length > 0) {
                data.appointments.forEach((appointment) => {
                    setTimeout(() => {
                        const div = document.createElement('div');
                        div.className = 'notification-item';
                        div.innerHTML = `
                            <p><strong>Scheduled Appointment</strong></p>
                            <p><strong>Date:</strong> ${appointment.date}</p>
                            <p><strong>Time:</strong> ${appointment.time}</p>
                            <p><strong>Reason:</strong> ${appointment.reason}</p>
                        `;
                        notificationBody.appendChild(div);
                    }, delay);
                    delay += 2000;
                });
            } else {
                setTimeout(() => {
                    const div = document.createElement('div');
                    div.className = 'notification-item';
                    div.innerHTML = `<p>No scheduled appointments yet.</p>`;
                    notificationBody.appendChild(div);
                }, delay);
                delay += 2000;
            }

            // Display return date
            if (data.return_date) {
                setTimeout(() => {
                    const div = document.createElement('div');
                    div.className = 'notification-item';
                    div.innerHTML = `<p>You are scheduled to return on ${data.return_date}</p>`;
                    notificationBody.appendChild(div);
                }, delay);
                delay += 2000;
            } else {
                setTimeout(() => {
                    const div = document.createElement('div');
                    div.className = 'notification-item';
                    div.innerHTML = `<p>No scheduled return date yet.</p>`;
                    notificationBody.appendChild(div);
                }, delay);
                delay += 2000;
            }

            // Display prescriptions immediately after return date
            if (data.prescriptions && data.prescriptions.length > 0) {
                data.prescriptions.forEach((prescription) => {
                    setTimeout(() => {
                        const div = document.createElement('div');
                        div.className = 'notification-item';
                        div.innerHTML = `
                            <p><strong>Prescription</strong></p>
                            <p>You were prescribed <strong>${prescription.medication_name}</strong></p>
                            <p><strong>Dosage Instructions:</strong> ${prescription.dosage_instructions}</p>
                        `;
                        notificationBody.appendChild(div);
                    }, delay);
                    delay += 2000;
                });
            } else {
                setTimeout(() => {
                    const div = document.createElement('div');
                    div.className = 'notification-item';
                    div.innerHTML = `<p>No prescriptions available yet.</p>`;
                    notificationBody.appendChild(div);
                }, delay);
                delay += 2000;
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            document.getElementById('notificationBody').innerHTML = '<p>Error loading notifications.</p>';
        });
    });
</script>
</body>
</html>