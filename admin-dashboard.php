<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Fetch weekly summary data
try {
    $stmt = $pdo->prepare("SELECT Week_Start, Total_Registrations, New_Enrollments, Waiting_List_Additions, Bus_Serviceability FROM WeeklySummary ORDER BY Week_Start DESC");
    $stmt->execute();
    $weeklySummaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            
            <!-- Weekly Summary Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Week Start</th>
                        <th>Total Registrations</th>
                        <th>New Enrollments</th>
                        <th>Waiting List Additions</th>
                        <th>Bus Serviceability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($weeklySummaries as $summary): ?>
                        <tr>
                            <td><?php echo date("Y-m-d", strtotime($summary['Week_Start'])); ?></td>
                            <td><?php echo $summary['Total_Registrations']; ?></td>
                            <td><?php echo $summary['New_Enrollments']; ?></td>
                            <td><?php echo $summary['Waiting_List_Additions']; ?></td>
                            <td><?php echo $summary['Bus_Serviceability']; ?></td>
                        </tr>
                    <?php endforeach; ?>
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
