// main.js

// Simulate login with session role storage
function login(email, password) {
    // For demo purposes, we assume certain emails for role identification
    if (email === "admin@example.com") {
        localStorage.setItem("role", "admin");
        localStorage.setItem("loggedIn", "true");
        window.location.href = "dashboard/admin-dashboard.html";
    } else if (email === "user@example.com") {
        localStorage.setItem("role", "user");
        localStorage.setItem("loggedIn", "true");
        window.location.href = "dashboard/user-dashboard.html";
    } else {
        alert("Invalid credentials. Try 'admin@example.com' or 'user@example.com'.");
    }
}

// Log out and clear session storage
function logout() {
    localStorage.removeItem("role");
    localStorage.removeItem("loggedIn");
    window.location.href = "index.html";
}

// Check login status and redirect to appropriate dashboard
function checkLogin() {
    const loggedIn = localStorage.getItem("loggedIn");
    const role = localStorage.getItem("role");
    if (loggedIn && role === "admin") {
        window.location.href = "dashboard/admin-dashboard.html";
    } else if (loggedIn && role === "user") {
        window.location.href = "dashboard/user-dashboard.html";
    }
}

// Update navigation links based on login status
function updateNavbar() {
    const loggedIn = localStorage.getItem("loggedIn");
    const navbar = document.getElementById("navbarNav");

    if (loggedIn) {
        // Hide login/register links and show dashboard and logout
        navbar.innerHTML = `
            <li class="nav-item">
                <a class="nav-link" href="dashboard/user-dashboard.html">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="logout()">Logout</a>
            </li>
        `;
    } else {
        // Show login/register links
        navbar.innerHTML = `
            <li class="nav-item">
                <a class="nav-link" href="login.html">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.html">Register</a>
            </li>
        `;
    }
}

// Initialize navbar update on page load
document.addEventListener("DOMContentLoaded", updateNavbar);
