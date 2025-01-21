<?php
$host = 'localhost';
$db_name = 'cake_ordering';
$username = 'root';
$password = '';
$dbConfig = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'cake_ordering',
];
// Create connection
$conn = mysqli_connect($host, $username, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create tables
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)");

// Create table for storing customer information
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL
)");

// Create table for storing cake information
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS cakes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
)");



// Create table for storing customer orders
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    cake_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (cake_id) REFERENCES cakes(id)
)");

// Check if the 'price' column exists and add it if not
$result = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'price'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "ALTER TABLE orders ADD COLUMN price DECIMAL(10, 2) DEFAULT 0");
}


?>
