<?php
session_start();
require_once '../controllers/Controller.php';

$controller = new Controller();
$controller->showPage();