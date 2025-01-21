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

// Fetch admin data or statistics
$admin_username = $_SESSION['admin_username']; // Assuming the admin's username is stored in session
$total_orders = getTotalOrders();  // You need to implement this function
$total_users = getTotalUsers();    // You need to implement this function
$total_revenue = getTotalRevenue(); // You need to implement this function
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


    <div class="container mt-5">
        <h2 class="text-center">Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h2>
        <p class="text-center">Admin Dashboard</p>

        <!-- Dashboard stats -->
        <div class="row text-center my-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Total Orders</h3>
                        <p class="card-text fs-4"><?php echo $total_orders; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Total Users</h3>
                        <p class="card-text fs-4"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Total Revenue</h3>
                        <p class="card-text fs-4">$<?php echo number_format($total_revenue, 2); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="card shadow-sm mt-4">
        <?php include('includes/quick_actions.php'); ?>
    </div>

    <!-- Include footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
