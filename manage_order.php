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

// Handle order deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $order_id = intval($_GET['delete']);
    $query = "DELETE FROM orders WHERE id = $order_id";
    if (mysqli_query($conn, $query)) {
        $message = "Order deleted successfully!";
    } else {
        $message = "Failed to delete the order: " . mysqli_error($conn);
    }
}

// Fetch all orders
$query = "SELECT orders.id, customers.name AS customer_name, cakes.name AS cake_name, orders.quantity, orders.price, orders.order_date 
          FROM orders 
          INNER JOIN customers ON orders.customer_id = customers.id 
          INNER JOIN cakes ON orders.cake_id = cakes.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
    <h2 class="mb-4 ">Manage Orders</h2>

    <!-- Display messages -->
    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Quick Actions Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm mt-4">
                <!-- Include quick actions -->
                <?php include('includes/quick_actions.php'); ?>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="col-md-9">
            <div class="table-responsive mt-4">
                <table id="ordersTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Cake Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cake_name']); ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                                    <td>$<?php echo number_format($row['quantity'] * $row['price'], 2); ?></td>
                                    <td><?php echo $row['order_date']; ?></td>
                                    <td>
                                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


    <!-- Include footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#ordersTable').DataTable();
        });
    </script>
</body>
</html>
