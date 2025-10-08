<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    exit('Access denied.');
}

$servername = 'localhost';
$username   = 'root';
$password   = '';
$dbname     = 'website';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$name        = 'PER4M Whey Protein - Caramel Biscuit';
$description = 'PER4M is a newly established brand that has one of the best qualities of protein powder';
$price       = 45.99;
$image_path = 'per4m-caramel-biscuit-whey-protein-powder-2kg-1_1200x.webp';

$stmt = mysqli_prepare($conn,
    "INSERT INTO items (name, description, price, image_path) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'ssds', $name, $description, $price, $image_path);

if (mysqli_stmt_execute($stmt)) {
    echo 'Inserted ID: ' . mysqli_insert_id($conn);
} else {
    echo 'Error inserting item: ' . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>