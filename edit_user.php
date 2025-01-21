<?php
include 'includes/header.php';
// Include configuration and helper functions
include('config.php');
include('functions.php');


// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Redirect to login page if not logged in
    exit;
}

// Check if the user ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    // Fetch user data for the given ID
    $query = "SELECT id, username FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Get the user data
        $user_data = mysqli_fetch_assoc($result);
        $username = $user_data['username'];
    } else {
        // User not found
        $message = "User not found.";
    }
} else {
    // Invalid ID provided
    $message = "Invalid user ID.";
}

// Handle form submission for updating user data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the username already exists for a different user
    $check_username_query = "SELECT id FROM users WHERE username = '$username' AND id != $user_id";
    $check_result = mysqli_query($conn, $check_username_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Username already exists
        $message = "The username '$username' is already taken. Please choose another one.";
    } else {
        // If password is provided, hash it before updating
        $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

        // Update user data
        if ($hashed_password) {
            $query = "UPDATE users SET username = '$username', password = '$hashed_password' WHERE id = $user_id";
        } else {
            $query = "UPDATE users SET username = '$username' WHERE id = $user_id";
        }

        if (mysqli_query($conn, $query)) {
            header("Location: manage_users.php");
        } else {
            $message = "Failed to update the user: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


    <div class="container mt-5">
    <h2>Edit User</h2>

    <!-- Display messages -->
    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Start of the Bootstrap grid layout -->
    <div class="row">
        <!-- First column: Quick Actions -->
        <div class="col-md-3">
            <div class="card shadow-sm mt-4">
                <!-- Include quick actions -->
                <?php include('includes/quick_actions.php'); ?>
            </div>
        </div>

        <!-- Second column: Edit User Form -->
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Edit User Details</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($user_data)): ?>
                        <form action="edit_user.php?id=<?php echo $user_id; ?>" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password (Leave empty to keep current)</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" name="edit_user" class="btn btn-primary">Update User</button>
                        </form>
                    <?php else: ?>
                        <p>User not found or invalid ID.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End of grid layout -->

</div>


    <!-- Include footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
