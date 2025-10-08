<?php
//define('ALLOW_ACCESS',true);

if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    exit('Access denied.');
}

$servername = 'localhost';
$username   = 'root';
$password   = '';
$dbname     = 'website';

$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if (mysqli_query($conn, $sql)) {
    echo 'database created successfully!<br>';
} else {
    echo 'Error creating database: ' . mysqli_error($conn) .'<br>';
}

mysqli_select_db($conn, $dbname);

//Create database here
$sql = "CREATE TABLE IF NOT EXISTS items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo 'items created successfully!<br>';
} else {
    echo 'Error creating items: ' . mysqli_error($conn) . '<br>';
}

$sql = "CREATE TABLE IF NOT EXISTS order_status (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL UNIQUE
)";
if (mysqli_query($conn, $sql)) {
    echo 'order_status created successfully!<br>';
} else {
    echo 'Error creating order_status: ' . mysqli_error($conn) . '<br>';
}

//Insert order status options
$sql = "INSERT IGNORE INTO order_status (name) VALUES
('started'),
('paid'),
('processing'),
('shipped'),
('delivered'),
('cancelled')";
if (mysqli_query($conn, $sql)) {
    echo 'Inserted order_status values successfully!<br>';
} else {
    echo 'Error inserting order_status values: ' . mysqli_error($conn) . '<br>';
}

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo 'users created successfully!<br>';
} else {
    echo 'Error creating users: ' . mysqli_error($conn) . '<br>';
}

//Orders table
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,                  
    status_id TINYINT UNSIGNED NOT NULL DEFAULT 1,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (status_id) REFERENCES order_status(id)
)";
if (mysqli_query($conn, $sql)) {
    echo 'orders created successfully!<br>';
} else {
    echo 'Error creating orders: ' . mysqli_error($conn) . '<br>';
}

//Ordered items table
$sql = "CREATE TABLE IF NOT EXISTS order_items (
    order_id INT UNSIGNED NOT NULL,                 
    item_id INT UNSIGNED NOT NULL,                  
    amount INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (order_id, item_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id)
)";
if (mysqli_query($conn, $sql)) {
    echo 'order_items created successfully!<br>';
} else {
    echo 'Error creating order_items: ' . mysqli_error($conn) . '<br>';
}

mysqli_close($conn);
?> 