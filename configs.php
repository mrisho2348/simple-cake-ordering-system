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

// Fetch current database connection settings from a configuration file or database
$db_host = $dbConfig['host'];
$db_username = $dbConfig['username'];
$db_password = $dbConfig['password'];
$db_name = $dbConfig['database'];

// Handle form submission for updating database settings
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_db_settings'])) {
    // Get form values
    $new_db_host = mysqli_real_escape_string($conn, $_POST['db_host']);
    $new_db_username = mysqli_real_escape_string($conn, $_POST['db_username']);
    $new_db_password = mysqli_real_escape_string($conn, $_POST['db_password']);
    $new_db_name = mysqli_real_escape_string($conn, $_POST['db_name']);

    // Update the database settings (for simplicity, we will update the configuration file or database)
    // Here you would typically update a config file or database record that stores these settings.
    // Example: updating a config file

    $config_content = "<?php\n";
    $config_content .= "\$dbConfig = [\n";
    $config_content .= "  'host' => '$new_db_host',\n";
    $config_content .= "  'username' => '$new_db_username',\n";
    $config_content .= "  'password' => '$new_db_password',\n";
    $config_content .= "  'database' => '$new_db_name',\n";
    $config_content .= "];\n";

    file_put_contents('config.php', $config_content); // Save the new settings to the file

    $message = "Database settings updated successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


    <div class="container mt-5">
        <h2>Database Settings</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- First Column: Quick Actions -->
            <div class="col-md-3">
                <div class="card shadow-sm mt-4">
                    <!-- Include quick actions -->
                    <?php include('includes/quick_actions.php'); ?>
                </div>
            </div>

            <!-- Second Column: Database Settings Form -->
            <div class="col-md-9">
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <!-- Form for updating database settings -->
                        <form action="config.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="db_host" class="form-label">Database Host</label>
                                        <input type="text" class="form-control" id="db_host" name="db_host" value="<?php echo $db_host; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="db_username" class="form-label">Database Username</label>
                                        <input type="text" class="form-control" id="db_username" name="db_username" value="<?php echo $db_username; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="db_password" class="form-label">Database Password</label>
                                        <input type="password" class="form-control" id="db_password" name="db_password" value="<?php echo $db_password; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="db_name" class="form-label">Database Name</label>
                                        <input type="text" class="form-control" id="db_name" name="db_name" value="<?php echo $db_name; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="update_db_settings" class="btn btn-primary">Update Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Include footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
