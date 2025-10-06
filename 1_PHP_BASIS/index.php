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
$userDataPath = 'user/user.txt';
$pathToCSS = 'css/style.css';

//Building the page
$request = getRequest();

$request['userDataPath'] = $userDataPath;
$request['menu'] = $options;
$response = validateRequest($request);

$response['contact'] = $contactfields;
$response['login'] = $loginfields;
$response['register'] = $registerfields;
$response['CSS'] = $pathToCSS;
showResponse($response);
?>