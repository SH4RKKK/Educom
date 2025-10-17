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

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$name        = 'GOLD STANDARD Double Chocolate';
$description = 'A cheap alternative whey protein that has slightly worse macros';
$price       = 49.99;
$image_path = '71OsEAdPuZL._UF1000,1000_QL80_.jpg';

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