<?php
session_start();
include 'db_connect.php';

$title = "Strive High School - Register";
$errorMessage = "";
$successMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $grade = $_POST['grade'];
    $route = $_POST['route'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check if the combination of full name and grade already exists in the database
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE full_name = :fullName AND grade = :grade");
    $stmt->bindParam(':fullName', $fullName, PDO::PARAM_STR);
    $stmt->bindParam(':grade', $grade, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $errorMessage = "A user with this full name and grade is already registered.";
    } elseif (empty($role) || empty($fullName) || empty($grade) || empty($route) || empty($email) || empty($password)) {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } else {
        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the Users table
            $stmt = $pdo->prepare("INSERT INTO Users (role, email, password, route, full_name, grade) 
                                   VALUES (:role, :email, :password, :route, :full_name, :grade)");
            $stmt->execute([
                ':role' => $role,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':route' => $route,
                ':full_name' => $fullName,
                ':grade' => $grade
            ]);

            $successMessage = "Registration successful! You can now log in.";
        } catch (PDOException $e) {
            $errorMessage = "Error: " . $e->getMessage();
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
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link active" href="register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Section -->
    <section class="main-section">
        <div class="container">
            <h2>Register for Bus Service</h2>

            <!-- Display Success or Error Message -->
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php elseif ($errorMessage): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <form action="register.php" method="post">
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
                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" name="fullName" required>
                </div>
                <div class="mb-3">
                    <label for="grade" class="form-label">Grade</label>
                    <input type="number" class="form-control" id="grade" name="grade" required>
                </div>
                <div class="mb-3">
                    <label for="route" class="form-label">Preferred Route</label>
                    <select class="form-select" id="route" name="route" required>
                        <option value="">Select Route</option>
                        <option value="Rooihuiskraal">Rooihuiskraal</option>
                        <option value="Wierdapark">Wierdapark</option>
                        <option value="Centurion">Centurion</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
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

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
