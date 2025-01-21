<?php
include('includes/header.php');
include 'config.php';
include('functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        redirect('order.php');
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid login credentials.</div>";
    }
}
?>
<main class="container mt-4">
    <h2 class="text-center mb-4">Login</h2>
    <form method="POST" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="invalid-feedback">Please enter your username.</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">Please enter your password.</div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>
