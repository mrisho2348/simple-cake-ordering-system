<?php
// Start the session to access session variables
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cake Ordering Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="bg-dark text-white py-3">
    <nav class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand text-white text-decoration-none" href="index.php">Cake Ordering</a>
        <div class="d-flex">
            <a href="index.php" class="btn btn-outline-light me-2">Home</a>
            <a href="dashboard.php" class="btn btn-outline-light me-2">Dashboard</a>
            <!-- Display these links only if the admin is logged in -->
            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                <a href="order_details.php" class="btn btn-outline-light me-2">Order Details</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
