<?php


if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];

    // Delete the customer from the database
    $delete_query = "DELETE FROM customers WHERE id = $customer_id";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: manage_customer.php"); // Redirect to the manage customers page
        exit;
    } else {
        echo "Error deleting customer: " . mysqli_error($conn);
    }
}


?>