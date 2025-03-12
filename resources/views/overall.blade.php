<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Role Selection - Maternity Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('{{ asset('images/mother.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }
        .header {
            color: white;
            padding: 2rem 0;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 1rem 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="header" role="banner">
        <div class="container">
            <h1>Welcome to Maternity Care Management</h1>
            <p>Your partner in comprehensive maternity services</p>
        </div>
    </header>

    <div class="form-container" role="main">
        <h3 class="text-center">Who Are You?</h3>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('role.select') }}" aria-label="Role Selection Form">
            @csrf
        
            <div class="mb-3">
                <label for="role" class="form-label">Select your role:</label>
                <select class="form-select" id="role" name="role" required aria-required="true">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choose an option</option>
                    <option value="expectant" {{ old('role') === 'expectant' ? 'selected' : '' }}>Expectant Mother</option>
                    <option value="healthcare" {{ old('role') === 'healthcare' ? 'selected' : '' }}>Healthcare Provider</option>
                </select>
            </div>
            <div class="mb-3" id="providerOptions" style="display: none;">
                <label for="providerType" class="form-label">Are you a:</label>
                <select class="form-select" id="providerType" name="providerType" aria-required="true">
                    <option value="" disabled {{ old('providerType') ? '' : 'selected' }}>Choose an option</option>
                    <option value="doctor" {{ old('providerType') === 'doctor' ? 'selected' : '' }}>Doctor</option>
                    <option value="nurse" {{ old('providerType') === 'nurse' ? 'selected' : '' }}>Nurse</option>
                    <option value="pharmacist" {{ old('providerType') === 'pharmacist' ? 'selected' : '' }}>Pharmacist</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Continue</button>
        </form>
    </div>

    <footer role="contentinfo">
        <div class="container">
            <p>© 2024 Maternity Care Management. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roleSelect = document.getElementById('role');
            const providerOptions = document.getElementById('providerOptions');

            if (!roleSelect || !providerOptions) {
                console.warn('Role selection or provider options elements not found.');
                return;
            }

            roleSelect.addEventListener('change', () => {
                providerOptions.style.display = roleSelect.value === 'healthcare' ? 'block' : 'none';
            });

            // Set initial state based on old input
            if (roleSelect.value === 'healthcare') {
                providerOptions.style.display = 'block';
            }
        });
    </script>
</body>
</html>