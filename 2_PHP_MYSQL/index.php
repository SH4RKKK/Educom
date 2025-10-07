<?php
session_start();
require 'functions.php';

//Menu
$options = [
    'HOME',
    'ABOUT',
    'WEBSHOP',
    'CONTACT',
    'LOGIN',
    'REGISTER'
];

//Fields
$contactfields = ['Naam', 'E-mail', 'Bericht'];
$loginfields = ['E-mail','Wachtwoord'];
$registerfields = ['Naam','E-mail', 'Wachtwoord','Herhaal wachtwoord'];

//Files
$pathToCSS = 'css/style.css';

//Databases
$userDataBase = [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => '', //dont forget to remove before pushing
    'dbName' => 'user_database',
];

$itemDataBase = [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => '', //dont forget to remove before pushing
    'dbName' => 'item_database',
];

$orderDataBase = [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => '', //dont forget to remove before pushing
    'dbName' => 'order_database',
];


//Building the page
$request = getRequest();

$request['userDatabase'] = $userDataBase;
$request['menu'] = $options;
$response = validateRequest($request);

$response['contact'] = $contactfields;
$response['login'] = $loginfields;
$response['register'] = $registerfields;
$response['CSS'] = $pathToCSS;
showResponse($response);
?>