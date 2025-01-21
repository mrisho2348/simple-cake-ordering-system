<?php
include 'includes/header.php';
// Include configuration and database connection
include('config.php');
include('functions.php');


// Check if the customer is logged in
if (!isset($_SESSION['customer_logged_in']) || $_SESSION['customer_logged_in'] !== true) {
    header("Location: register.php"); // Redirect to registration if not logged in
    exit;
}

// Get cake details from the query string
if (isset($_GET['cake_id'])) {
    $cake_id = mysqli_real_escape_string($conn, $_GET['cake_id']);

    // Fetch the cake details from the database
    $query = "SELECT * FROM cakes WHERE id = '$cake_id'";
    $result = mysqli_query($conn, $query);
    $cake = mysqli_fetch_assoc($result);
} else {
    echo "Invalid cake selection.";
    exit;
}

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $price = $cake['price'] * $quantity;

    // Insert the order into the orders table
    $order_query = "INSERT INTO orders (customer_id, cake_id, quantity, price) 
                    VALUES ('$customer_id', '$cake_id', '$quantity', '$price')";
    if (mysqli_query($conn, $order_query)) {
        $success_message = "Your order has been placed successfully!";
        header("Location: index.php");
    } else {
        $error_message = "There was an error placing the order. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - Cake Ordering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<div class="container mt-5">
    <h2>Order Cake</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <img src="<?= htmlspecialchars($cake['image_url']); ?>" class="card-img-top" alt="<?= htmlspecialchars($cake['name']); ?>">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($cake['name']); ?></h5>
            <p class="card-text"><?= htmlspecialchars($cake['description']); ?></p>
            <p class="card-text text-muted">Price: $<?= htmlspecialchars($cake['price']); ?></p>

            <!-- Order Form -->
            <form action="order.php?cake_id=<?= $cake['id']; ?>" method="POST">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary">Place Order</button>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
