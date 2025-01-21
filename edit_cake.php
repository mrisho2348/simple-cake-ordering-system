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

// Check if the cake ID is passed in the URL
if (isset($_GET['id'])) {
    $cake_id = $_GET['id'];

    // Fetch the cake data from the database
    $query = "SELECT id, name, description, price, image_url FROM cakes WHERE id = $cake_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $cake = mysqli_fetch_assoc($result);
    } else {
        echo "Cake not found.";
        exit;
    }
}

// Handle form submission for updating cake details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    // Check if the cake name already exists in the database (excluding the current cake)
    $name_check_query = "SELECT COUNT(*) AS count FROM cakes WHERE name = '$name' AND id != $cake_id";
    $name_check_result = mysqli_query($conn, $name_check_query);
    $name_check_row = mysqli_fetch_assoc($name_check_result);

    // Check if the image URL already exists in the database (excluding the current cake)
    $image_url_check_query = "SELECT COUNT(*) AS count FROM cakes WHERE image_url = '$image_url' AND id != $cake_id";
    $image_url_check_result = mysqli_query($conn, $image_url_check_query);
    $image_url_check_row = mysqli_fetch_assoc($image_url_check_result);

    if ($name_check_row['count'] > 0) {
        // Name already exists
        echo "Error: A cake with this name already exists.";
    } elseif ($image_url_check_row['count'] > 0) {
        // Image URL already exists
        echo "Error: A cake with this image URL already exists.";
    } else {
        // No duplicates, proceed with the update
        $update_query = "UPDATE cakes SET name = '$name', description = '$description', price = '$price', image_url = '$image_url' WHERE id = $cake_id";
        if (mysqli_query($conn, $update_query)) {
            header("Location: manage_cakes.php"); // Redirect back to the cakes management page
            exit;
        } else {
            echo "Error updating cake: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cake</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


    <div class="container mt-5">
        <h2>Edit Cake</h2>

        <!-- Bootstrap Grid: Row with two columns -->
        <div class="row">
            <!-- First Column: Quick Actions -->
            <div class="col-md-3">
                <div class="card shadow-sm mt-4">
                    <!-- Include quick actions -->
                    <?php include('includes/quick_actions.php'); ?>
                </div>
            </div>

            <!-- Second Column: Cake Edit Form -->
            <div class="col-md-9">
                <form action="edit_cake.php?id=<?php echo $cake['id']; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Cake Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($cake['name']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($cake['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($cake['price']); ?>" required step="0.01">
                    </div>

                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo htmlspecialchars($cake['image_url']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Cake</button>
                </form>
            </div>
        </div> <!-- End of Bootstrap row -->
    </div>

    <!-- Include footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
