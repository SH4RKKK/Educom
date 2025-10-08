<?php
session_start();
require 'functions.php';

//Menu
$options = [
    'HOME',
    'ABOUT',
    'CONTACT',
    'WEBSHOP',
    'LOGIN',
    'REGISTER'
];

//Fields
$contactFields = ['Naam', 'E-mail', 'Bericht'];
$loginFields = ['E-mail','Wachtwoord'];
$registerFields = ['Naam','E-mail', 'Wachtwoord','Herhaal wachtwoord'];

//$webshopFields = ['amount'];

//Files
$pathToCSS = 'css/style.css';

//Databases
$userDataBase = [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => '', //dont forget to remove before pushing
    'dbName' => 'user_database',
];

//Building the page
$request = getRequest();

$request['userDatabase'] = $userDataBase;
$request['itemDatabase'] = $itemDataBase;
$request['menu'] = $options;
$response = validateRequest($request);

$response['contact'] = $contactFields;
$response['login'] = $loginFields;
$response['register'] = $registerFields;
//$response['webshop'] = $webshopFields;
$response['CSS'] = $pathToCSS;

showResponse($response);
?>