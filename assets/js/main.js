// main.js

function getUserStatus(userId) {
    fetch(`get_user_status.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('status').innerText = data.status;
            document.getElementById('waitingListPosition').innerText = data.waiting_list_position || "N/A";
        })
        .catch(error => console.error('Error fetching user status:', error));
}


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



// Initialize navbar update on page load
document.addEventListener("DOMContentLoaded", updateNavbar);

document.addEventListener("DOMContentLoaded", updateNavbar);

// Ensure toggleFields() and other functions only affect elements by ID
function toggleFieldsBasedOnRole(role) {
    const isAdmin = role === 'Admin';

    // Only disable specified fields, ensuring no impact on CSS
    const fieldsToToggle = ['grade', 'route'];
    fieldsToToggle.forEach(id => {
        document.getElementById(id).disabled = isAdmin;
    });
}

// Toggle mobile menu visibility
function toggleMenu() {
    const menu = document.getElementById('navbarMenu');
    menu.classList.toggle('active');
}