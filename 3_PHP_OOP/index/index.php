<?php
session_start();
require_once '../controller/controller.php';

$controller = new Controller();
$controller->showPage();
?>