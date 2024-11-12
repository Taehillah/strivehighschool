<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedIn']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_query'])) {
    $searchQuery = '%' . htmlspecialchars(trim($_POST['search_query'])) . '%';

    // Fetch matching learners based on search
    try {
        $stmt = $pdo->prepare("SELECT User_ID, full_name, grade, route, enrollment_status FROM Users WHERE full_name LIKE :searchQuery");
        $stmt->bindParam(':searchQuery', $searchQuery);
        $stmt->execute();
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle route update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_route'])) {
    $newRoute = $_POST['new_route'];
    $userId = $_POST['user_id'];

    try {
        $stmt = $pdo->prepare("UPDATE Users SET route = :newRoute WHERE User_ID = :userId");
        $stmt->bindParam(':newRoute', $newRoute);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        echo "<script>alert('Route updated successfully');</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle deletion of learner
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_learner'])) {
    $userId = $_POST['user_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM Users WHERE User_ID = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        echo "<script>alert('Learner deleted successfully');</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Learners</title>
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
            <h2>Admin Dashboard</h2>

            <!-- Search Form -->
            <form method="POST" class="d-flex mb-4">
                <input class="form-control me-2" type="search" name="search_query" placeholder="Search learner by name" aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>

            <!-- Search Results -->
            <?php if (!empty($searchResults)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Grade</th>
                            <th>Current Route</th>
                            <th>Enrollment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($searchResults as $learner): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($learner['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($learner['grade']); ?></td>
                                <td><?php echo htmlspecialchars($learner['route']); ?></td>
                                <td><?php echo htmlspecialchars($learner['enrollment_status']); ?></td>
                                <td>
                                    <!-- Update Route Form -->
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?php echo $learner['User_ID']; ?>">
                                        <select name="new_route" class="form-select form-select-sm mb-1">
                                            <option value="Rooihuiskraal">Rooihuiskraal</option>
                                            <option value="Wierdapark">Wierdapark</option>
                                            <option value="Centurion">Centurion</option>
                                        </select>
                                        <button type="submit" name="update_route" class="btn btn-sm btn-primary">Update Route</button>
                                    </form>

                                    <!-- Delete Learner Form -->
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?php echo $learner['User_ID']; ?>">
                                        <button type="submit" name="delete_learner" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <p class="text-warning">No results found for your search.</p>
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
