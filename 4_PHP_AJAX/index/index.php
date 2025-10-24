<?php
session_start();
require_once '../controllers/Controller.php';
require_once '../database/Database.php';

$database = new Database('localhost','website','root','');
$controller = new Controller($database );
$controller->showPage();