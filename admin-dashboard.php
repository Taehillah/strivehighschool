<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Calculate weekly summary dynamically
try {
    // Define start of the current week
    $weekStart = date("Y-m-d", strtotime("last Monday"));

    // Fetch total registrations
    $stmt = $pdo->prepare("SELECT COUNT(*) AS Total_Registrations FROM Users");
    $stmt->execute();
    $totalRegistrations = $stmt->fetchColumn();

    // Fetch new enrollments for the current week
    $stmt = $pdo->prepare("SELECT COUNT(*) AS New_Enrollments FROM Users WHERE enrollment_status = 'Accepted' AND enrollment_date >= :weekStart");
    $stmt->bindParam(':weekStart', $weekStart);
    $stmt->execute();
    $newEnrollments = $stmt->fetchColumn();

    // Fetch waiting list additions for the current week
    $stmt = $pdo->prepare("SELECT COUNT(*) AS Waiting_List_Additions FROM Users WHERE enrollment_status = 'Waiting List' AND enrollment_date >= :weekStart");
    $stmt->bindParam(':weekStart', $weekStart);
    $stmt->execute();
    $waitingListAdditions = $stmt->fetchColumn();

    // Check bus serviceability status
    $stmt = $pdo->prepare("SELECT COUNT(*) AS Operational_Buses FROM Buses WHERE service_status = 'Operational'");
    $stmt->execute();
    $operationalBuses = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) AS Total_Buses FROM Buses");
    $stmt->execute();
    $totalBuses = $stmt->fetchColumn();

    $busServiceability = ($operationalBuses == $totalBuses) ? 'All Functional' : 'Some Buses Down';
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Weekly Activities</title>
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
                    <li class="nav-item"><a class="nav-link active" href="admin-dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Section -->
    <section class="main-section">
        <div class="container">
            <h2>Weekly Activity Summary</h2>
            
            <!-- Weekly Summary Display -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Week Start</th>
                        <th>Total Registrations</th>
                        <th>New Enrollments (This Week)</th>
                        <th>Waiting List Additions (This Week)</th>
                        <th>Bus Serviceability</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $weekStart; ?></td>
                        <td><?php echo $totalRegistrations; ?></td>
                        <td><?php echo $newEnrollments; ?></td>
                        <td><?php echo $waitingListAdditions; ?></td>
                        <td><?php echo $busServiceability; ?></td>
                    </tr>
                </tbody>
            </table>
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
