<?php
session_start();
include 'db_connect.php';

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otpCode = $_POST['otp_code'];
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
            // Optionally, redirect to login page after a few seconds
        } else {
            $errorMessage = "Invalid OTP code. Please try again.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $errorMessage = "An error occurred. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Strive High School</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
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
    
    <footer class="footer">
        <div class="container text-center">
            <span>© <?php echo date("Y"); ?> Strive High School. All rights reserved.</span>
        </div>
    </footer>

    <!-- Bootstrap JS for hamburger functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>