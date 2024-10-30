<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet">
</head>
<body>
    <h1>Bus Registration</h1>
    <form action="register.php" method="post">
        <h2>Learner Information</h2>
        <label for="learnerID">Learner ID:</label>
        <input type="text" id="learnerID" name="learnerID" required><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="grade">Grade:</label>
        <input type="text" id="grade" name="grade" required><br>

        <label for="guardianName">Guardian Name:</label>
        <input type="text" id="guardianName" name="guardianName" required><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required><br>

        <h2>Bus Information</h2>
        <label for="regNum">Bus Registration Number:</label>
        <input type="text" id="regNum" name="regNum" required><br>

        <label for="routeID">Route ID:</label>
        <input type="text" id="routeID" name="routeID" required><br>

        <label for="capacity">Capacity:</label>
        <input type="text" id="capacity" name="capacity" required><br>

        <label for="driverName">Driver Name:</label>
        <input type="text" id="driverName" name="driverName" required><br>

        <h2>Pick Up Point Information</h2>
        <label for="pickUpPointID">Pick Up Point ID:</label>
        <input type="text" id="pickUpPointID" name="pickUpPointID" required><br>

        <label for="pickUpPointName">Pick Up Point Name:</label>
        <input type="text" id="pickUpPointName" name="pickUpPointName" required><br>

        <label for="pickUpPointNum">Pick Up Point Number:</label>
        <input type="text" id="pickUpPointNum" name="pickUpPointNum" required><br>

        <label for="pickUpPointTime">Pick Up Point Time:</label>
        <input type="text" id="pickUpPointTime" name="pickUpPointTime" required><br>

        <label for="numOfLearners">Number of Learners:</label>
        <input type="text" id="numOfLearners" name="numOfLearners" required><br>

        <h2>Drop Off Point Information</h2>
        <label for="dropOffPointID">Drop Off Point ID:</label>
        <input type="text" id="dropOffPointID" name="dropOffPointID" required><br>

        <label for="dropOffPointName">Drop Off Point Name:</label>
        <input type="text" id="dropOffPointName" name="dropOffPointName" required><br>

        <label for="dropOffPointNum">Drop Off Point Number:</label>
        <input type="text" id="dropOffPointNum" name="dropOffPointNum" required><br>

        <label for="dropOffPointTime">Drop Off Point Time:</label>
        <input type="text" id="dropOffPointTime" name="dropOffPointTime" required><br>

        <label for="dropOffNumOfLearners">Number of Learners:</label>
        <input type="text" id="dropOffNumOfLearners" name="dropOffNumOfLearners" required><br>

        <button type="submit">Register</button>
    </form>

    <form class="row g-3">
  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Email</label>
    <input type="email" class="form-control" id="inputEmail4">
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Password</label>
    <input type="password" class="form-control" id="inputPassword4">
  </div>
  <div class="col-12">
    <label for="inputAddress" class="form-label">Address</label>
    <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
  </div>
  <div class="col-12">
    <label for="inputAddress2" class="form-label">Address 2</label>
    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
  </div>
  <div class="col-md-6">
    <label for="inputCity" class="form-label">City</label>
    <input type="text" class="form-control" id="inputCity">
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">State</label>
    <select id="inputState" class="form-select">
      <option selected>Choose...</option>
      <option>...</option>
    </select>
  </div>
  <div class="col-md-2">
    <label for="inputZip" class="form-label">Zip</label>
    <input type="text" class="form-control" id="inputZip">
  </div>
  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck">
      <label class="form-check-label" for="gridCheck">
        Check me out
      </label>
    </div>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Sign in</button>
  </div>
</form>
</body>
</html>



<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_transport_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO Learner (LearnerID, Name, Grade, GuardianName, Status, Location) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $learnerID, $name, $grade, $guardianName, $status, $location);

// Set parameters and execute
$learnerID = $_POST['learnerID'];
$name = $_POST['name'];
$grade = $_POST['grade'];
$guardianName = $_POST['guardianName'];
$status = $_POST['status'];
$location = $_POST['location'];
$stmt->execute();

$stmt = $conn->prepare("INSERT INTO Bus (RegNum, RouteID, Capacity, DriverName) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $regNum, $routeID, $capacity, $driverName);

$regNum = $_POST['regNum'];
$routeID = $_POST['routeID'];
$capacity = $_POST['capacity'];
$driverName = $_POST['driverName'];
$stmt->execute();

$stmt = $conn->prepare("INSERT INTO PickUpPoint (PickUpPointID, PickUpPointName, PickUpPointNum, PickUpPointTime, NumOfLearners) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $pickUpPointID, $pickUpPointName, $pickUpPointNum, $pickUpPointTime, $numOfLearners);

$pickUpPointID = $_POST['pickUpPointID'];
$pickUpPointName = $_POST['pickUpPointName'];
$pickUpPointNum = $_POST['pickUpPointNum'];
$pickUpPointTime = $_POST['pickUpPointTime'];
$numOfLearners = $_POST['numOfLearners'];
$stmt->execute();

$stmt = $conn->prepare("INSERT INTO DropOffPoint (DropOffPointID, DropOffPointName, DropOffPointNum, DropOffPointTime, NumOfLearners) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $dropOffPointID, $dropOffPointName, $dropOffPointNum, $dropOffPointTime, $dropOffNumOfLearners);

$dropOffPointID = $_POST['dropOffPointID'];
$dropOffPointName = $_POST['dropOffPointName'];
$dropOffPointNum = $_POST['dropOffPointNum'];
$dropOffPointTime = $_POST['dropOffPointTime'];
$dropOffNumOfLearners = $_POST['dropOffNumOfLearners'];
$stmt->execute();

echo "New records created successfully";

$stmt->close();
$conn->close();
?>
