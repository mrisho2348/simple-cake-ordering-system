<?php
include('includes/header.php');
// Include configuration and helper functions
include('config.php');
include('functions.php');



// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Redirect to login page if not logged in
    exit;
}

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    $query = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($conn, $query)) {
        $message = "User deleted successfully!";
    } else {
        $message = "Failed to delete the user: " . mysqli_error($conn);
    }
}

// Fetch all users
$query = "SELECT id, username FROM users";
$result = mysqli_query($conn, $query);

// Handle user creation (add new user)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the username already exists
    $check_username_query = "SELECT id FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_username_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Username already exists
        $message = "The username '$username' is already taken. Please choose another one.";
    } else {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        
        if (mysqli_query($conn, $query)) {
            $message = "User added successfully!";
        } else {
            $message = "Failed to add the user: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Include header -->


    <div class="container mt-5">
    <h2 class="mb-4">Manage Users</h2>

    <!-- Display messages -->
    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Bootstrap Grid: Row with two columns -->
    <div class="row">
        <!-- First Column: Quick Actions -->
        <div class="col-md-3">
            <div class="card shadow-sm mt-4">
                <!-- Include quick actions -->
                <?php include('includes/quick_actions.php'); ?>
            </div>
        </div>

        <!-- Second Column: User Table and Add User Form -->
        <div class="col-md-9">
            <!-- Add User Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Add New User</h5>
                </div>
                <div class="card-body">
                    <form action="manage_users.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <h3>Existing Users</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- End of Bootstrap row -->
</div>

    <!-- Include footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
