<?php
session_start();
require 'functions.php';
require 'validate.php';
require 'layout.php';

//Files
$pathToCSS = 'css/style.css';

//Get request data
$request = getRequest();

//Connect to database
$request['db'] = null;
try {
    $request['db'] = mysqli_connect(
        'localhost',
        'root',
        '', //dont forget to remove before pushing
        'website'
    );
} catch (mysqli_sql_exception $e) {
    $request['db'] = null;
    echo 'Connection failed: ' . $e;
}

//Options
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

if($request['db']) mysqli_close($request['db']);
?>