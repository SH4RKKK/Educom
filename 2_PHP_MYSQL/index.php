<?php
session_start();
require 'functions.php';

//Menu
$options = [
    'HOME',
    'ABOUT',
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

//Database
$userDataBase = [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbName' => 'user_database',
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