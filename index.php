<?php
session_start(); // Start session to manage login state
include 'db_connect.php';

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['email'])) {
        $errorMessage = "Session expired. Please register again.";
    } else {
        $otpCode = filter_input(INPUT_POST, 'otp_code', FILTER_SANITIZE_STRING);
        $email = $_SESSION['email']; // Get the email from session

        try {
            // Retrieve the OTP code from the database
            $stmt = $pdo->prepare("SELECT otp_code FROM Users WHERE email = :email AND verified = 0");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $user['otp_code'] == $otpCode) {
                // Update the user's verified status
                $stmt = $pdo->prepare("UPDATE Users SET verified = 1, otp_code = NULL WHERE email = :email");
                $stmt->execute([':email' => $email]);
                $successMessage = "Your email has been verified! You can now log in.";
                // Redirect to login page after a few seconds
                header("refresh:5;url=login.php");
            } else {
                $errorMessage = "Invalid OTP code. Please try again.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errorMessage = "An error occurred. Please try again later.";
        }
    }
}

// Set dynamic title
$title = "Strive High School - Home";

// Example: Set session variable for demonstration (remove in production)
$_SESSION['loggedIn'] = $_SESSION['loggedIn'] ?? false;
$_SESSION['role'] = $_SESSION['role'] ?? 'Guest'; // Role can be 'Admin', 'User', etc.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Make sure the page fills the entire screen */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* Navbar styling */
        .navbar {
            background-color: #f8f9fa;
        }

        /* Welcome Section Styling */
        .welcome-section {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('assets/images/hero.jpg'); /* Background cover image */
            background-size: cover;
            background-position: center;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
            padding: 60px 15px;
            text-align: center;
        }

        /* Typography styling */
        h1 {
            font-size: 3em;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        p.lead {
            font-size: 1.3em;
            max-width: 600px;
            margin: 0 auto 30px;
            color: #ddd;
        }

        /* Button styling */
        .btn-primary {
            padding: 10px 20px;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 8px;
        }

        /* Footer styling */
        .footer {
            background-color: #333;
            color: #f8f9fa;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Strive High School</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- OTP Verification Form -->
    <section class="main-section">
        <div class="container">
            <h2>OTP Verification</h2>
            <!-- Display Messages -->
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php elseif ($errorMessage): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <?php if (!$successMessage): ?>
                <form action="verify_otp.php" method="post">
                    <div class="mb-3">
                        <label for="otp_code" class="form-label">Enter OTP Code</label>
                        <input type="text" class="form-control" id="otp_code" name="otp_code" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Verify</button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <span>© <?php echo date("Y"); ?> Strive High School. All rights reserved.</span>
        </div>
    </footer>

    <!-- Optional JavaScript for Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
