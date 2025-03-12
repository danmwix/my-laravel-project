<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Registration - Maternity Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: url('{{ asset('images/mother.jpg') }}') no-repeat center center fixed; background-size: cover; margin: 0; }
        .header { color: white; padding: 2rem 0; text-align: center; background: rgba(0, 0, 0, 0.5); }
        .form-container { max-width: 500px; margin: 50px auto; background-color: rgba(255, 255, 255, 0.8); padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        footer { background-color: #343a40; color: white; padding: 1rem 0; text-align: center; }
    </style>
</head>
<body>
    <header class="header" role="banner">
        <div class="container">
            <h1>Welcome to Maternity Care Management</h1>
            <p>Register as a pharmacist to manage maternity care</p>
        </div>
    </header>

    <div class="form-container" role="main">
        <h3 class="text-center">Pharmacist Registration</h3>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pharmacist.register') }}" aria-label="Pharmacist Registration Form">
            @csrf
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
            </div>
            <div class="mb-3">
                <label for="registration_number" class="form-label">Registration Number</label>
                <input type="text" class="form-control" id="registration_number" name="registration_number" value="{{ old('registration_number') }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('pharmacist.login') }}">Already have an account? Login</a>
        </div>
    </div>

    <footer role="contentinfo">
        <div class="container">
            <p>© 2025 Maternity Care Management. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>