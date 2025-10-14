<?php
session_start();
require 'functions.php';
require 'validate.php';
require 'layout.php';

//Files
$pathToCSS = 'css/style.css';

//Database
$db = [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => '', //dont forget to remove before pushing
    'dbName' => 'website',
];

//Connect to database
$request['db'] = false;
try {
    $conn = connectDataBase($db);
    $request['db'] = $conn;
} catch (mysqli_sql_exception $e) {
    echo 'Connection failed: ' . $e;
}

$request = getRequest();

$request['menu'] = [
    'HOME',
    'ABOUT',
    'CONTACT',
    'WEBSHOP',
    'LOGIN',
    'REGISTER'
];

$response = validateRequest($request);

$response['CSS'] = $pathToCSS;
showResponse($response);
?>