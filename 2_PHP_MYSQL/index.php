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
$request['db'] = false;
try {
    $request['db'] = mysqli_connect(
        'localhost',
        'root',
        '', //dont forget to remove before pushing
        'website'
    );
} catch (mysqli_sql_exception $e) {
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

if($response['db']) {
    mysqli_close($response['db']);
}
?>