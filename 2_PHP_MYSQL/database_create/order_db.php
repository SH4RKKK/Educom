<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "order_database";

$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if (!mysqli_query($conn, $sql)) {
    die("Error creating database: " . mysqli_error($conn));
}

mysqli_select_db($conn, $dbname);

//Order status table
$sql = "CREATE TABLE IF NOT EXISTS order_status (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL UNIQUE
)";
if (!mysqli_query($conn, $sql)) {
    die("Error creating order_status: " . mysqli_error($conn));
}

//Insert order status options
$sql = "INSERT IGNORE INTO order_status (name) VALUES
('started'),
('paid'),
('processing'),
('shipped'),
('delivered'),
('cancelled')";
if (!mysqli_query($conn, $sql)) {
    die("Error inserting order_status values: " . mysqli_error($conn));
}

//Orders table
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,                  -- user_database.users(id)
    status_id TINYINT UNSIGNED NOT NULL DEFAULT 1,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
if (!mysqli_query($conn, $sql)) {
    die("Error creating orders: " . mysqli_error($conn));
}

//Ordered items table
$sql = "CREATE TABLE IF NOT EXISTS order_items (
    order_id INT UNSIGNED NOT NULL,                 -- orders_database.orders(id)
    item_id INT UNSIGNED NOT NULL,                  -- items_database.items(id)
    amount INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (order_id, item_id)
)";
if (!mysqli_query($conn, $sql)) {
    die("Error creating order_items: " . mysqli_error($conn));
}

mysqli_close($conn);


/*
function userExists(mysqli $connUser, int $user_id): bool {
    $sql = "SELECT 1 FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connUser, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return (mysqli_num_rows($result) > 0);
}

function itemExists(mysqli $connItems, int $item_id): bool {
    $sql = "SELECT 1 FROM items WHERE id = ?";
    $stmt = mysqli_prepare($connItems, $sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return (mysqli_num_rows($result) > 0);
}

$connOrders = mysqli_connect('localhost','root','','order_database');
$connUsers  = mysqli_connect('localhost','root','','user_database');
$connItems  = mysqli_connect('localhost','root','','item_database');

$user_id = 7;

// verify user exists
if (!userExists($connUsers, $user_id)) {
    die('Invalid user ID');
}

// create an order
mysqli_query($connOrders, "INSERT INTO orders (user_id, total, status_id) VALUES ($user_id, 0, 1)");
$order_id = mysqli_insert_id($connOrders);

// add items
$item_id = 5;
$qty = 2;
$unit_price = getItemPrice($connItems, $item_id);

if (!itemExists($connItems, $item_id)) {
    die('Invalid item ID');
}

$sql = "INSERT INTO order_items (order_id, item_id, amount, unit_price)
        VALUES ($order_id, $item_id, $qty, $unit_price)";
mysqli_query($connOrders, $sql);

function getItemPrice(mysqli $connItems, int $item_id): float
{
    $sql = "SELECT price FROM items WHERE id = ?";
    $stmt = mysqli_prepare($connItems, $sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Return price as float
        return (float)$row['price'];
    }

    // If no row found, something is wrong
    die("Item with ID $item_id not found in items table.");
}
    
*/
?> 