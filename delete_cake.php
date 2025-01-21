<?php
// Include configuration and helper functions
include('config.php');
include('functions.php');

// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Redirect to login page if not logged in
    exit;
}

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $cake_id = (int)$_GET['id']; // Get the cake ID from the URL

    // First, check if the cake exists in the database
    $check_query = "SELECT id FROM cakes WHERE id = $cake_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Cake exists, proceed to delete
        $delete_query = "DELETE FROM cakes WHERE id = $cake_id";

        if (mysqli_query($conn, $delete_query)) {
            // Redirect back to the manage cakes page with a success message
            header("Location: manage_cakes.php?message=success");
            exit;
        } else {
            echo "Error deleting cake: " . mysqli_error($conn);
        }
    } else {
        // Cake not found in the database
        echo "Cake not found.";
    }
} else {
    // If no ID is provided, redirect to the cakes management page
    header("Location: manage_cakes.php");
    exit;
}
?>
