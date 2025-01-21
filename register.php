<?php
include 'includes/header.php';
include('config.php');
include('functions.php');


// Check if the customer is already logged in
if (isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] === true) {
    header("Location: order.php"); // Redirect to order page if already logged in
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if email already exists
    $query = "SELECT * FROM customers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    // Get cake details from the query string
if (!isset($_GET['cake_id'])) {   
    header("Location: register.php"); 
    echo "Invalid cake selection.";
    exit;
}

   $cake_id = mysqli_real_escape_string($conn, $_GET['cake_id']);
    if (mysqli_num_rows($result) > 0) {
        $error_message = "Email already exists. Please login or use another email.";
    } else {
        // Insert new customer into the database
        $insert_query = "INSERT INTO customers (name, email, phone, address) 
                         VALUES ('$name', '$email', '$phone', '$address')";
        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['customer_logged_in'] = true;
            $_SESSION['cake_id'] =  $cake_id;
            $_SESSION['customer_id'] = mysqli_insert_id($conn);
            header("Location: order.php"); // Redirect to order page after successful registration
            exit;
        } else {
            $error_message = "Error registering the customer. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cake Ordering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<div class="container mt-5">
    <h2>Register to Place Order</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
