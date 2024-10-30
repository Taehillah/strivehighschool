<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strive High Secondary School</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('images/bk2.jpg');
            margin: 0;
            padding-top: 60px;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #434242;
            padding: 30px 10px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .navbar .logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        #logo {
            height: 80px;
            width: 300px;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .navbar ul li {
            position: relative;
        }
        .navbar ul li a {
            color: white;
            text-decoration: none;
            padding: 20px 40px;
            display: block;
        }
        .navbar ul li a:hover,
        .navbar ul li a:focus {
            background-color: #555;
        }
        .navbar ul li ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #333;
            min-width: 200px;
            z-index: 1000;
        }
        .navbar ul li ul li a {
            padding: 10px;
        }
        .navbar ul li:hover > ul,
        .navbar ul li:focus-within > ul {
            display: block;
        }
        .content {
            padding: 20px;
        }
        .center {
            max-width: 500px;
            margin: auto;
            margin-top: 70px;
        }
        form {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(15px);
            width: 100%;
            max-width: 500px;
            margin: auto;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            transition: 0.3s;
        }
        h2 {
            color: #fff;
            font-size: 30px;
            display: flex;
            justify-content: center;
            padding: 10px 0;
            margin-bottom: 15px;
        }
        .labels {
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
            display: block;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="password"],
        select {
            width: 100%;
            height: 50px;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 30px;
            color: #fff;
            font-size: 20px;
            padding: 0 0 0 45px;
            background: rgba(255, 255, 255, 0.1);
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo"><img id="logo" src="images/logoFIN.png" alt=""></div>
        <ul>
            <li><a href="#">Home</a>
            <ul>
                    <li><a href="#">Welcome Message</a></li>
                    <li><a href="Login.php">Login</a></li>
                    <li><a href="LearnerReg.php">Registration(Learner)</a></li>
                    <li><a href="AdminReg.php">Registration(Admin)</a></li>
                </ul>
            </li>
            <li><a href="#">Dashboard</a>
                <ul>
                    <li><a href="#">Bus Routes</a></li>
                    <li><a href="#">Register Bus Route</a></li>
                    <li><a href="#">View Existing Registrations</a></li>
                </ul>
            </li>
            <li><a href="#">Admin Panel</a>
                <ul>
                    <li><a href="#">Manage Bus Routes and Schedule</a></li>
                    <li><a href="#">View All Registrations</a></li>
                    <li><a href="#">Generate Reports</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="center"><h2 style="color: white;">BUS REGISTRATION System <br>(Guardian / Learner)</h2></div>
    <div class="content">
        <!-- Registration page for learners or guardians -->
        <form action="register.php" method="post" onsubmit="return validateForm()">
            <h2>Learner Information</h2>
            <div class="form-group">
                <label for="learnerName" class="labels">Name (Learner):</label>
                <input type="text" id="learnerName" name="learnerName" required>
            </div>
            <div class="form-group">
                <label for="guardianName" class="labels">Name (Guardian):</label>
                <input type="text" id="guardianName" name="guardianName" required>
            </div>
            <div class="form-group">
                <label for="email" class="labels">E-Mail (Guardian):</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password" class="labels">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
          
            <div class="form-group">
                <label for="cell" class="labels">Contacts (Guardian):</label>
                <input type="number" id="cell" name="contact" required>
            </div>
            <div class="form-group">
                <label for="role" class="labels">Role:</label>
                <select id="role" name="role" required>
                    <option selected disabled>Choose Role</option>
                    <option value="Guardian">Guardian or Parent</option>
                    <option value="Learner">Learner</option>
                    <option value="Administrator">Administrator</option>
                </select>
            </div>
            <div class="form-group">
                <label for="class" class="labels">Class:</label>
                <select id="class" name="class" required>
                    <option selected disabled>Choose Class</option>
                    <option value="10A">10(A)</option>
                    <option value="10B">10(B)</option>
                    <option value="10C">10(C)</option>
                    <option value="11A">11(A)</option>
                    <option value="11B">11(B)</option>
                    <option value="11C">11(C)</option>
                    <option value="12A">12(A)</option>
                    <option value="12B">12(B)</option>
                    <option value="12C">12(C)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status" class="labels">Status:</label>
                <select id="status" name="status" required>
                    <option selected disabled>Choose Status</option>
                    <option value="New Learner">New Learner</option>
                    <option value="Current Learner">Current Learner</option>
                </select>
            </div>
            <div class="form-group">
                <label for="location" class="labels">Location:</label>
                <select id="location" name="location" required>
                    <option selected disabled>Choose Location</option>
                    <option value="Rooihuiskraal">Rooihuiskraal</option>
                    <option value="Wierdapark">Wierdapark</option>
                    <option value="Centurion">Centurion</option>
                </select>
            </div>
            <button type="submit">Register</button>
        </form>

        <script>
            function validateForm() {
                var cell = document.getElementById("cell").value;
                if (isNaN(cell)) {
                    alert("Please enter a valid contact number.");
                    return false;
                }
                return true;
            }
        </script>
    </div>
</body>
</html>
