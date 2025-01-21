<?php
include 'includes/header.php';
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists in the database
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // If the username exists, show an error message
        echo "<div class='alert alert-danger text-center'>Username already taken. Please choose another one.</div>";
    } else {
        // If the username doesn't exist, insert the new user
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success text-center'>Signup successful. <a href='login.php'>Login here</a></div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>


<main class="container mt-4">
    <h2 class="text-center mb-4">Signup</h2>
    <form method="POST" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="invalid-feedback">Please enter a username.</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">Please enter a password.</div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Signup</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
