<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Search functionality
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_query'])) {
    $searchQuery = '%' . htmlspecialchars(trim($_POST['search_query'])) . '%';

    try {
        $stmt = $pdo->prepare("SELECT User_ID, full_name, grade, route, enrollment_status FROM Users WHERE full_name LIKE :searchQuery");
        $stmt->bindParam(':searchQuery', $searchQuery);
        $stmt->execute();
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle updates and deletions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_route'])) {
        $newRoute = $_POST['new_route'];
        $userId = $_POST['user_id'];
        $stmt = $pdo->prepare("UPDATE Users SET route = :newRoute WHERE User_ID = :userId");
        $stmt->bindParam(':newRoute', $newRoute);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    } elseif (isset($_POST['delete_learner'])) {
        $userId = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM Users WHERE User_ID = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    }
}

// Weekly summary calculation
$weekStart = date("Y-m-d", strtotime("last Monday"));
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users");
    $stmt->execute();
    $totalRegistrations = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE enrollment_status = 'Accepted' AND enrollment_date >= :weekStart");
    $stmt->bindParam(':weekStart', $weekStart);
    $stmt->execute();
    $newEnrollments = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE enrollment_status = 'Waiting List' AND enrollment_date >= :weekStart");
    $stmt->bindParam(':weekStart', $weekStart);
    $stmt->execute();
    $waitingListAdditions = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Buses WHERE service_status = 'Operational'");
    $stmt->execute();
    $operationalBuses = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Buses");
    $stmt->execute();
    $totalBuses = $stmt->fetchColumn();

    $busServiceability = ($operationalBuses == $totalBuses) ? 'All Functional' : 'Some Buses Down';

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Fetch route capacity data
try {
    $stmt = $pdo->prepare("SELECT route, COUNT(*) AS passenger_count FROM Users WHERE role = 'Learner' GROUP BY route");
    $stmt->execute();
    $routeData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $routeCapacity = [
        'Rooihuiskraal' => 50, // example capacity, replace with actual
        'Wierdapark' => 50,    // example capacity, replace with actual
        'Centurion' => 50      // example capacity, replace with actual
    ];

    $routePassengers = [];
    foreach ($routeData as $route) {
        $routePassengers[$route['route']] = $route['passenger_count'];
    }

    // Ensure all routes are present in case they don't have any passengers yet
    foreach ($routeCapacity as $route => $capacity) {
        if (!isset($routePassengers[$route])) {
            $routePassengers[$route] = 0;
        }
    }

    // Fetch registration data for the graph
    $stmt = $pdo->prepare("SELECT DATE(created_at) AS reg_date, COUNT(*) AS registrations FROM Users WHERE role = 'Learner' GROUP BY reg_date ORDER BY reg_date");
    $stmt->execute();
    $registrationData = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>

        <!-- Gauge for Route Capacity -->
        <h3>Route Capacity</h3>
        <div class="row">
            <?php foreach ($routeCapacity as $route => $capacity): ?>
                <div class="col-md-4 text-center">
                    <h4><?php echo $route; ?></h4>
                    <canvas id="gauge-<?php echo $route; ?>" width="200" height="200"></canvas>
                    <p><?php echo $routePassengers[$route] . " / " . $capacity; ?> Passengers</p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Graph for Registrations -->
        <h3>Registration Trends</h3>
        <canvas id="registrationChart" width="600" height="400"></canvas>
    </div>

    <script>
        // Gauge Charts
        <?php foreach ($routeCapacity as $route => $capacity): ?>
            var ctxGauge<?php echo $route; ?> = document.getElementById('gauge-<?php echo $route; ?>').getContext('2d');
            new Chart(ctxGauge<?php echo $route; ?>, {
                type: 'doughnut',
                data: {
                    labels: ['Occupied', 'Available'],
                    datasets: [{
                        data: [<?php echo $routePassengers[$route]; ?>, <?php echo $capacity - $routePassengers[$route]; ?>],
                        backgroundColor: ['#FF6384', '#36A2EB'],
                        borderWidth: 1
                    }]
                },
                options: {
                    rotation: 1 * Math.PI,
                    circumference: 1 * Math.PI,
                    cutout: '70%',
                    plugins: {
                        tooltip: { enabled: false }
                    }
                }
            });
        <?php endforeach; ?>

        // Registration Line Chart
        var ctx = document.getElementById('registrationChart').getContext('2d');
        var registrationChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    <?php foreach ($registrationData as $data) {
                        echo '"' . $data['reg_date'] . '", ';
                    } ?>
                ],
                datasets: [{
                    label: 'Registrations',
                    data: [
                        <?php foreach ($registrationData as $data) {
                            echo $data['registrations'] . ', ';
                        } ?>
                    ],
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { title: { display: true, text: 'Number of Registrations' } }
                }
            }
        });
    </script>
</body>
</html>
