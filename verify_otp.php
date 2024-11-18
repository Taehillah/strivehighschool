<?php
session_start();
include 'db_connect.php';

$title = "Strive High School - Verify OTP";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $otpCode = htmlspecialchars(trim($_POST['otp']));
    $email = $_SESSION['email'] ?? '';

    if (empty($email)) {
        $errorMessage = "Session expired. Please register again.";
    } elseif (empty($otpCode)) {
        $errorMessage = "Please enter the OTP.";
    } else {
        try {
            // Verify OTP
            $stmt = $pdo->prepare("SELECT otp_code FROM Users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $storedOtp = $stmt->fetchColumn();

            if ($storedOtp && $storedOtp === $otpCode) {
                // Update verification status
                $stmt = $pdo->prepare("UPDATE Users SET verified = 1 WHERE email = :email");
                $stmt->execute([':email' => $email]);

                $successMessage = "OTP verified successfully! You can now log in.";
                unset($_SESSION['email']);
            } else {
                $errorMessage = "Invalid OTP. Please try again.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errorMessage = "An error occurred during OTP verification. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your custom styles -->
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- OTP Verification Form -->
    <section class="main-section">
        <div class="container">
            <h2>Verify OTP</h2>

            <!-- Error/Success Messages -->
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php elseif ($errorMessage): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <?php if (!$successMessage): ?>
                <form action="verify_otp.php" method="post">
                    <!-- OTP Input -->
                    <div class="mb-3">
                        <label for="otp" class="form-label">Enter OTP</label>
                        <input type="text" class="form-control" id="otp" name="otp" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
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

    <!-- Bootstrap JS for functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
