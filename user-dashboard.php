<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];
$role = $_SESSION['role'];
$name_of_the_learner = '';
$status = '';
$waitingListPosition = '';
$busRegistration = '';
$busRoute = '';
$busTimings = '';

try {
    // Retrieve user details and enrollment status
    $stmt = $pdo->prepare("SELECT full_name, role, enrollment_status, waiting_list_position, assigned_bus FROM Users WHERE User_ID = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $name_of_the_learner = $user['full_name'];
        $status = $user['enrollment_status'];
        $waitingListPosition = $user['waiting_list_position'];
        $assignedBusRegistration = $user['assigned_bus'];
    }

    // Retrieve bus information if the user is assigned a bus
    if ($status === 'Accepted' && $assignedBusRegistration) {
        $stmt = $pdo->prepare("SELECT Bus_Registration, Bus_Route, Bus_Timings 
                               FROM Buses 
                               WHERE Bus_Registration = :busRegistration");
        $stmt->bindParam(':busRegistration', $assignedBusRegistration, PDO::PARAM_STR);
        $stmt->execute();
        $bus = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bus) {
            $busRegistration = $bus['Bus_Registration'];
            $busRoute = $bus['Bus_Route'];
            $busTimings = $bus['Bus_Timings'];
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar with Hamburger Menu -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Strive High School</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Section -->
    <section class="main-section">
        <div class="container">
            <h2>Welcome, <?php echo htmlspecialchars($name_of_the_learner) . "'s " . htmlspecialchars($role); ?></h2>

            <!-- Enrollment Status -->
            <div class="card mb-4">
                <div class="card-header">Enrollment Status</div>
                <div class="card-body">
                    <p>Status: <strong><?php echo htmlspecialchars($status); ?></strong></p>
                    <?php if ($status === 'Waiting List'): ?>
                        <p>Waiting List Position: <strong><?php echo htmlspecialchars($waitingListPosition); ?></strong></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bus Information -->
            <?php if ($status === 'Accepted' && $busRegistration): ?>
                <div class="card">
                    <div class="card-header">Bus Information</div>
                    <div class="card-body">
                        <p>Assigned Bus: <strong><?php echo htmlspecialchars($busRegistration); ?></strong></p>
                        <p>Route: <strong><?php echo htmlspecialchars($busRoute); ?></strong></p>
                        <p>Timings: <strong><?php echo htmlspecialchars($busTimings); ?></strong></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header">Bus Information</div>
                    <div class="card-body">
                        <p>No bus assigned yet.</p>
                    </div>
                </div>
            <?php endif; ?>
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
