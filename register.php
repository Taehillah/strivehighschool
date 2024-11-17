<?php
session_start();
include 'db_connect.php';

$title = "Strive High School - Register";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $grade = $_POST['grade'];
    $route = $_POST['route'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Assign a bus to the user based on their route
        $stmt = $pdo->prepare("
            SELECT Bus_Registration 
            FROM Buses 
            WHERE Bus_Route = :route AND service_status = 'Operational' 
            AND (SELECT COUNT(*) FROM Users WHERE assigned_bus = Bus_Registration) < capacity 
            LIMIT 1
        ");
        $stmt->execute([':route' => $route]);
        $assignedBus = $stmt->fetchColumn();

        if (!$assignedBus) {
            $assignedBus = null; // No available bus, user goes to the waiting list
        }

        // Insert the user into the database
        $stmt = $pdo->prepare("
            INSERT INTO Users (role, email, password, route, full_name, grade, assigned_bus) 
            VALUES (:role, :email, :password, :route, :full_name, :grade, :assigned_bus)
        ");
        $stmt->execute([
            ':role' => $role,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':route' => $route,
            ':full_name' => $fullName,
            ':grade' => $grade,
            ':assigned_bus' => $assignedBus
        ]);

        $successMessage = "Registration successful! Your assigned bus is: " . ($assignedBus ?: "Waiting List");
    } catch (PDOException $e) {
        $errorMessage = "Error: " . $e->getMessage();
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
                    <label for="fullName" class="form-label">Full Name(of Learner or Admin)</label>
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

    <!-- Bootstrap JS for hamburger functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
