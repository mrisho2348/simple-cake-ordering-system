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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_cake'])) {
    $cake_name = mysqli_real_escape_string($conn, $_POST['cake_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Check if the cake name already exists in the database
    $query_check = "SELECT COUNT(*) AS cake_count FROM cakes WHERE name = '$cake_name'";
    $result_check = mysqli_query($conn, $query_check);
    $row_check = mysqli_fetch_assoc($result_check);

    if ($row_check['cake_count'] > 0) {
        // Cake name already exists, show an error message
        $message = "Cake with this name already exists. Please choose a different name.";
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_size = $_FILES['image']['size'];
            $image_type = $_FILES['image']['type'];

            // Generate a unique name for the image
            $image_name_new = uniqid() . "_" . $image_name;
            $upload_dir = 'uploads/cakes/';
            $upload_path = $upload_dir . $image_name_new;

            // Check if the uploaded file is an image
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($image_type, $allowed_types)) {
                // Move the uploaded file to the desired directory
                if (move_uploaded_file($image_tmp, $upload_path)) {
                    // Insert the cake data into the database, including the image URL
                    $query = "INSERT INTO cakes (name, price, description, image_url) VALUES ('$cake_name', '$price', '$description', '$upload_path')";

                    if (mysqli_query($conn, $query)) {
                        $message = "Cake added successfully!";
                    } else {
                        $message = "Failed to add cake: " . mysqli_error($conn);
                    }
                } else {
                    $message = "Failed to upload image.";
                }
            } else {
                $message = "Only image files are allowed (JPEG, PNG, GIF).";
            }
        } else {
            $message = "Please upload an image.";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Cake</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


    <div class="container mt-5">
        <h2>Add New Cake</h2>

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

            <!-- Second Column: Cake Add Form -->
            <div class="col-md-9">
                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        <h5>Add New Cake</h5>
                    </div>
                    <div class="card-body">
                        <!-- Form to add new cake -->
                        <form action="add_cake.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="cake_name" class="form-label">Cake Name</label>
                                <input type="text" class="form-control" id="cake_name" name="cake_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Cake Image</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                            </div>
                            <button type="submit" name="add_cake" class="btn btn-primary">Add Cake</button>
                        </form>

                    </div>
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
