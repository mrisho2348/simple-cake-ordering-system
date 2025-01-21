<?php
include 'includes/header.php';
// Include database connection and header
include 'config.php'; // Include database configuration to establish connection

?>

<main class="container mt-4">
    <h1 class="text-center mb-4">Welcome to the Cake Ordering Website</h1>
    <p class="text-center mb-5">Order your favorite cakes with a few clicks! Freshly baked, made just for you!</p>

    <!-- Hero Section: Eye-catching banner with some intro text -->
    <div class="jumbotron text-center">
        <h2>Discover Our Bestsellers</h2>
        <p>Browse through our most popular cakes and place your order right here.</p>
        <a href="#cakes" class="btn btn-danger btn-lg">Browse Cakes</a>
    </div>

    <!-- Cake Display Section -->
    <div id="cakes" class="row">
        <?php
        // Query to get all cakes from the database
        $query = "SELECT * FROM cakes";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($cake = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="<?= htmlspecialchars($cake['image_url']); ?>" class="card-img-top" alt="<?= htmlspecialchars($cake['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($cake['name']); ?></h5>
                            <p class="card-text text-muted">Price: $<?= htmlspecialchars($cake['price']); ?></p>
                            <p class="card-text"><?= htmlspecialchars($cake['description']); ?></p>
                            <a href="order.php?cake_id=<?= $cake['id']; ?>" class="btn btn-primary">Order Now</a>
                        </div>
                    </div>
                </div>
            <?php }
        } else {
            echo "<p>No cakes available at the moment.</p>";
        }
        ?>
    </div>

    <!-- Special Offers Section -->
    <div class="row mt-5">
        <div class="col-md-6 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Limited Time Offer</h5>
                    <p class="card-text">Get 10% off on your first order! Use code <strong>FIRST10</strong> at checkout.</p>
                    <a href="#" class="btn btn-light">Order Now</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Free Delivery</h5>
                    <p class="card-text">Enjoy free delivery on orders above $50. Fresh cakes delivered to your doorstep!</p>
                    <a href="#" class="btn btn-light">Order Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="mt-5">
        <h3 class="text-center mb-4">What Our Customers Are Saying</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="card-text">"The best cake I have ever tasted! The delivery was fast and the cake was still fresh. Highly recommend!"</p>
                        <footer class="blockquote-footer">Jane Doe</footer>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="card-text">"Amazing selection of cakes. I ordered the chocolate fudge, and it was absolutely delicious."</p>
                        <footer class="blockquote-footer">John Smith</footer>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="card-text">"The cake arrived perfectly packed and tasted like heaven! Will definitely order again."</p>
                        <footer class="blockquote-footer">Emily Johnson</footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
