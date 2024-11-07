<?php
require_once 'sql/db_connect.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Strive High School</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Strive High School</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Section -->
    <section class="main-section">
        <div class="container">
            <h2>Register for Bus Service</h2>
            <p class="text-muted">Fill out the form below to register your learner for bus transportation.</p>
            <form id="registrationForm">
                <!-- Role Selection -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" required onchange="toggleFields()">
                        <option value="">Select Role</option>
                        <option value="Learner">Learner</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                        <option value="Guardian">Guardian</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <!-- Full Name Field -->
                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name (Learner / Admin)</label>
                    <input type="text" class="form-control" id="fullName" required>
                </div>

                <!-- Grade Field -->
                <div class="mb-3">
                    <label for="grade" class="form-label">Grade</label>
                    <input type="number" class="form-control" id="grade" required>
                </div>

                <!-- Route Field -->
                <div class="mb-3">
                    <label for="route" class="form-label">Preferred Route</label>
                    <select class="form-select" id="route" required>
                        <option value="">Select Route</option>
                        <option value="Route A">Route A</option>
                        <option value="Route B">Route B</option>
                        <option value="Route C">Route C</option>
                    </select>
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email (Parent / Guardian / Admin)</label>
                    <input type="email" class="form-control" id="email" required>
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" required>
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <span>© 2024 Strive High School. All Rights Reserved.</span>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
