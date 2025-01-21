<?php
include 'includes/header.php';
// Include configuration and helper functions
include('config.php');
include('functions.php');


// Check if the user is already logged in (Redirect to dashboard)
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Initialize variables for error messages
$error_message = "";

// Handle the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password are correct
    if (adminLogin($username, $password)) {
        // Set session and redirect to dashboard
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        // Invalid login credentials
        $error_message = "Invalid username or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Admin Login</h2>

            <!-- Login Form -->
            <form method="POST" action="admin.php">
                <?php if (!empty($error_message)) { ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php } ?>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <!-- Link to the Signup page for new users -->
            <div class="text-center mt-3">
                <p>Don't have an account? <a href="signup.php" class="btn btn-link">Sign up here</a></p>
            </div>
        </div>
    </div>

    <!-- Include footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS and Popper.js for dynamic elements -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
