<?php
include('config.php');
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}

// Fetch total number of orders
function getTotalOrders() {
    global $conn; // Use the global $conn resource for the database connection
    $query = "SELECT COUNT(*) AS total_orders FROM orders"; // Adjust table name and field as necessary
    $result = mysqli_query($conn, $query); // Execute the query using procedural style

    if ($result) {
        $row = mysqli_fetch_assoc($result); // Fetch the result as an associative array
        return $row['total_orders']; // Return the total count of orders
    } else {
        return 0; // In case of an error, return 0
    }
}

// Fetch total number of users
function getTotalUsers() {
    global $conn; // Use the global $conn resource for the database connection
    $query = "SELECT COUNT(*) AS total_users FROM users"; // Adjust table name and field as necessary
    $result = mysqli_query($conn, $query); // Execute the query using procedural style

    if ($result) {
        $row = mysqli_fetch_assoc($result); // Fetch the result as an associative array
        return $row['total_users']; // Return the total count of users
    } else {
        return 0; // In case of an error, return 0
    }
}

function getTotalRevenue() {
    global $conn; // Use the global $conn resource for the database connection

    // Calculate the total revenue using the price and quantity fields
    $query = "SELECT SUM(price * quantity) AS total_revenue FROM orders";

    $result = mysqli_query($conn, $query); // Execute the query using procedural style

    if ($result) {
        $row = mysqli_fetch_assoc($result); // Fetch the result as an associative array
        return $row['total_revenue'] !== null ? $row['total_revenue'] : 0; // Return total revenue or 0 if null
    } else {
        return 0; // In case of an error, return 0
    }
}




function adminLogin($username, $password) {
    global $conn; // Ensure the database connection is available

    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to fetch the admin user data
    $query = "SELECT * FROM users WHERE username = '$username'"; // Assuming you're storing admin info in an 'admins' table
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        // Verify password (assuming it's hashed in the database)
        if (password_verify($password, $admin['password'])) {
            return true; // Login successful
        } else {
            return false; // Incorrect password
        }
    } else {
        return false; // Admin not found
    }
}

?>
