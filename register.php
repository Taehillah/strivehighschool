<?php
session_start();
include 'db_connect.php';



$title = "Strive High School - Register";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $role = $_POST['role'];
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $grade = $_POST['grade'];
    $route = $_POST['route'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email address.";
    } elseif (strlen($password) < 8) {
        $errorMessage = "Password must be at least 8 characters long.";
    } else {
        // Generate OTP
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        try {
            // Check if the email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $emailExists = $stmt->fetchColumn();

            if ($emailExists) {
                $errorMessage = "An account with this email already exists.";
            } else {
                // Assign a bus to the user based on their route
                $stmt = $pdo->prepare("
                    SELECT TOP 1 Bus_Registration 
                    FROM Buses 
                    WHERE Bus_Route = :route AND service_status = 'Operational' 
                    AND (SELECT COUNT(*) FROM Users WHERE assigned_bus = Bus_Registration) < capacity
                ");
                $stmt->execute([':route' => $route]);
                $assignedBus = $stmt->fetchColumn();

                if (!$assignedBus) {
                    $assignedBus = null; // No available bus, user goes to the waiting list
                }

                // Insert the user into the database
                $stmt = $pdo->prepare("
                    INSERT INTO Users (role, email, password, route, full_name, grade, assigned_bus, otp_code, verified) 
                    VALUES (:role, :email, :password, :route, :full_name, :grade, :assigned_bus, :otp_code, 0)
                ");
                $stmt->bindParam(':role', $role);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':route', $route);
                $stmt->bindParam(':full_name', $fullName);
                $stmt->bindParam(':grade', $grade);
                $stmt->bindParam(':assigned_bus', $assignedBus, PDO::PARAM_NULL);
                $stmt->bindParam(':otp_code', $otpCode);
                $stmt->execute();

                // Send OTP via email using Mailgun
                $mgClient = Mailgun::create('ed0824fd2d10b66044f20b9cecde9936-79295dd0-0f3c0ee3'); // Replace with your Mailgun Private API Key
                $domain = "sandbox6fcd1c67273b404c848b97e3926b4dc4.mailgun.org"; // Replace with your verified domain

                // Prepare email parameters
                $params = [
                    'from'    => 'Strive High School <no-reply@sandbox6fcd1c67273b404c848b97e3926b4dc4.mailgun.org>',
                    'to'      => $email,
                    'subject' => 'Your OTP Verification Code',
                    'text'    => "Dear $fullName,\n\nYour OTP verification code is: $otpCode\n\nPlease enter this code to verify your email address.\n\nThank you!",
                ];

                try {
                    // Send the email
                    $mgClient->messages()->send($domain, $params);

                    // Store email in session and redirect to verify_otp.php
                    $_SESSION['email'] = $email;
                    $_SESSION['successMessage'] = "Registration successful! An OTP has been sent to your email address.";
                    header("Location: verify_otp.php");
                    exit();
                } catch (Exception $e) {
                    // Log the error message
                    error_log('Mailgun Error: ' . $e->getMessage());
                    $errorMessage = "Failed to send OTP email. Please try again later.";
                }
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errorMessage = "An error occurred during registration. Please try again later.";
        }
    }
}
?>

<!-- Your existing HTML content remains the same -->

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your existing head content -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your custom styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        // JavaScript function to enable/disable fields based on role selection
        function toggleFieldsBasedOnRole() {
            const role = document.getElementById('role').value;
            const isAdmin = role === 'Admin';

            // Only disable the grade and route fields for Admin role
            document.getElementById('grade').disabled = isAdmin;
            document.getElementById('route').disabled = isAdmin;
        }

        // Initialize field state on page load
        window.addEventListener('DOMContentLoaded', (event) => {
            toggleFieldsBasedOnRole(); // Initialize based on current selection
            document.getElementById('role').addEventListener('change', toggleFieldsBasedOnRole);
        });
    </script>
</head>
<body>
    <!-- Navbar, similar to other pages -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Strive High School</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link active" href="register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Registration Form -->
    <section class="main-section">
        <div class="container">
            <h2>Register for Bus Service</h2>

            <!-- Error/Success Messages -->
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php elseif ($errorMessage): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <form action="register.php" method="post">
                <!-- Role Dropdown -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="Learner">Learner</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                        <option value="Guardian">Guardian</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name (of Learner or Admin)</label>
                    <input type="text" class="form-control" id="fullName" name="fullName" required>
                </div>

                <!-- Grade -->
                <div class="mb-3">
                    <label for="grade" class="form-label">Grade</label>
                    <input type="number" class="form-control" id="grade" name="grade" required>
                </div>

                <!-- Route -->
                <div class="mb-3">
                    <label for="route" class="form-label">Preferred Route</label>
                    <select class="form-select" id="route" name="route" required>
                        <option value="">Select Route</option>
                        <option value="Rooihuiskraal">Rooihuiskraal</option>
                        <option value="Wierdapark">Wierdapark</option>
                        <option value="Centurion">Centurion</option>
                    </select>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password (minimum 8 characters)</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <span>© <?php echo date("Y"); ?> Strive High School. All rights reserved.</span>
        </div>
    </footer>

    <!-- Bootstrap JS for functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>